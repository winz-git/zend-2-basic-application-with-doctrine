<?php
/**
 *
 * User: winston.c
 * Date: 07/11/13
 * Time: 10:10 AM
 * 
 */

namespace Application\Controller;




use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;


abstract class BaseController extends AbstractActionController {

    protected $entityManager;
    protected $adapter;
    protected $authService;


    public function setEntityManager(EntityManager $em) {
        $this->entityManager = $em;
        return $this;
    }

    public function getEntityManager($entity_manager = 'doctrine.entitymanager.orm_default') {
        if (null === $this->entityManager) {
            $this->setEntityManager($this->getServiceLocator()->get($entity_manager));//Doctrine\ORM\EntityManager
        }
        return $this->entityManager;
    }

    public function getAdapter()
    {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('adapter2');
        }
        return $this->adapter;
    }

    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
                ->get('Admin\Model\AdminSession');
        }

        return $this->storage;
    }

    public function getAuthService() {
        if(null === $this->authService) {
            $this->authService = $this->getServiceLocator()->get('Application\Authentication\Service');
        }
        return $this->authService;
    }


    public function getRepository($entity){
        return $this->getEntityManager()->getRepository($entity);
    }

    public function getConfig(){
        return $this->getServiceLocator()->get('config');
    }

    public function getViewHelper(){
        return $this->getServiceLocator()->get('viewHelperManager');
    }

    public function setInlineScript($stmt) {

        $script = $this->getServiceLocator()->get('viewHelperManager')
            ->get('inlineScript');


        $script->appendScript(
            $stmt,
            'text/javascript',
            array('noescape' => true));


        return $script;
    }




}