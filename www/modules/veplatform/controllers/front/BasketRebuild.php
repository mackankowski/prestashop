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

class VePlatformBasketRebuildModuleFrontController extends ModuleFrontController
{
    /**
     * @var VeLogger $logger
     */
    protected $logger;

    /**
     * @var PrestashopBasketBuilder $prestashopBasketBuilder
     */
    protected $prestashopBasketBuilder;

    /**
      * @var PrestashopContext $prestashopContext
      */
    protected $prestashopContext;
    
    /**
      * @var PrestashopProductFactory $prestashopProductFactory
      */
    protected $prestashopProductFactory;

    public function __construct()
    {
        parent::__construct();
        $this->logger = new VeLogger();
        $this->prestashopContext = new PrestashopContext();
        $this->prestashopProductFactory = new PrestashopProductFactory();
        $this->prestashopBasketBuilder = new PrestashopBasketBuilder($this->logger, $this->prestashopProductFactory, $this->prestashopContext);
    }

    /**
     * Initializes front page content for basketRebuild
     */
    public function initContent()
    {
        parent::initContent();

        try {
            //Get productIdentifierQuantity param if exists
            $pidq = Tools::getValue('pidq');
            if (isset($pidq)) {
                $response = $this->prestashopBasketBuilder->rebuildBasket($pidq);
                if ($response) {
                    if (VePlatform::isPrestashopVersion('1.7')) {
                        Tools::redirect('index.php?controller=cart?action=show');
                    }

                    Tools::redirect('index.php?controller=order');
                }
            }
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }

        Tools::redirect('index.php');
    }
}
