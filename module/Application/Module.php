<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Service\CacheFactory;
use DoctrineModule\Service\ZendStorageCacheFactory;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Doctrine\DBAL\Types\Type;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {

        $eventManager        = $e->getApplication()->getEventManager();
        $serviceManager      = $e->getApplication()->getServiceManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //layout
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setLayout'));



        $em = $serviceManager->get('Doctrine\ORM\EntityManager');
        $platform = $em->getConnection()->getDatabasePlatform();

        //Mapping
        $platform->registerDoctrineTypeMapping('enum', 'string');
        $platform->registerDoctrineTypeMapping('set', 'string');
        $platform->registerDoctrineTypeMapping('varbinary', 'string');
        $platform->registerDoctrineTypeMapping('tinyblob', 'text');



        //set timezone
        date_default_timezone_set('America/Toronto');

        //
        ini_set('memory_limit','2048M');

        //$layout = $e->getViewModel();
        //$layout->userInfo = $serviceManager->get('Application\Authentication\Service')->getStorage()->read();
    }

    public function getConfig()
    {

        $config = array();
        $configFiles = array(
            include __DIR__ . '/config/module.config.php',
            include __DIR__ . '/config/module.customconfig.php',
        );
        foreach ($configFiles as $file) {
            $config = \Zend\Stdlib\ArrayUtils::merge($config, $file);
        }
        return $config;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


    public function setLayout(\Zend\Mvc\MvcEvent $e)
    {
        $matches    = $e->getRouteMatch();
        $controller = $matches->getParam('controller');

        if (false === strpos($controller, __NAMESPACE__)) {
            // not a controller from this module
            return;
        }




        // Set the layout template
        $viewModel = $e->getViewModel();
        //$viewModel->setTemplate('admin/layout');

        //navigation
        $factory = new \Application\Model\Navigation();
        $navigation = $factory->createService($e->getApplication()->getServiceManager());

        $layout = $e->getViewModel();

        //remove those links are not permitted

        //populate menu
        if(method_exists($layout, 'setVariable')){
            $layout->setVariable('custom_nav',$navigation);
        }
    }

    public function onRender(\Zend\Mvc\MvcEvent $e)
    {
        if($e->getResponse()->getStatusCode() == 404){
            $e->getViewModel()->setTemplate('layout/error-layout');
        }
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                //'doctrine.cache.redis' => 'Admin\Service\Factory\DoctrineRedisCacheFactory',
                //'doctrine.cache.array' => 'Doctrine\Common\Cache\Array',
                //'doctrine.cache.array' => 'Doctrine\Common\Cache\Array',


                'DoctrineORMModule\Form\Annotation\AnnotationBuilder' => function(ServiceLocatorInterface $sl) {
                        return new AnnotationBuilder($sl->get('doctrine.entitymanager.orm_default'));
                    },
            )
        );
    }
}
