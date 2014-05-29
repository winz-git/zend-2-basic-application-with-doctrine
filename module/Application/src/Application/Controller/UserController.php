<?php
/**
 * 
 * User: Winston
 * Date: 15/5/14
 * Time: 12:19 PM
 */

namespace Application\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Zend\Db\Sql\Sql,
    Zend\Db\ResultSet\ResultSet;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class UserController extends BaseController{

    public function preferenceAction()
    {
        $viewModel = new ViewModel();

        return $viewModel;
    }


    public function profileAction()
    {
        //
        $viewModel = new ViewModel();

        return $viewModel;
    }

    public function signInAction()
    {
        //
        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/user/sign-in');

        return $viewModel;
    }

} 