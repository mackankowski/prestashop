<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    Ve Interactive <info@veinteractive.com>
 * @copyright 2017 Ve Interactive
 * @license   MIT License
 *
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

class PrestashopBasketBuilder implements BasketBuilderInterface
{
    /**
     * @var ContextCore $context
     */
    private $context;

    /**
     * @var VeLogger $logger
     */
    private $logger;

    /**
     * @var PrestashopContext $prestashopContext
     */
    private $prestashopContext;

    /**
     * @var PrestashopProductFactory $prestashopProductFactory
     */
    private $prestashopProductFactory;

    const BASKET_REBUILD_FEATURE = 'basketRebuild';
    const PRODUCT_ID_QUANTITY = 'pidq=';

    public function __construct($logger, $prestashopProductFactory, $prestashopContext)
    {
        $this->logger = $logger;
        $this->prestashopProductFactory = $prestashopProductFactory;
        $this->context = Context::getContext();
        $this->prestashopContext = $prestashopContext;
    }

    /**
     * Add product to the cart
     * based on processChangeProductInCart() from CartController
     *
     * @param int $productId
     * @param int $quantity
     * @param int $productAttribute
     *
     * @return void
     */
    public function addProductToCart($productId, $quantity, $productAttribute = null)
    {
        $product = $this->initializeProduct($productId);

        //check availability for this product
        if (!$product || !$this->checkProductAvailability($product, $productId, $quantity, $productAttribute)) {
            return;
        }

        // Add cart if no cart found
        if (!$this->context->cart->id) {
            $this->addCartToContext();
        }

        // Add product to cart
        $response = $this->context->cart->updateQty($quantity, $productId, $productAttribute);
    }

    /**
     * Validate productQuantity
     *
     * @param int $quantity
     *
     * @return bool
     */
    private function validateQuantity($quantity)
    {
        if (!isset($quantity) || !is_numeric($quantity) || $quantity < 1) {
            return false;
        }
        return true;
    }

    /**
     * Validate $productId
     *
     * @param int $productId
     *
     * @return bool
     */
    private function validateId($productId)
    {
        if (!isset($productId) || !is_numeric($productId)) {
            return false;
        }
        return true;
    }

    /**
     * Initialize Product
     *
     * @param int $productId
     *
     * @return mixed
     */
    public function initializeProduct($productId)
    {
        $product = $this->prestashopProductFactory->createProduct($productId, $this->context->language->id);

        // Product is not available
        if (!$product->id || !$product->active || !$product->checkAccess($this->context->cart->id_customer)) {
            $this->logger->logMessage('The product with productId = ' . $productId . ' is no longer available', 'ERROR');
            return false;
        }

        return $product;
    }

    /**
     * Check product availability
     *
     * @param Product $product
     * @param int     $productId
     * @param int     $quantity
     * @param int     $productAttribute
     *
     * @return bool
     */
    public function checkProductAvailability($product, $productId, $quantity, $productAttribute)
    {
        // Check product quantity availability
        if ($productAttribute) {
            if (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && !Attribute::checkAttributeQty($productAttribute, $quantity)) {
                $this->logger->logMessage('There aren\'t enough items with the productId = ' . $productId . ' in stock.', 'ERROR');
                return false;
            }
        } elseif ($product->hasAttributes()) {
            // If out_of_stock property is 2 it means that the product behaviour is defined by the global setting
            // found in prefix_configuration at the key PS_ORDER_OUT_OF_STOCK
            $minimumQuantity = ($product->out_of_stock == 2) ? !Configuration::get('PS_ORDER_OUT_OF_STOCK') : !$product->out_of_stock;
            $productAttribute = Product::getDefaultAttribute($product->id, $minimumQuantity);

            if (!$productAttribute) {
                Tools::redirectAdmin($this->context->link->getProductLink($product));
            } elseif (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && !Attribute::checkAttributeQty($productAttribute, $quantity)) {
                $this->logger->logMessage('There aren\'t enough items with the productId = ' . $productId . ' in stock.', 'ERROR');
                return false;
            }
        } elseif (!$product->checkQty($quantity)) {
            $this->logger->logMessage('There aren\'t enough items with the productId = ' . $productId . ' in stock.', 'ERROR');
            return false;
        }

        return true;
    }

    /**
     * Add cart to context
     *
     * @return void
     */
    private function addCartToContext()
    {
        if (Context::getContext()->cookie->id_guest) {
            $guest = new Guest(Context::getContext()->cookie->id_guest);
            $this->context->cart->mobile_theme = $guest->mobile_theme;
        }
        $this->context->cart->add();
        if ($this->context->cart->id) {
            $this->context->cookie->id_cart = (int)$this->context->cart->id;
        }
    }

    /**
     * Deletes all products from cart
     */
    public function clearCart()
    {
        $existingProducts = $this->prestashopContext->getProducts();
        foreach ($existingProducts as $existingProduct) {
            $deleted = $this->prestashopContext->deleteProduct($existingProduct['id_product'], $existingProduct['id_product_attribute']);

            if (!$deleted) {
                $this->logger->logMessage('Product with name ' . $existingProduct['name'] . 'could not be deleted from cart', 'ERROR');
            }
        }
    }

    /**
     * Process productIdQuantity received and return products
     *
     * @param string $productIdQuantity
     *
     * @return bool
     */
    public function rebuildBasket($productIdQuantity)
    {
        $failedToAdd = 0;

        $this->clearCart();

        $products = explode(';', $productIdQuantity);

        //if the delimiter for productIdentifier and quantity doesn't exist in the string
        if (strpos($productIdQuantity, ',') === false) {
            return false;
        }

        foreach ($products as $product) {
            $productParams = explode(',', $product);

            $productId = $productParams[0];
            $productQuantity = $productParams[1];
            if (!isset($productParams[2])) {
                $productAttributeId = null;
            } else {
                $productAttributeId = $this->validateId($productParams[2]) ? $productParams[2] : null;
            }

            if (!$this->validateId($productId) || !$this->validateQuantity((int)
                $productQuantity)
            ) {
                $failedToAdd++;
                continue;
            }

            $this->addProductToCart($productId, $productQuantity, $productAttributeId);
        }

        //if none of the products were added return false - home page
        $response = $failedToAdd == count($products) ? false : true;
        return $response;
    }
}
