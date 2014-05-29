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

class LanguageController extends BaseController{

    public function indexAction()
    {
        $viewModel = new ViewModel();

        $viewModel->setTemplate('application/ajax/response');
        $viewModel->setTerminal(true);

        $params = array_merge_recursive(
          $this->params()->fromRoute()
        );

        if(isset($params['code']))
        {
            $translator = $this->getServiceLocator()->get('translator');

            switch($params['code'])
            {
                case 'en':
                    $translator->setLocale('en_US');
                    break;
                case 'cn':
                    $translator->setLocale('zh_CN');
                    break;
                case 'hk':
                    $translator->setLocale('zh_TW');
                    break;
                case 'ja':
                    $translator->setLocale('ja_JP');
                    break;
                default:
                    $translator->setFallbackLocale('en_US');
                    break;

            }


        }

        return $this->redirect()->toRoute('track');
    }


} 