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

class ProductMapper implements ProductMapperInterface
{
    /**
     * @var ProductCore $productCore
     */
    private $productCore;

    public function __construct($productCore = null)
    {
        $this->productCore = $productCore;
    }

    /**
     * Map Product from DB to our Product Model
     *
     * @param array        $productToMap  Product from DB
     * @param ProductModel $mappedProduct Product we want to map
     *
     * @return ProductModel $mappedProduct
     */
    public function mapProduct(array $productToMap, ProductModel $mappedProduct)
    {
        $price = $this->calculatePriceWithTaxes($productToMap['id_product']);
        $currency = Currency::getDefaultCurrency();

        $mappedProduct->setCode($productToMap['reference']);
        $mappedProduct->setDescription($productToMap['description']);
        $mappedProduct->setExternalProductId($productToMap['id_product']);
        $mappedProduct->setName($productToMap['name']);
        $mappedProduct->setShippingAmount($productToMap['additional_shipping_cost']);
        $mappedProduct->setPriceValue($price);
        $mappedProduct->setPriceWithCurrencySymbol(ToolsCore::displayPrice($price, $currency->id));
        $mappedProduct->setBrandName($productToMap['id_manufacturer']);
        $mappedProduct->setIsInStock($productToMap['available_for_order']);

        $isBlacklisted = $mappedProduct->getIsBlacklisted();
        if (!isset($isBlacklisted)) {
            $mappedProduct->setIsBlacklisted(false);
        }

        return $mappedProduct;
    }

    /**
     * calculate the price including taxes
     *
     * @param int $idProduct product id
     *
     * @return float $price price including taxes
     */
    private function calculatePriceWithTaxes($idProduct = null)
    {
        if (isset($this->productCore) && isset($idProduct)) {
            return $this->productCore->getPriceStatic($idProduct, true, null, 2, null, false, true, 1, false, null, -1);
        }
        return 0;
    }
}
