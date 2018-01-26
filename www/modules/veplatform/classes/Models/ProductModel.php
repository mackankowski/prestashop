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

class ProductModel implements ProductInterface, JsonSerializable
{
    const PRODUCT_STRING_PROPERTY_DEFAULT_VALUE = '';
    const PRODUCT_BOOL_PROPERTY_DEFAULT_VALUE = false;
    const PRODUCT_INT_PROPERTY_DEFAULT_VALUE = 0;
    const PRODUCT_FLOAT_PROPERTY_DEFAULT_VALUE = 0.00;

    /**
     * @var string $bigImageUrl
     * Big resolution/size image path
     */
    private $bigImageUrl;

    /**
     * @var string $brandName
     * Manufacturer name
     */
    private $brandName;

    /**
     * @var string $code
     * SKU code
     */
    private $code;

    /**
     * @var string $description
     * Product description
     */
    private $description;

    /**
     * @var string $externalProductId
     * Product Id
     */
    private $externalProductId;

    /**
     * @var bool $isFreeDelivery
     * Product has free delivery
     */
    private $isFreeDelivery;

    /**
     * @var bool $isInStock
     * Product is in stock
     */
    private $isInStock;

    /**
     * @var bool $isOnSale
     * Product is on sale
     */
    private $isOnSale;

    /**
     * @var bool $isBlacklisted
     * Product is blacklisted
     */
    private $isBlacklisted;

    /**
     * @var bool $isPromoted
     * Product is promoted
     */
    private $isPromoted;

    /**
     * @var string $name
     * Product name
     */
    private $name;

    /**
     * @var float $priceValue
     * Product price without currency symbol
     */
    private $priceValue;

    /**
     * @var float $shippingInfo
     * Product shipping information
     */
    private $shippingInfo;

    /**
     * @var string $shippingAmount
     * Shipping cost
     */
    private $shippingAmount;

    /**
     * @var string $shippingCurrencyCode
     * Shipping's cost currency isoCode
     */
    private $shippingCurrencyCode;

    /**
     * @var string $smallImageUrl
     * Small resolution/size image path
     */
    private $smallImageUrl;

    /**
     * @var int $stockQuantity
     * Product stock quantity
     */
    private $stockQuantity;

    /**
     * @var string $cultureCode
     * Store's culture isoCode
     */
    private $cultureCode;

    /**
     * @var string $currencyCode
     * Store's currency isoCode
     */
    private $currencyCode;

    /**
     * @var array $categories
     * Product's categories and its paths
     */
    private $categories;

    /**
     * @var int $reviewCount
     * Number of reviews of the product
     */
    private $reviewCount;

    /**
     * @var int $ratingCount
     * Number of ratings the product has
     */
    private $ratingCount;

    /**
     * @var double $avgRating
     * Average rating of the product
     */
    private $avgRating;

    /**
     * @var string $discountType
     * Discount type for the product
     */
    private $discountType;

    /**
     * @var string $discountAmount
     * Discount amount for the product
     */
    private $discountAmount;

    /**
     * @var string $url
     * Product link
     */
    private $url;

    /**
     * @var string $priceWithCurrencySymbol
     * Product price including currency symbol
     */
    private $priceWithCurrencySymbol;

    /**
     * @return string
     */
    public function getBigImageUrl()
    {
        return $this->bigImageUrl;
    }

    /**
     * @param string $bigImageUrl
     */
    public function setBigImageUrl($bigImageUrl)
    {
        $this->bigImageUrl = $bigImageUrl;
    }

    /**
     * @return string
     */
    public function getBrandName()
    {
        return $this->brandName;
    }

