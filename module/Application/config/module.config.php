<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'track' => array(
                'type' => 'Segment',
                'options'=> array(
                    'route' => '/track[/:action[/:id]]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Track',
                        'action' => 'index'
                    ),
                    'constraints'=> array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    //Index
                    'index' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/',
                            'defaults' => array(
                                'module' => 'track',
                                'controller' => 'Application\Controller\Track',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate'=> true,

                    ),
                ),
            ),
            //User Profile
            'user' => array(
                'type' => 'Segment',
                'options'=> array(
                    'route' => '/user[/:action[/:id]]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action' => 'index'
                    ),
                    'constraints'=> array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    //Index
                    'index' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/',
                            'defaults' => array(
                                'module' => 'track',
                                'controller' => 'Application\Controller\User',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate'=> true,

                    ),
                ),
            ),
            //
            //Language
            'language' => array(
                'type' => 'Segment',
                'options'=> array(
                    'route' => '/language[/:code]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Language',
                        'action' => 'index'
                    ),
                    'constraints'=> array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'code' => '[a-zA-Z][a-zA-Z]',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    //Index
                    'index' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/',
                            'defaults' => array(
                                'module' => 'track',
                                'controller' => 'Application\Controller\Language',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate'=> true,

                    ),
                ),
            ),

        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            // 'Application\Cache\Redis' => 'Application\Service\Factory\RedisCacheFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            //'secondary_nav' => 'Admin\Service\Factory\NavigationFactory',
            //'navigation' => 'Admin\Service\Factory\NavigationFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Track' => 'Application\Controller\TrackController',
            'Application\Controller\User' => 'Application\Controller\UserController',
            'Application\Controller\Language' => 'Application\Controller\LanguageController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'doctrine' => array(
        'orm_autoload_annotations' => true,

        'connection' => array(
            'orm_default' => array(
                'configuration' =>'orm_default',
                'event_manager' => 'orm_default',

                //params
                'doctrine_type_mappings' => array(
                    'enum' => 'string'
                ),
            ),
            'orm_alternate' => array(
                'configuration' =>'orm_alternate',
                'event_manager' => 'orm_alternate',

                //params
                'doctrine_type_mappings' => array(
                    'enum' => 'string'
                ),
            )
        ),

        'configuration' => array(
            'orm_default' => array(
                'metadata_cache'    => 'array',
                'query_cache'       => 'array',
                'result_cache'      => 'array',

                'driver'            => 'orm_default',

                'generate_proxies'  => false,
                'proxy_dir'         => array(__DIR__ .'/../../../data/DoctrineORMModule/Proxy'),
                'proxy_namespace'   => 'DoctrineProxies'
            ),
            'orm_alternate' => array(
                'metadata_cache'    => 'array',
                'query_cache'       => 'array',
                'result_cache'      => 'array',

                'driver'            => 'orm_alternate',

                'generate_proxies'  => false,
                'proxy_dir'         => array(__DIR__ .'/../../../data/DoctrineORMModule/Proxy'),
                'proxy_namespace'   => 'DoctrineProxies'
            )
        ),

        'driver' => array(
            __NAMESPACE__ .'_orm_default_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ .'\Model\Entity' => __NAMESPACE__ . '_orm_default_driver'
                )
            ),
        ),

        'entitymanager' => array(
            'orm_default' => array(
                'connection' => 'orm_default',
                'configuration'=> 'orm_default',
            ),
            'orm_alternate' => array(
                'connection' => 'orm_alternate',
                'configuration'=> 'orm_alternate',
            )

        ),

        'eventmanager' => array(
            'orm_default' => array(),
            'orm_alternate' => array(),
        ),

        'entity_resolver' => array(
            'orm_default' => array(),
            'orm_alternate' => array(),
        ),


        //Cache
        'cache' => array(
            'apc' => array(
                'class'     => 'Doctrine\Common\Cache\ApcCache',
                'namespace' => 'DoctrineModule',
            ),
            'array' => array(
                'class' => 'Doctrine\Common\Cache\ArrayCache',
                'namespace' => 'DoctrineModule',
            ),
            'filesystem' => array(
                'class'     => 'Doctrine\Common\Cache\FilesystemCache',
                'directory' => 'data/DoctrineModule/cache',
                'namespace' => 'DoctrineModule',
            ),
            'memcache' => array(
                'class'     => 'Doctrine\Common\Cache\MemcacheCache',
                'instance'  => 'my_memcache_alias',
                'namespace' => 'DoctrineModule',
            ),
            'memcached' => array(
                'class'     => 'Doctrine\Common\Cache\MemcachedCache',
                'instance'  => 'my_memcached_alias',
                'namespace' => 'DoctrineModule',
            ),
            'redis' => array(
                'class'     => 'Doctrine\Common\Cache\RedisCache',
                'instance'  => 'my_redis_alias',
                'namespace' => 'DoctrineModule',
            ),
            'wincache' => array(
                'class'     => 'Doctrine\Common\Cache\WinCacheCache',
                'namespace' => 'DoctrineModule',
            ),
            'xcache' => array(
                'class'     => 'Doctrine\Common\Cache\XcacheCache',
                'namespace' => 'DoctrineModule',
            ),
            'zenddata' => array(
                'class'     => 'Doctrine\Common\Cache\ZendDataCache',
                'namespace' => 'DoctrineModule',
            ),
        )
    ),
//
//    'session' => array(
//        'remember_me_seconds' => 2419200,
//        'use_cookies' => true,
//        'cookie_httponly' => true,
//    ),
    //
    // Navigation
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Track',
                'route' => '/track',
                'controller' => 'Application\Controller\Track',
                'pages' => array(
                    array(
                        'label' => 'Track Add',
                        'route' => 'track/add',
                        'controller' => 'Application\Controller\Track',
                        'action' => 'add'
                    )

                )

            ),

            // Others

        )
    )
);
