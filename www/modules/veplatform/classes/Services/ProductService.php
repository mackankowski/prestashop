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

class ProductService implements ProductServiceInterface
{

    /**
     * @var DataAccessInterface $dataAccess
     */
    private $dataAccess;

    /**
     * @var ProductPrestaShopServiceInterface $productPrestashopService
     */
    private $productPrestashopService;

    /**
     * @var ProductMapperInterface $productMapper
     */
    private $productMapper;

    /**
     * @var ProductModel $productModel
     */
    private $productModel;

    /**
     * @var VeLogger $logger
     */
    private $logger;

    /**
     * @var ProductDecoratorInterface $productDecorator
     */
    private $productDecorator;

    /**
     * @var ContextCore $context
     */
    private $context;

    /**
     * @var int $languageId
     */
    private $languageId;

    public function __construct(
        $dataAccess,
        $productPrestashopService,
        $productMapper,
        $productModel,
        $productDecorator,
        $logger
    ) {
        $this->dataAccess = $dataAccess;
        $this->productPrestashopService = $productPrestashopService;
        $this->productMapper = $productMapper;
        $this->productModel = $productModel;
        $this->productDecorator = $productDecorator;
        $this->logger = $logger;
        $this->context = Context::getContext();
        $this->languageId = $this->context->language->id;
    }

    /**
     * Get all products
     *
     * @param int $startingProduct the index of the first product to be processed
     * @param int $batchSize       number of products to be sent at once
     *
     * @return array Products details
     */
    public function getProducts($startingProduct = 0, $batchSize = 0)
    {
        $allProducts = array();

        try {
            $products = $this->dataAccess->getAllProducts(
                $this->languageId,
                $startingProduct,
                $batchSize,
                'id_product',
                'DESC'
            );

            $timePre = microtime(true);
            $finalTimeMapping = 0;
            $finalTimeDecoration = 0;
            foreach ($products as $product) {
                $this->productModel->setDefaultProductProperties();
                $timePreMapping = microtime(true);
                $mappedProduct = $this->productMapper->mapProduct($product, $this->productModel);
                $timePostMapping = microtime(true);
                $finalTimeMapping += $timePostMapping - $timePreMapping;

                $timePreDecoration = microtime(true);
                $mappedProduct = $this->productDecorator->decorateProduct($mappedProduct);
                $timePostDecoration = microtime(true);
                $finalTimeDecoration += $timePostDecoration - $timePreDecoration;

                $mappedProduct = $mappedProduct->jsonSerialize();
                $allProducts[] = $mappedProduct;
            }

            $timePost = microtime(true);
            $execTimeMappingAndDecorationProducts = $timePost - $timePre;

            $this->logger->trackMetric(
                'InitialProductSync.Module.ProductMapping.Duration',
                $finalTimeMapping
            );

            $this->logger->trackMetric(
                'InitialProductSync.Module.ProductDecoration.Duration',
                $finalTimeDecoration
            );

            $this->logger->trackMetric(
                'InitialProductSync.Module.ProductMappingAndDecoration.Duration',
                $execTimeMappingAndDecorationProducts
            );
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        return $allProducts;
    }

    /**
     * Get updated products
     *
     * @param int $batchSize
     *
     * @return array Products details
     */
    public function getUpdatedProducts($batchSize)
    {
        $allProducts = array();

        try {
            $products = $this->dataAccess->getAllProductsToSync($batchSize);

            foreach ($products as $product) {
                if (!isset($product['id_product']) || empty($product['id_product'])) {
                    $this->logger->logException(
                        new Exception('ExternalProductId was not set for this product or is empty ' .
                            print_r($product, true))
                    );
                    continue;
                }

                $newProduct = new Product($product['id_product'], null, $this->languageId);

                //the product models are not the same, the one from db and the ProductCore one
                //we have to set some properties by ourselves to have them the same
                $newProduct->id_product = $product['id_product'];
                $newProduct->rate = Tax::getProductTaxRate($newProduct->id_product);

                $this->productModel->setDefaultProductProperties();
                $this->productModel->setIsBlacklisted(false);

                if (isset($product['name_product']) && isset($product['url_product'])) {
                    //mark product as deleted
                    $this->productModel->setIsBlacklisted(true);
                    $newProduct->name = $product['name_product'];
                    $this->productModel->setUrl($product['url_product']);
                }

                $mappedProduct = $this->productMapper->mapProduct((array)$newProduct, $this->productModel);
                $mappedProduct = $this->productDecorator->decorateProduct($mappedProduct);

                if (!isset($mappedProduct)) {
                    continue;
                }
                $mappedProduct = $mappedProduct->jsonSerialize();
                $allProducts[] = $mappedProduct;
            }

            $this->dataAccess->deleteAllProductsToSync($batchSize);
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        return $allProducts;
    }
}
