<?php
/**
 * Navigation.php
 * User: winston.c
 * Date: 23/12/13
 * Time: 5:17 PM
 */

namespace Application\Model;


use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class Navigation extends DefaultNavigationFactory {

    protected function getPages(ServiceLocatorInterface $serviceLocator){
        if(null === $this->pages){

            $application = $serviceLocator->get('Application');

            $routeMatch  = $application->getMvcEvent()->getRouteMatch();

            $router      = $application->getMvcEvent()->getRouter();
            $config      = $serviceLocator->get('config');
            $pages       = $this->getPagesFromConfig($config['navigation'][$this->getName()]);


            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }

        return $this->pages;
    }



} 