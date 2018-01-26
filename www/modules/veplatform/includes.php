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

if (!defined('_PS_VERSION_')) {
    exit;
}

$pathVendorAutoload = dirname(__FILE__) . '/vendor/autoload.php';
$pathVePlatform = dirname(__FILE__) . '/veplatform.php';
$pathFileSystemHelperInterface = dirname(__FILE__) . '/interfaces/FileSystemHelperInterface.php';
$pathFileSystemHelper = dirname(__FILE__) . '/classes/Helpers/FileSystemHelper.php';
$pathECommerceProductInterface = dirname(__FILE__) . '/interfaces/ECommerceProductFactoryInterface.php';
$pathECommerceCategoryInterface = dirname(__FILE__) . '/interfaces/ECommerceCategoryFactoryInterface.php';
$pathPrestashopProduct = dirname(__FILE__) . '/classes/Models/PrestashopProductFactory.php';
$pathPrestashopCategory = dirname(__FILE__) . '/classes/Models/PrestashopCategoryFactory.php';

$pathUpgradeHandler = dirname(__FILE__) . '/upgrade/UpgradeHandler.php';
$pathVeData = dirname(__FILE__) . '/classes/VeData.php';
$pathLogger = dirname(__FILE__) . '/classes/VeLogger.php';
$pathVeApi = dirname(__FILE__) . '/classes/AbstractVePlatformAPI.php';
$pathApi = dirname(__FILE__) . '/classes/VePlatformAPI.php';

$pathProductModelInterface = dirname(__FILE__) . '/interfaces/ProductInterface.php';
$pathBasketBuilderInterface = dirname(__FILE__) . '/interfaces/BasketBuilderInterface.php';
$pathProductDecoratorInterface = dirname(__FILE__) . '/interfaces/ProductDecoratorInterface.php';
$pathDataAccessInterface = dirname(__FILE__) . '/interfaces/DataAccessInterface.php';
$pathProductInterface = dirname(__FILE__) . '/interfaces/ProductMapperInterface.php';
$pathProductServiceInterface = dirname(__FILE__) . '/interfaces/ProductServiceInterface.php';
$pathProductPrestashopServiceInterface = dirname(__FILE__) . '/interfaces/ProductPrestashopServiceInterface.php';
$pathPrestashopContextInterface = dirname(__FILE__) . '/interfaces/PrestashopContextInterface.php';

$pathAdminControllerVePlatformTab = dirname(__FILE__) . '/controllers/admin/AdminVePlatformTabController.php';

$pathProductModel = dirname(__FILE__) . '/classes/Models/ProductModel.php';
$pathProductDecorator = dirname(__FILE__) . '/classes/Models/ProductDecorator.php';
$pathDataAccess = dirname(__FILE__) . '/classes/Data/DataAccess.php';
$pathProduct = dirname(__FILE__) . '/classes/Mappers/ProductMapper.php';
$pathProductService = dirname(__FILE__) . '/classes/Services/ProductService.php';
$pathProductPrestashopService = dirname(__FILE__) . '/classes/Services/ProductPrestashopService.php';
$pathPrestashopBasketBuilder = dirname(__FILE__) . '/classes/Services/PrestashopBasketBuilder.php';
$pathPrestashopContext = dirname(__FILE__) . '/classes/Wrappers/PrestashopContext.php';

$files = array(
    $pathVendorAutoload, $pathVePlatform, $pathFileSystemHelperInterface, $pathFileSystemHelper,
    $pathECommerceProductInterface, $pathECommerceCategoryInterface, $pathPrestashopProduct, $pathPrestashopCategory,
    $pathUpgradeHandler, $pathVeData, $pathLogger, $pathVeApi, $pathApi, $pathProductModelInterface,
    $pathBasketBuilderInterface, $pathProductDecoratorInterface, $pathDataAccessInterface, $pathProductInterface,
    $pathProductServiceInterface, $pathProductPrestashopServiceInterface, $pathAdminControllerVePlatformTab,
    $pathProductModel, $pathProductDecorator, $pathDataAccess, $pathProduct, $pathProductService,
    $pathProductPrestashopService, $pathPrestashopBasketBuilder, $pathPrestashopContextInterface, $pathPrestashopContext
);

foreach ($files as $file) {
    if (file_exists($file)) {
        include_once $file;
    }
}
