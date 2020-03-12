<?php
/**
 * Module.php - Module Class
 *
 * Module Class File for Article Variant Plugin
 *
 * @category Config
 * @package Article\Variant
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Article\Variant;

use Application\Controller\CoreEntityController;
use Laminas\Mvc\MvcEvent;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\EventManager\EventInterface as Event;
use Laminas\ModuleManager\ModuleManager;
use OnePlace\Article\Variant\Controller\VariantController;
use OnePlace\Article\Variant\Model\VariantTable;
use OnePlace\Article\Model\ArticleTable;

class Module {
    /**
     * Module Version
     *
     * @since 1.0.0
     */
    const VERSION = '1.0.4';
    
    /**
     * Load module config file
     *
     * @since 1.0.0
     * @return array
     */
    public function getConfig() : array {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(Event $e)
    {
        // This method is called once the MVC bootstrapping is complete
        $application = $e->getApplication();
        $container    = $application->getServiceManager();
        $oDbAdapter = $container->get(AdapterInterface::class);
        $tableGateway = $container->get(VariantTable::class);

        # Register Filter Plugin Hook
        CoreEntityController::addHook('article-view-before',(object)['sFunction'=>'attachVariantForm','oItem'=>new VariantController($oDbAdapter,$tableGateway,$container)]);
        CoreEntityController::addHook('articlevariant-add-before-save',(object)['sFunction'=>'attachVariantToArticle','oItem'=>new VariantController($oDbAdapter,$tableGateway,$container)]);
        CoreEntityController::addHook('article-single-api-list-before',(object)['sFunction'=>'attachVariantToArticleAPI','oItem'=>new VariantController($oDbAdapter,$tableGateway,$container)]);
        CoreEntityController::addHook('article-single-api-get-before',(object)['sFunction'=>'attachVariantToArticleAPI','oItem'=>new VariantController($oDbAdapter,$tableGateway,$container)]);
    }

    /**
     * Load Models
     */
    public function getServiceConfig() : array {
        return [
            'factories' => [
                # Variant Plugin - Base Model
                Model\VariantTable::class => function($container) {
                    $tableGateway = $container->get(Model\VariantTableGateway::class);
                    return new Model\VariantTable($tableGateway,$container);
                },
                Model\VariantTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Variant($dbAdapter));
                    return new TableGateway('article_variant', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    } # getServiceConfig()

    /**
     * Load Controllers
     */
    public function getControllerConfig() : array {
        return [
            'factories' => [
                Controller\VariantController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    $tableGateway = $container->get(VariantTable::class);

                    # hook start
                    # hook end
                    return new Controller\VariantController(
                        $oDbAdapter,
                        $tableGateway,
                        $container
                    );
                },
                # Installer
                Controller\InstallController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\InstallController(
                        $oDbAdapter,
                        $container->get(Model\VariantTable::class),
                        $container
                    );
                },
            ],
        ];
    } # getControllerConfig()
}