    /**
     * @param string $brandName
     */
    public function setBrandName($brandName)
    {
        $this->brandName = $brandName;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $description = strip_tags($description);
        $description = html_entity_decode($description);
        $description = str_replace("\r\n", '', $description);

        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getExternalProductId()
    {
        return $this->externalProductId;
    }

    /**
     * @param string $externalProductId
     */
    public function setExternalProductId($externalProductId)
    {
        $this->externalProductId = $externalProductId;
    }

    /**
     * @return bool
     */
    public function getIsFreeDelivery()
    {
        return $this->isFreeDelivery;
    }

    /**
     * @param bool $isFreeDelivery
     */
    public function setIsFreeDelivery($isFreeDelivery)
    {
        $this->isFreeDelivery = (bool)$isFreeDelivery;
    }

    /**
     * @return bool
     */
    public function getIsInStock()
    {
        return $this->isInStock;
    }

    /**
     * @param bool $isInStock
     */
    public function setIsInStock($isInStock)
    {
        $this->isInStock = (bool)$isInStock;
    }

    /**
     * @return bool
     */
    public function getIsOnSale()
    {
        return $this->isOnSale;
    }

    /**
     * @param bool $isOnSale
     */
    public function setIsOnSale($isOnSale)
    {
        $this->isOnSale = (bool)$isOnSale;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPriceValue()
    {
        return $this->priceValue;
    }

    /**
     * @param string $priceValue
     */
    public function setPriceValue($priceValue)
    {
        $this->priceValue = (float)$priceValue;
    }

    /**
     * @return string
     */
    public function getShippingInfo()
    {
        return $this->shippingInfo;
    }

    /**
     * @param string $shippingInfo
     */
    public function setShippingInfo($shippingInfo)
    {
        $this->shippingInfo = $shippingInfo;
    }

    /**
     * @return string
     */
    public function getSmallImageUrl()
    {
        return $this->smallImageUrl;
    }

    /**
     * @param string $smallImageUrl
     */
    public function setSmallImageUrl($smallImageUrl)
    {
        $this->smallImageUrl = $smallImageUrl;
    }

    /**
     * @return int
     */
    public function getStockQuantity()
    {
        return $this->stockQuantity;
    }

    /**
     * @param int $stockQuantity
     */
    public function setStockQuantity($stockQuantity)
    {
        $this->stockQuantity = (int)$stockQuantity;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return boolean
     */
    public function getIsBlacklisted()
    {
        return $this->isBlacklisted;
    }

    /**
     * @param boolean $isBlacklisted
     */
    public function setIsBlacklisted($isBlacklisted)
    {
        $this->isBlacklisted = (bool)$isBlacklisted;
    }

    /**
     * @return boolean
     */
    public function getIsPromoted()
    {
        return $this->isPromoted;
    }

    /**
     * @param boolean $isPromoted
     */
    public function setIsPromoted($isPromoted)
    {
        $this->isPromoted = (bool)$isPromoted;
    }

    /**
     * @return double
     * Additional shipping fees (tax excl.)
     */
    public function getShippingAmount()
    {
        return $this->shippingAmount;
    }

    /**
     * @param double $shippingAmount
     */
    public function setShippingAmount($shippingAmount)
    {
        $this->shippingAmount = (double)$shippingAmount;
    }

    /**
     * @return string
     */
    public function getShippingCurrencyCode()
    {
        return $this->shippingCurrencyCode;
    }

    /**
     * @param string $shippingCurrencyCode
     */
    public function setShippingCurrencyCode($shippingCurrencyCode)
    {
        $this->shippingCurrencyCode = $shippingCurrencyCode;
    }

    /**
     * @return string
     */
    public function getCultureCode()
    {
        return $this->cultureCode;
    }

    /**
     * @param string $cultureCode
     */
    public function setCultureCode($cultureCode)
    {
        $this->cultureCode = $cultureCode;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return int
     */
    public function getReviewCount()
    {
        return $this->reviewCount;
    }

    /**
     * @param int $reviewCount
     */
    public function setReviewCount($reviewCount)
    {
        $this->reviewCount = $reviewCount;
    }

    /**
     * @return int
     */
    public function getRatingCount()
    {
        return $this->ratingCount;
    }

    /**
     * @param int $ratingCount
     */
    public function setRatingCount($ratingCount)
    {
        $this->ratingCount = $ratingCount;
    }

    /**
     * @return float
     */
    public function getAvgRating()
    {
        return $this->avgRating;
    }

    /**
     * @param float $avgRating
     */
    public function setAvgRating($avgRating)
    {
        $this->avgRating = $avgRating;
    }

    /**
     * @return string
     */
    public function getDiscountType()
    {
        return $this->discountType;
    }

    /**
     * @param string $discountType
     */
    public function setDiscountType($discountType)
    {
        $this->discountType = $discountType;
    }

    /**
     * @return string
     */
    public function getDiscountAmount()
    {
        return $this->discountAmount;
    }

    /**
     * @param string $discountAmount
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * @return string
     */
    public function getPriceWithCurrencySymbol()
    {
        return $this->priceWithCurrencySymbol;
    }

    /**
     * @param string $priceWithCurrencySymbol
     */
    public function setPriceWithCurrencySymbol($priceWithCurrencySymbol)
    {
        $this->priceWithCurrencySymbol = $priceWithCurrencySymbol;
    }

    /**
     * Set default values for all the product properties
     * @return void
     */
    public function setDefaultProductProperties()
    {
        $this->setBigImageUrl(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setBrandName(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setCode(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setDescription(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setExternalProductId(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setIsFreeDelivery(self::PRODUCT_BOOL_PROPERTY_DEFAULT_VALUE);
        $this->setIsInStock(self::PRODUCT_BOOL_PROPERTY_DEFAULT_VALUE);
        $this->setIsOnSale(self::PRODUCT_BOOL_PROPERTY_DEFAULT_VALUE);
        $this->setName(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setPriceValue(self::PRODUCT_FLOAT_PROPERTY_DEFAULT_VALUE);
        $this->setShippingInfo(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setSmallImageUrl(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setStockQuantity(self::PRODUCT_INT_PROPERTY_DEFAULT_VALUE);
        $this->setUrl(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setIsBlacklisted(self::PRODUCT_BOOL_PROPERTY_DEFAULT_VALUE);
        $this->setIsPromoted(self::PRODUCT_BOOL_PROPERTY_DEFAULT_VALUE);
        $this->setShippingAmount(self::PRODUCT_FLOAT_PROPERTY_DEFAULT_VALUE);
        $this->setShippingCurrencyCode(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setCultureCode(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setCurrencyCode(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setCategories(array());
        $this->setReviewCount(self::PRODUCT_INT_PROPERTY_DEFAULT_VALUE);
        $this->setRatingCount(self::PRODUCT_INT_PROPERTY_DEFAULT_VALUE);
        $this->setAvgRating(self::PRODUCT_FLOAT_PROPERTY_DEFAULT_VALUE);
        $this->setDiscountType(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setDiscountAmount(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
        $this->setPriceWithCurrencySymbol(self::PRODUCT_STRING_PROPERTY_DEFAULT_VALUE);
    }

    /**
     * Remove unnecessary properties of the ProductModel
     *
     * @param string $property
     */
    public function removeProperty($property)
    {
        unset($this->$property);
    }

    /**
     * Serialize data to JSON
     * @return mixed data
     */
    public function jsonSerialize()
    {
        return (object)get_object_vars($this);
    }
}
