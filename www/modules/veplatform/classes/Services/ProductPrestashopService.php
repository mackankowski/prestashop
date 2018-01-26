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

class ProductPrestashopService implements ProductPrestashopServiceInterface
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
     * @var PrestashopProductFactory $prestashopProductFactory
     */
    public $prestashopProductFactory;

    /**
     * @var PrestashopCategoryFactory $prestashopCategoryFactory
     */
    public $prestashopCategoryFactory;

    public function __construct($logger, $productPrestashop, $categoryPrestashop)
    {
        $this->logger = $logger;
        $this->context = Context::getContext();
        $this->prestashopProductFactory = $productPrestashop;
        $this->prestashopCategoryFactory = $categoryPrestashop;
    }

    /**
     * Add bigImageUrl property to ProductModel
     * Add smallImageUrl property to ProductModel
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addImageUrls($product)
    {
        $productId = $product->getExternalProductId();

        try {
            $cover = Image::getCover($productId);
            $rewriteInfo = Product::getUrlRewriteInformations($productId);

            if (isset($rewriteInfo) && isset($cover) && !empty($rewriteInfo) && !empty($cover)) {
                $bigImageUrl = $this->context->link->getImageLink(
                    $rewriteInfo[0]['link_rewrite'],
                    $productId . '-' . $cover['id_image']
                );

                $imageFormatName = method_exists('ImageType', 'getFormattedName') ?
                    ImageType::getFormattedName('small') : $imageFormatName = ImageType::getFormatedName('small');

                $smallImageUrl = $this->context->link->getImageLink(
                    $rewriteInfo[0]['link_rewrite'],
                    $productId . '-' . $cover['id_image'],
                    $imageFormatName
                );

                $product->setBigImageUrl($bigImageUrl);
                $product->setSmallImageUrl($smallImageUrl);
            }
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        return $product;
    }

    /**
     * Add brandName property to ProductModel
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addBrandName($product)
    {
        $manufacturerId = $product->getBrandName();
        try {
            //there is a posibility that the product doesn't have a manufacturer set and this return false
            $manufacturerName = Manufacturer::getNameById((int)$manufacturerId);

            if ($manufacturerName) {
                $product->setBrandName($manufacturerName);
            } else {
                $product->removeProperty('brandName');
            }
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }
        return $product;
    }

    /**
     * Add isPromoted property to ProductModel
     *
     * @param ProductModel $product
     * @param bool         $isPromoted
     *
     * @return ProductModel
     */
    private function addIsPromoted($product, $isPromoted)
    {
        $product->setIsPromoted($isPromoted);

        return $product;
    }

    /**
     * Add IsOnSale property to ProductModel
     *
     * @param ProductModel $product
     * @param bool         $isOnSale
     *
     * @return ProductModel
     */
    private function addIsOnSale($product, $isOnSale)
    {
        $discountType = $product->getDiscountType();
        if (!empty($discountType)) {
            $isOnSale = true;
        } else {
            $product->removeProperty('discountType');
        }

        $product->setIsOnSale($isOnSale);
        return $product;
    }

    /**
     * Add isFreeDelivery property to ProductModel
     *
     * @param ProductModel $product Product
     * @param bool         $isFreeDelivery
     *
     * @return ProductModel
     */
    private function addIsFreeDelivery($product, $isFreeDelivery)
    {
        $product->setIsFreeDelivery($isFreeDelivery);

        return $product;
    }

    /**
     * Add isInStock property to ProductModel
     *
     * @param ProductModel $product
     * @param bool         $isInStock
     *
     * @return ProductModel
     */
    private function addIsInStock($product)
    {
        $availableForOrder = $product->getIsInStock();
        $stockQuantity = $product->getStockQuantity();

        if (isset($availableForOrder) && $availableForOrder && $stockQuantity > 0) {
            $product->setIsInStock(true);
        } else {
            $product->setIsInStock(false);
        }

        return $product;
    }

    /**
     * Add stockQuantity property to ProductModel
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addStockQuantity($product)
    {
        $productId = $product->getExternalProductId();

        try {
            $stockQuantity = Product::getQuantity($productId);
            $product->setStockQuantity($stockQuantity);
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        return $product;
    }

    /**
     * Add shippingInfo property to ProductModel
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addShippingInfo($product)
    {
        //No Shipping Info available at the moment
        $product->removeProperty('shippingInfo');

        return $product;
    }

    /**
     * Add url property to ProductModel
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addUrl($product)
    {
        //for continuousProductSync where the products were added by a deleteEvent the url is already filled
        if (!empty($product->getUrl())) {
            return $product;
        }

        $productId = $product->getExternalProductId();
        if (empty($productId)) {
            $this->logger->logException(
                new Exception('The external productId for this product is empty ' . print_r($product, true))
            );
            return $product;
        }

        $prestashopProduct = $this->prestashopProductFactory->createProduct((int)$productId);
        $productLink = $this->context->link->getProductLink($prestashopProduct);

        if (isset($productLink)) {
            $product->setUrl($productLink);
        }

        return $product;
    }

    /**
     * Add currencyCode property to ProductModel
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addCurrencyCode($product)
    {
        try {
            $currency = Currency::getDefaultCurrency();
            $product->setCurrencyCode($currency->iso_code);
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }
        return $product;
    }

    /**
     * Add shippingCurrencyCode property to ProductModel
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addShippingCurrencyCode($product)
    {
        $shippingAmount = $product->getShippingAmount();

        if (isset($shippingAmount)) {
            $currency = $product->getCurrencyCode();
            $product->setShippingCurrencyCode($currency);
        } else {
            $product->removeProperty('shippingAmount');
            $product->removeProperty('shippingCurrencyCode');
        }

        return $product;
    }

    /**
     * Add cultureCode property to ProductModel
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addCultureCode($product)
    {
        $lannguageIsoCode = $this->context->language->iso_code;

        $product->setCultureCode($lannguageIsoCode);
        return $product;
    }

    /**
     * Add categories property to ProductModel
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addCategories($product)
    {
        $productId = $product->getExternalProductId();
        try {
            $categoriesIds = Product::getProductCategories($productId);

            $categoriesInfo = Category::getCategoryInformations($categoriesIds, $this->context->language->id);
            //default format of the category mush be a key-value pair
            $categories = array(
                '' => ''
            );

            if (!empty($categoriesInfo)) {
                $categoryInfo = end($categoriesInfo);
                $categories = array(
                    $categoryInfo['name'] => $this->createProductBreadcrumb($categoriesInfo)
                );
            }

            $product->setCategories($categories);
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }
        return $product;
    }

    /**
     * Create Categories Breadcrumb
     *
     * @param array $categories
     *
     * @return string
     */
    private function createProductBreadcrumb($categories)
    {
        $breadcrumb = '';

        foreach ($categories as $category) {
            $breadcrumb .= $category['name'] . ',';
        }

        return Tools::substr($breadcrumb, 0, -1);
    }

    /**
     * Add discountAmount and discountType property to ProductModel
     * If Product is discounted
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addDiscountAmountAndType($product)
    {
        $productId = $product->getExternalProductId();

        try {
            $isDiscounted = $this->hasSpecificPrice($productId);
            if ($isDiscounted) {
                $specificPriceInfo = SpecificPrice::getByProductId($productId);

                $discountAmount = $specificPriceInfo[0]['reduction'];
                $discountType = $specificPriceInfo[0]['reduction_type'];

                $product->setDiscountAmount($discountAmount);
                $product->setDiscountType($discountType);
            } else {
                $product->removeProperty('discountAmount');
            }
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        return $product;
    }

    /**
     * Check if a product has a specific price, a discount
     *
     * @param int $productId
     *
     * @return bool
     */
    private function hasSpecificPrice($productId)
    {
        $quantity = 1;

        $idCurrency = (int)$this->context->cookie->id_currency;
        $idCustomer = (int)$this->context->customer->id;
        $idGroup = (int)Group::getCurrent()->id;
        $idCountry = $idCustomer ? (int)Customer::getCurrentCountry($idCustomer) : $this->getCountry();

        $isDiscounted = (bool)SpecificPrice::getSpecificPrice(
            (int)$productId,
            $this->context->shop->id,
            $idCurrency,
            $idCountry,
            $idGroup,
            $quantity,
            null,
            0,
            0,
            $quantity
        );

        return $isDiscounted;
    }

    /**
     * Get country id
     *
     * @return int
     */
    private function getCountry()
    {
        if (method_exists(Tools::class, 'getCountry')) {
            return (int)Tools::getCountry();
        }

        if (Configuration::get('PS_DETECT_COUNTRY') && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            preg_match('#(?<=-)\w\w|\w\w(?!-)#', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $array);
            if (is_array($array) && isset($array[0]) && Validate::isLanguageIsoCode($array[0])) {
                $id_country = (int)Country::getByIso($array[0], true);
            }
        }

        if (!isset($id_country) || !$id_country) {
            $id_country = (int)Configuration::get('PS_COUNTRY_DEFAULT');
        }

        return (int)$id_country;
    }

    /**
     * Add rating properties to ProductModel
     * Add reviews properties to ProductModel
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    private function addRatingsAndReviews($product)
    {
        $product->removeProperty('reviewCount');
        $product->removeProperty('ratingCount');
        $product->removeProperty('avgRating');
        return $product;
    }

    /**
     * Decorate product with extra properties
     *
     * @param ProductModel $product
     *
     * @return ProductModel
     */
    public function decorateProduct($product)
    {
        try {
            $decoratedProduct = $this->addImageUrls($product);
            $decoratedProduct = $this->addBrandName($decoratedProduct);
            $decoratedProduct = $this->addIsPromoted($decoratedProduct, false);
            $decoratedProduct = $this->addIsFreeDelivery($decoratedProduct, false);
            $decoratedProduct = $this->addStockQuantity($decoratedProduct);
            $decoratedProduct = $this->addIsInStock($decoratedProduct);
            $decoratedProduct = $this->addUrl($decoratedProduct);
            $decoratedProduct = $this->addCurrencyCode($decoratedProduct);
            $decoratedProduct = $this->addCultureCode($decoratedProduct);
            $decoratedProduct = $this->addCategories($decoratedProduct);
            $decoratedProduct = $this->addShippingInfo($decoratedProduct);
            $decoratedProduct = $this->addShippingCurrencyCode($decoratedProduct);
            $decoratedProduct = $this->addDiscountAmountAndType($decoratedProduct);
            $decoratedProduct = $this->addRatingsAndReviews($decoratedProduct);
            $decoratedProduct = $this->addCurrencyCode($decoratedProduct);
            $decoratedProduct = $this->addIsOnSale($decoratedProduct, false);

            return $decoratedProduct;
        } catch (Exception $exception) {
            $this->logger->logException($exception);
            $this->logger->logException(new Exception('Failed to decorate this product ' . print_r((array)
                $product, true)));
            return null;
        }
    }
}
