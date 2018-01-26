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

class VeData
{
    /**
     * @var ContextCore $context
     */
    protected $context;

    /**
     * @var VeLogger $logger
     */
    protected $logger;

    /**
     * @var VePlatformAPI $veApi
     */
    protected $veApi;

    /**
     * @var PrestashopProductFactory $prestashopProductFactory
     */
    protected $prestashopProductFactory;

    /**
     * @var PrestashopCategoryFactory $prestashopCategoryFactory
     */
    protected $prestashopCategoryFactory;

    //maximum char number for rebuildUrl
    const URL_MAX_LIMIT = 1500;

    public function __construct($logger, $veApi)
    {
        $this->context = Context::getContext();
        $this->logger = $logger;
        $this->veApi = $veApi;
        $this->prestashopProductFactory = $veApi->prestashopProductFactory;
        $this->prestashopCategoryFactory = $veApi->prestashopCategoryFactory;
    }

    /**
     * Get user's email, firstName and lastName
     * @return array
     */
    public function getUser()
    {
        $email = null;
        $firstname = null;
        $lastname = null;

        try {
            $customer = $this->context->customer;
            $cookie = $this->context->cookie;

            if (isset($customer) && isset($cookie)) {
                if ($customer->logged) {
                    $email = $customer->email;
                    $firstname = $customer->firstname;
                    $lastname = $customer->lastname;
                } elseif (isset($cookie->email)) {
                    $email = $cookie->email;
                } elseif (isset($cookie->customer_firstname)) {
                    $firstname = $cookie->customer_firstname;
                } elseif (isset($cookie->customer_lastname)) {
                    $lastname = $cookie->customer_lastname;
                }
            }
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        return array(
            'email' => $email,
            'firstName' => $firstname,
            'lastName' => $lastname
        );
    }

    /**
     * Add viewed product to cookie(viewedProducts)
     *
     * @param int $productId
     *
     * @return void
     */
    private function addViewedProduct($productId)
    {
        if (isset($this->context->cookie->viewed)) {
            return;
        }

        $viewedProducts = array();
        if (isset($this->context->cookie->viewedProducts)) {
            $viewedProducts = explode(',', $this->context->cookie->viewedProducts);
        }
        if (!in_array($productId, $viewedProducts)) {
            $viewedProducts[] = $productId;
            $this->context->cookie->viewedProducts = trim(implode(',', $viewedProducts), ',');
        }
    }

    /**
     * Add categoryId in cookie
     *
     * @param Product $product
     *
     * @return void
     */
    private function addLastVisitedCategory($categoryId)
    {
        if (!isset($categoryId)) {
            return;
        }

        $this->context->cookie->lastVisitedCategory = $categoryId;
    }

    /**
     * Get Product History with all product information
     *
     * @param string $viewedProducts
     *
     * @return array
     */
    public function getProductHistory($viewedProducts)
    {
        $productHistory = array();
        if (!isset($viewedProducts) || empty($viewedProducts)) {
            return $productHistory;
        }

        $productList = array_values(array_unique(explode(',', $viewedProducts)));
        foreach ($productList as $productId) {
            $productHistory[] = $this->getProductInformation($productId);
        }

        return $productHistory;
    }

    /**
     * Get History - lastVisitedCategory and productHistory
     * @return array
     */
    public function getHistory()
    {
        $history = array();
        $lastVisitedCategory = array(
            'name' => null,
            'link' => null
        );

        try {
            //Get the lastVisitedCategory from cookie
            if (isset($this->context->cookie->lastVisitedCategory) && !empty($this->context->cookie->lastVisitedCategory)) {
                $lastVisitedCategory = $this->getCategoryInfo($this->context->cookie->lastVisitedCategory);
            }

            $history['lastVisitedCategory'] = $lastVisitedCategory;

            $productHistory = array();
            //for PrestaShop 1.7 we have to set our own cookie: viewedProducts to get the productHistory
            if ($this->context->cookie->viewed && !empty($this->context->cookie->viewed)) {
                $productHistory = $this->getProductHistory($this->context->cookie->viewed);
            } elseif ($this->context->cookie->viewedProducts && !empty($this->context->cookie->viewedProducts)) {
                $productHistory = $this->getProductHistory($this->context->cookie->viewedProducts);
            }

            $history['productHistory'] = $productHistory;
            return $history;
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        // something went wrong, return a default history object
        return array(
            'lastVisitedCategory' => $lastVisitedCategory,
            'productHistory' => array()
        );
    }

    /**
     * Get current page information - currentUrl, pageType, orderId if it's complete page or product information if is
     * product page
     * @return array
     */
    public function getCurrentPage()
    {
        try {
            //TODO: refactor this function to be easier to read
            $currentPage = array(
                'currentUrl' => (defined('_PS_BASE_URL_SSL_') && isset($_SERVER['REQUEST_URI'])) ?
                    _PS_BASE_URL_SSL_ . $_SERVER['REQUEST_URI'] : null,
                'currentPageType' => $this->getCurrentPageType(),
                'orderId' => isset($this->context->controller->reference) ?
                    $this->context->controller->reference : (isset($this->context->controller->id_order) ?
                        $this->context->controller->id_order : null),
                'product' => $this->context->controller->php_self == 'product' ?
                    $this->getProductInformation(Tools::getValue('id_product')) : $this->getDefaultProduct(),
            );

            //Set productId in cookie for the productHistory and categoryId for the lastVisitedCategory
            $languageId = $this->context->language->id;
            if ($currentPage['currentPageType'] == 'product') {
                $productId = Tools::getValue('id_product');
                if (isset($productId) && !empty($productId)) {
                    $this->addViewedProduct($productId);

                    $product = $this->prestashopProductFactory->createProduct(
                        $productId,
                        $languageId,
                        $this->context->shop->id_shop_group
                    );
                    $this->addLastVisitedCategory($product->id_category_default);
                }
            }

            //Set categoryId in cookie for the lastVisitedCategory
            if ($currentPage['currentPageType'] == 'category') {
                $categoryId = Tools::getValue('id_category');
                $this->addLastVisitedCategory($categoryId);
            }

            return $currentPage;
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        // something went wrong, return a default page
        return array(
            'currentUrl' => null,
            'currentPageType' => null,
            'orderId' => null,
            'product' => null,
        );
    }

    /**
     * Get current page type - home, register, login, product, category, basket, checkout, complete or other
     * @return string
     */
    private function getCurrentPageType()
    {
        try {
            $controllerName = Tools::getValue('controller');

            $type = null;
            $step = null;
            switch ($controllerName) {
                case 'index':
                    $type = 'home';
                    break;
                case 'authentication':
                    $isRegisterPage = Tools::getValue('create_account');
                    $type = isset($isRegisterPage) && !empty($isRegisterPage) ? 'register' : 'login';
                    break;
                case 'product':
                    $type = 'product';
                    break;
                case 'order':
                    $type = 'checkout';
                    if (isset($this->context->controller->step)) {
                        $step = $this->context->controller->step;
                        if ($step > 0 && $step == 3) {
                            $type = 'payment';
                        }
                    }
                    break;
                case 'orderopc':
                    $type = 'basket';
                    break;
                case 'cart':
                    $type = 'basket';
                    break;
                case 'category':
                    $type = 'category';
                    break;
                case 'orderconfirmation':
                    $type = 'complete';
                    break;
                default:
                    $type = 'other';
            }
            return $type;
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        // something went wrong, return 'other'
        return 'other';
    }

    /**
     * Get cart information
     * @return array
     */
    public function getCart()
    {
        try {
            if ($this->getCurrentPageType() == 'complete') {
                return null;
            }

            $cartInfo = $this->context->cart->getSummaryDetails();
            if (!isset($cartInfo)) {
                $cartInfo = array();
                $cartInfo['products'] = null;
            }

            $products = array();
            $taxes = array();
            $i = 0;
            if (!isset($cartInfo['products']) || (count($cartInfo['products']) == 0)) {
                // nothing in cart return a default null cart
                return self::getDefaultCart();
            }

            $giftProducts = array();
            $hasGifts = false;

            if (isset($cartInfo['gift_products'])) {
                $hasGifts = true;
                $giftProducts = $cartInfo['gift_products'];
            }

            foreach ($cartInfo['products'] as $prod) {
                $products[$i] = $this->getProductInformation($prod['id_product']);
                $products[$i]['productAttributeId'] = $prod['id_product_attribute'];
                //check if product is gift
                if ($hasGifts) {
                    foreach ($giftProducts as $giftProd) {
                        if ($prod['id_product'] == $giftProd['id_product']) {
                            $prod['cart_quantity'] += $giftProd['cart_quantity'];
                            if (($key = array_search($giftProd, $giftProducts)) !== false) {
                                unset($giftProducts[$key]);
                            }
                        }
                    }
                }

                $products[$i]['quantity'] = $prod['cart_quantity'];

                $productPrice = (float)Product::getPriceStatic($prod['id_product'], true, null, 2);

                $products[$i]['productSubTotal'] = ToolsCore::displayPrice($productPrice * (int)$prod['cart_quantity']);

                if (array_search($prod['tax_name'], array_column($taxes, 'name')) != 'name') {
                    $tax = array(
                        'name' => $prod['tax_name'],
                        'taxValue' => ToolsCore::displayPrice((float)$cartInfo['total_tax'])
                    );
                    $taxes[] = $tax;
                }
                ++$i;
            }

            foreach ($giftProducts as $giftProd) {
                $products[$i] = $this->getProductInformation($giftProd['id_product']);
                $products[$i]['productAttributeId'] = $giftProd['id_product_attribute'];
                $products[$i]['quantity'] = $giftProd['cart_quantity'];
                $productPrice = (float)Product::getPriceStatic($giftProd['id_product'], true, null, 2);

                $products[$i]['productSubTotal'] = ToolsCore::displayPrice($productPrice * (int)$giftProd['cart_quantity']);

                if (array_search($giftProd['tax_name'], array_column($taxes, 'name')) != 'name') {
                    $tax = array(
                        'name' => $giftProd['tax_name'],
                        'taxValue' => ToolsCore::displayPrice((float)$cartInfo['total_tax'])
                    );
                    $taxes[] = $tax;
                }
                ++$i;
            }

            $rebuildUrl = $this->getRebuildUrl($products);
            //productAttributeId array element is only needed for the rebuildUrl, so we remove it after usage
            $this->removeProductAttributeIdProperty($products);

            $cart = array(
                'dateUpd' => date('D M d Y H:i:s O', strtotime($this->context->cart->date_upd)),
                'promocode' => $this->getPromoCode($cartInfo),
                'totalPromocodeDiscount' => ToolsCore::displayPrice((float)$cartInfo['total_discounts']),
                'totalPrice' => ToolsCore::displayPrice((float)$cartInfo['total_price']),
                'totalProducts' => ToolsCore::displayPrice((float)$cartInfo['total_products_wt'] - $cartInfo['total_discounts']),
                'products' => $products,
                'taxes' => $taxes,
                'rebuildUrl' => $rebuildUrl ? $rebuildUrl : null
            );
            return $cart;
        } catch (Exception $e) {
            $this->logger->logException($e);
        }

        // there was an exception, return a default cart
        return self::getDefaultCart();
    }

    /**
     * Remove productAttributeId from each product
     *
     * @param $products array
     *
     * @return void
     */
    private function removeProductAttributeIdProperty(&$products)
    {
        if (isset($products) || !empty($products)) {
            foreach ($products as & $product) {
                unset($product['productAttributeId']);
            }
        }
    }

    /**
     * Get basket rebuild url
     * @return string
     */
    public function getRebuildUrl($products)
    {
        $basketRebuildActive = $this->veApi->getConfigOption('basketRebuildActive');
        //check basketRebuild feature is active
        if (!$basketRebuildActive || empty($products)) {
            return false;
        }

        $pidq = '';
        foreach ($products as $product) {
            if (Tools::strlen($pidq) < self::URL_MAX_LIMIT && isset($product['productId'])) {
                $productId = $product['productId'];
                $quantity = $product['quantity'];
                $productAttributeId = isset($product['productAttributeId']) ? $product['productAttributeId'] : null;

                $pidq = $pidq . $productId . ',' . $quantity;
                if (isset($productAttributeId)) {
                    $pidq = $pidq . ',' . $productAttributeId . ';';
                } else {
                    $pidq = $pidq . ';';
                }
            } else {
                break;
            }
        }

        //remove last ; from productIdQuantity param
        $pidq = Tools::substr($pidq, 0, -1);
        //generate a link for the frontController basketRebuild with the pidq param
        $pidq = $this->context->link->getModuleLink('veplatform', 'BasketRebuild', array('pidq' => $pidq));

        return $pidq;
    }

    /**
     * Get default cart with its default properties
     * @return array
     */
    public static function getDefaultCart()
    {
        return array(
            'dateUpd' => null,
            'promocode' => array(
                'name' => null,
                'code' => null,
                'type' => null,
                'value' => null
            ),
            'totalPromocodeDiscount' => null,
            'totalPrice' => null,
            'totalProducts' => null,
            'products' => array(),
            'taxes' => array(),
            'rebuildUrl' => null
        );
    }

    /**
     * Get promocode - name, code, type and value
     * @return array
     */
    private function getPromoCode($cartInfo)
    {
        $promocode = array();
        try {
            if (isset($cartInfo) && count($cartInfo['discounts']) != 0) {
                foreach ($cartInfo['discounts'] as $discount) {
                    if (!empty($discount['code'])) {
                        $promocode['name'] = $discount['name'];
                        $promocode['code'] = $discount['code'];
                        $promocode['type'] = $discount['reduction_percent'] != 0 ? 'percentage' : 'fixed';
                        $promocode['value'] = $discount['reduction_percent'] != 0 ? $discount['reduction_percent'] :
                            ToolsCore::displayPrice($discount['value_real']);
                        break;
                    }
                }
            }
            if (!isset($promocode) || count($promocode) == 0) {
                $promocode = array(
                    'name' => null,
                    'code' => null,
                    'type' => null,
                    'value' => null
                );
            }
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        return $promocode;
    }

    /**
     * Get currency - name, isoCode, isoCodeNum and sign
     * @return array
     */
    public function getCurrency()
    {
        try {
            $currency = array(
                'name' => $this->context->currency->name,
                'isoCode' => $this->context->currency->iso_code,
                'isoCodeNum' => $this->context->currency->iso_code_num,
                'sign' => $this->context->currency->sign
            );

            return $currency;
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }
        // something went wrong, returning a default currency
        return array(
            'name' => null,
            'isoCode' => null,
            'isoCodeNum' => null,
            'sign' => null,
        );
    }

    /**
     * Get language - name, isoCode, languageCode
     * @return array
     */
    public function getLanguage()
    {
        try {
            $language = array(
                'name' => $this->context->language->name,
                'isoCode' => $this->context->language->iso_code,
                'languageCode' => $this->context->language->language_code
            );

            return $language;
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        // something went wrong, returning a default language
        return array(
            'name' => null,
            'isoCode' => null,
            'languageCode' => null
        );
    }

    /**
     * Get culture information - dateFormatFull
     * @return array
     */
    public function getCulture()
    {
        try {
            $culture = array(
                'dateFormatFull' => $this->context->language->date_format_full
            );
            return $culture;
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        // something went wrong, returning a default culture
        return array(
            'dateFormatFull' => null
        );
    }

    /**
     * Get default product and its properties
     * @return array
     */
    private function getDefaultProduct()
    {
        $product = array(
            'description' => null,
            'descriptionShort' => null,
            'category' => null,
            'name' => null,
            'priceCurrent' => null,
            'priceDiscount' => null,
            'priceWithoutDiscount' => null,
            'productLink' => null,
            'productId' => null,
            'manufacturerName' => null
        );
        $product['images']['partialImagePath'] = null;
        $product['images']['fullImagePath'] = null;

        return $product;
    }

    /**
     * Get default categoryInfo
     * @return array
     */
    private function getDefaultCategoryInfo()
    {
        return array(
            'name' => null,
            'link' => null
        );
    }
    

    /**
     * Get product information by the productId
     *
     * @param $id
     * @param $obj
     *
     * @return array
     */
    private function getProductInformation($id = null, $obj = null)
    {
        try {
            if (!isset($obj)) {
                $prod = $this->prestashopProductFactory->createProduct(
                    $id,
                    $this->context->language->id,
                    $this->context->shop->id_shop_group
                );
            } else {
                $prod = $obj;
            }

            $manufacturer = new Manufacturer();
            $manName = $manufacturer->getNameById($prod->id_manufacturer);
            $manufacturerName = isset($manName) ? $manName : null;
            $category = $this->prestashopCategoryFactory->createCategory(
                $prod->id_category_default,
                $this->context->language->id
            );

            $product = array(
                'description' => $this->cleanStr($prod->description),
                'descriptionShort' => $this->cleanStr($prod->description_short),
                'category' => isset($category) ? $this->cleanStr($category->name) : null,
                'name' => $this->cleanStr($prod->name),
                'priceCurrent' => ToolsCore::displayPrice((float)$prod->getPrice(true, null, 6)),
                'priceDiscount' => ToolsCore::displayPrice((float)$prod->getPrice(true, null, 6, null, true)),
                'priceWithoutDiscount' => ToolsCore::displayPrice((float)$prod->getPrice(true, null, 6, null, false, false)),
                'productLink' => $prod->getLink(),
                'productId' => isset($prod->id) || !empty($prod->id) ? $prod->id : null,
                'manufacturerName' => $this->cleanStr($manufacturerName)
            );

            //TODO: use Product::getCover() instead of instantiate Image class
            $image = new Image();
            $cover = $image->getCover($id);

            $product['images']['partialImagePath'] = null;
            $product['images']['fullImagePath'] = $this->context->link->getImageLink($prod->link_rewrite[1], $id . '-' .
                $cover['id_image']);

            return $product;
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }
        // if an error occured, return the default product
        return $this->getDefaultProduct();
    }

    /**
     * Get category information by the categoryId and languageId
     *
     * @param $id
     * @param $languageId
     *
     * @return array
     */
    public function getCategoryInfo($id = null, $languageId = null)
    {
        try {
            $id = isset($id) ? $id : $this->context->cookie->last_visited_category;
            $languageId = isset($languageId) ? $languageId : $this->context->language->id;
            $category = $this->prestashopCategoryFactory->createCategory($id, $languageId);

            if (!isset($category)) {
                return $this->getDefaultCategoryInfo();
            }

            $link = $this->context->link->getCategoryLink($category->id_category, $category->link_rewrite);

            return array(
                'name' => $this->cleanStr($category->name),
                'link' => $link
            );
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        // if an error occurred, return the default categoryInfo
        return $this->getDefaultCategoryInfo();
    }

    /**
     * Clean string
     *
     * @param $str
     *
     * @return string
     */
    public function cleanStr($str)
    {
        if ($str == null) {
            return null;
        }
        $str = strip_tags($str);
        $str = html_entity_decode($str);

        return $str;
    }
}
