<?php
/**
 * module.config.php - Variant Config
 *
 * Main Config File for Article Variant Plugin
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

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    # Variant Module - Routes
    'router' => [
        'routes' => [
            'article-variant' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/article/variant[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\VariantController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'article-variant-setup' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/article/variant/setup',
                    'defaults' => [
                        'controller' => Controller\InstallController::class,
                        'action'     => 'checkdb',
                    ],
                ],
            ],
        ],
    ], # Routes

    # View Settings
    'view_manager' => [
        'template_path_stack' => [
            'article-variant' => __DIR__ . '/../view',
        ],
    ],
];
