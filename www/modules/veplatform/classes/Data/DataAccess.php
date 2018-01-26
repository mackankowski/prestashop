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

class DataAccess implements DataAccessInterface
{
    const PRODUCT_SYNC_TABLE = 've_products_sync';
    const PRODUCT_SYNC_TYPE_INITIAL = 'InitialProductSync';
    const PRODUCT_SYNC_TYPE_CONTINUOUS = 'ContinuousProductSync';
    const PRODUCT_TABLE = 'product';
    const PRODUCT_ATTRIBUTE_TABLE = 'product_attribute';

    /**
     * @var ProductCore $productCore
     */
    private $productCore;

    /**
     * @var VeLogger $logger
     */
    private $logger;

    /**
     * @var ContextCore $context
     */
    private $context;

    public function __construct($logger, $context, $productCore = null)
    {
        $this->logger = $logger;
        $this->context = $context;
        $this->productCore = $productCore;
    }

    /**
     * Get all available products from db
     *
     * @param int    $languageId Language id
     * @param int    $start      Start number
     * @param int    $limit      Number of products to return
     * @param string $orderBy    Field for ordering (eg. 'id_product')
     * @param string $orderWay   Way for ordering (ASC or DESC)
     *
     * @return array Products details
     */
    public function getAllProducts($languageId, $start, $limit, $orderBy, $orderWay)
    {
        $products = array();
        try {
            $timePre = microtime(true);
            if (isset($this->productCore)) {
                $context = !is_object($this->context->controller) ?
                    $this->setDefaultControllerTypeToContextController($this->context) : $this->context;

                $products = $this->productCore->getProducts(
                    $languageId,
                    $start,
                    $limit,
                    $orderBy,
                    $orderWay,
                    false,
                    false,
                    $context
                );
            }
            $timePost = microtime(true);

            $this->logger->sendModuleDbMetrics(self::PRODUCT_SYNC_TYPE_INITIAL, count($products), $timePost - $timePre);
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        return $products;
    }

    /**
     * Set default value for the controller type property of the context controller object
     *
     * @param ContextCore $context
     *
     * @return ContextCore
     */
    private function setDefaultControllerTypeToContextController($context)
    {
        //need to initialize the object so we can add a default value to it
        $context->controller = new \stdClass();
        $context->controller->controller_type = array();

        return $context;
    }

    /**
     * Create ve_products_sync table
     *
     * @return bool
     */
    public function createProductSyncTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::PRODUCT_SYNC_TABLE . '` (
			`id_product` int(10) NOT NULL, `update_time` datetime, `url_product` varchar(255),
			`name_product` varchar(100),
			PRIMARY KEY (`id_product`)
			) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        return Db::getInstance()->execute($sql);
    }

    /**
     * Delete ve_products_sync table
     *
     * @return bool
     */
    public function deleteProductSyncTable()
    {
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . self::PRODUCT_SYNC_TABLE;

        return Db::getInstance()->execute($sql);
    }

    /**
     * Add product id to be sync in ve_product_sync table
     *
     * @param int    $productId
     *
     * @param string $productUrl
     *
     * @param string $productName
     *
     * @return bool
     */
    public function addProductToSync($productId, $productUrl = null, $productName = null)
    {
        $productAddedByADeleteEvent = isset($productUrl) && isset($productName);
        if ($this->checkIfProductExists($productId)) {
            return $this->updateProductInformationInSyncTable(
                $productId,
                $productAddedByADeleteEvent,
                $productUrl,
                $productName
            );
        }

        if ($productAddedByADeleteEvent) {
            $sql = 'INSERT INTO ' . _DB_PREFIX_ . self::PRODUCT_SYNC_TABLE .
                '(`id_product`, `update_time`, `url_product`, `name_product`) VALUES (' .
                (int)$productId . ', UTC_TIMESTAMP(), "' . $productUrl . '", "' . $productName . '")';
        } else {
            $sql = 'INSERT INTO ' . _DB_PREFIX_ . self::PRODUCT_SYNC_TABLE . '(`id_product`, `update_time`) VALUES (' .
                (int)$productId . ', UTC_TIMESTAMP() )';
        }

        return Db::getInstance()->execute($sql);
    }

    /**
     * Update product information if its id already exists in the ve_product_sync table
     *
     * @param int    $productId
     *
     * @param bool   $productUpdatedByADeleteEvent
     *
     * @param string $productUrl
     *
     * @param string $productName
     *
     * @return bool
     */
    private function updateProductInformationInSyncTable(
        $productId,
        $productUpdatedByADeleteEvent,
        $productUrl,
        $productName
    ) {
        if ($productUpdatedByADeleteEvent) {
            $sql = 'UPDATE ' . _DB_PREFIX_ . self::PRODUCT_SYNC_TABLE .
                ' SET url_product="' . $productUrl . '", name_product="' . $productName .
                '" WHERE id_product=' . $productId;
        } else {
            $sql = 'UPDATE ' . _DB_PREFIX_ . self::PRODUCT_SYNC_TABLE .
                ' SET update_time=UTC_TIMESTAMP() ' . ' WHERE id_product=' . $productId;
        }

        return Db::getInstance()->execute($sql);
    }

    /**
     * Get all product ids saved in ve_products_sync table
     *
     * @param int $batchSize
     *
     * @return bool
     */
    public function deleteAllProductsToSync($batchSize)
    {
        $sql = 'DELETE FROM ' . _DB_PREFIX_ . self::PRODUCT_SYNC_TABLE . ' LIMIT ' . (int)$batchSize;

        return Db::getInstance()->execute($sql);
    }

    /**
     * Delete a specific product from de ve_products_sync table
     *
     * @param int $productId
     *
     * @return bool
     */
    public function deleteProductToSync($productId)
    {
        $sql = 'DELETE FROM ' . _DB_PREFIX_ . self::PRODUCT_SYNC_TABLE . ' WHERE `id_product` = ' . (int)$productId;
        return Db::getInstance()->execute($sql);
    }

    /**
     * Get all updated products waiting to be synced
     *
     * @param int $batchSize
     *
     * @return array idProducts
     */
    public function getAllProductsToSync($batchSize)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::PRODUCT_SYNC_TABLE . ' LIMIT ' . (int)$batchSize;

        $timePre = microtime(true);
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $timePost = microtime(true);

        $this->logger->sendModuleDbMetrics(self::PRODUCT_SYNC_TYPE_CONTINUOUS, count($products), $timePost - $timePre);

        return $products;
    }

    /**
     * Check if there exists another product with a specific productId
     *
     * @param int $productId
     *
     * @return bool
     */
    private function checkIfProductExists($productId)
    {
        $sql = 'SELECT COUNT(id_product) FROM ' . _DB_PREFIX_ . self::PRODUCT_SYNC_TABLE .
            ' WHERE id_product = ' . $productId;

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $result = $result[0]['COUNT(id_product)'] != 0 ? true : false;

        return $result;
    }

    /**
     * Update configuration key and value into database (automatically insert if key does not exist)
     *
     * @param string $key
     * @param mixed  $values $values is an array if the configuration is multilingual, a single string else.
     *
     * @return bool
     */
    public function updateConfigValue($key, $value)
    {
        if (!isset($key) || !isset($value)) {
            return false;
        }
        return Configuration::updateValue($key, $value);
    }

    /**
     * Delete a configuration key from database
     *
     * @param string $key
     *
     * @return bool
     */
    public function deleteConfigByKey($key)
    {
        if (!isset($key)) {
            return false;
        }
        return Configuration::deleteByName($key);
    }

    /**
     * Get several configuration values from database
     *
     * @param array $keys
     *
     * @return bool
     */
    public function getMultipleConfig($keys)
    {
        if (!is_array($keys) || empty($keys)) {
            return false;
        }
        return Configuration::getMultiple($keys);
    }
}
