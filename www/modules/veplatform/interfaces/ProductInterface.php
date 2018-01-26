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

interface ProductInterface
{
    public function setBigImageUrl($bigImageUrl);

    public function getBigImageUrl();

    public function setBrandName($brandName);

    public function getBrandName();

    public function setCode($code);

    public function getCode();

    public function setDescription($description);

    public function getDescription();

    public function setExternalProductId($externalProductId);

    public function getExternalProductId();

    public function setIsFreeDelivery($isFreeDelivery);

    public function getIsFreeDelivery();

    public function setIsInStock($isInStock);

    public function getIsInStock();

    public function setIsOnSale($isOnSale);

    public function getIsOnSale();

    public function setName($name);

    public function getName();

    public function setPriceValue($priceValue);

    public function getPriceValue();

    public function setShippingInfo($shippingInfo);

    public function getShippingInfo();

    public function setSmallImageUrl($smallImageUrl);

    public function getSmallImageUrl();

    public function setStockQuantity($stockQuantity);

    public function getStockQuantity();

    public function setUrl($url);

    public function getUrl();

    public function setIsBlacklisted($isBlacklisted);

    public function getIsBlacklisted();

    public function setIsPromoted($isPromoted);

    public function getIsPromoted();

    public function setShippingAmount($shippingAmount);

    public function getShippingAmount();

    public function setShippingCurrencyCode($shippingCurrencyCode);

    public function getShippingCurrencyCode();

    public function setCultureCode($cultureCode);

    public function getCultureCode();

    public function setCurrencyCode($currencyCode);

    public function getCurrencyCode();

    public function setCategories($categories);

    public function getCategories();

    public function setReviewCount($reviewCount);

    public function getReviewCount();

    public function setRatingCount($ratingCount);

    public function getRatingCount();

    public function setAvgRating($avgRating);

    public function getAvgRating();

    public function setDiscountType($discountType);

    public function getDiscountType();

    public function setDiscountAmount($discountAmount);

    public function getDiscountAmount();

    public function setDefaultProductProperties();

    public function removeProperty($property);
}
