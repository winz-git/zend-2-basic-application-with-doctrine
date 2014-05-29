<?php
/**
 * IndexController.php
 * User: winston.c
 * Date: 16/12/13
 * Time: 3:36 PM
 */

namespace Application\Controller;


use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;


use Application\Model\Entity\AdminUsers;
use Application\Model\AdminSession;
use Zend\Session\Container;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;

class AuthController  extends BaseController {

    protected $storage;
    protected $authService;

    public function indexAction() {

        //change layout
        $layout = $this->layout();
        $layout->setTemplate('layout/login');

        $view = new ViewModel();
        //$view->setTemplate('layout/login');
        if(count($this->flashMessenger()->getMessages()) > 0){
            $view->setVariable('flashMessages', $this->flashMessenger()->getMessages());
        } else{
            $view->setVariable('flashMessages', null);
        }


        return $view;
    }

    public function loginAction() {
        $layout = $this->layout();
        $layout->setTemplate('layout/login');

        $request = $this->getRequest();
        //
        $config = $this->getServiceLocator()->get('config');
        $page_limit = $config['page_limit'];

        $viewModel = new ViewModel();
        //$viewModel->setTerminal(true);

        if ($this->getAuthService()->hasIdentity()){
            //$result = $this->getRepository('Admin\Model\Entity\')
            return $this->redirect()->toRoute('admin');
        }


        if($request->isPost()){

            $params = $this->params()->fromPost();
            $_user = null;
            $_password = null;

            $salt = $config['admin_salt'];

            $result = null;

            //
            if(isset($params['user'])
                && !empty($params['user'])){
                $_user = $params['user'];
            }

            //
            if(isset($params['pass'])
                && !empty($params['pass'])){
                $_password = sha1($salt. $params['pass']);
            }



            //
            $repository = $this->getEntityManager()->getRepository('Admin\Model\Entity\AdminUsers');
            $result = $repository->getValidUser($_user, $_password);

            //var_dump($result);
            //exit();


            if(empty($result)
                || is_null($result)){
                $this->flashMessenger()->addMessage('The credentials you provided are invalid');
                return $this->redirect()->toRoute('admin/auth');
            }

            //check if Active
            if($result[0]->getStatus() == 'ACTIVE'){
                //check authentication...
//                $this->getAuthService()->getAdapter()
//                    ->setIdentity($request->getPost('user'))
//                    ->setCredential($_password);
//
//                $auth_result = $this->getAuthService()->authenticate();



                if(strpos($result[0]->getRoles()->getRoleDescription(),'Admin') !== FALSE){
                    $redirect = 'admin';
                }else{
                    $redirect = 'admin/reports';
                }

                //
                $this->getAuthService()->setStorage($this->getSessionStorage());
                $this->getAuthService()->getStorage()->write(array('admin_login'=>true, 'login_id'=>$result[0]->getUserId(), 'login_name'=>$result[0]->getUserName()));

                return $this->redirect()->toRoute($redirect);

            }else{
                $this->flashMessenger()->addMessage('The credentials you provided are invalid');
                return $this->redirect()->toRoute('admin/auth');
            }

            $viewModel->setVariable('result', $result);
            $viewModel->setVariable('params', $params);
            $viewModel->setVariable('flashMessages', $this->flasMessenger()->getMessages());

        }else{
            $viewModel->setVariable('flashMessages', '');
        }

        return $viewModel;
    }



    public function logoutAction(){
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addMessage("You've been logged out");

        return $this->redirect()->toRoute('admin/auth');
    }



    public function deniedAction(){
        $viewModel = new ViewModel();
        $viewModel->setVariable('message', 'Permission Denied');

        return $viewModel;
    }



}