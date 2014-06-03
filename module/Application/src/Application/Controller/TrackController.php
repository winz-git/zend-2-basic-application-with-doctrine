<?php
/**
 * 
 * User: Winston
 * Date: 14/5/14
 * Time: 12:03 PM
 */

namespace Application\Controller;


use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;


use Application\Model\Navigation;

class TrackController extends BaseController{

    public function indexAction()
    {
        //
        $viewModel = new ViewModel();

        $validate_js = $this->getServiceLocator()->get('viewhelpermanager')->get('headScript');
        $validate_js->prependFile('//code.jquery.com/ui/1.9.0/jquery-ui.js');
        $validate_js->appendFile('/module/application/js/jquery.validate.min.js','text/javascript');

        //add js
        $this->getServiceLocator()->get('viewhelpermanager')->get('inlineScript')->appendFile('/module/application/js/app-trackinfo.js');

        $repository = $this->getEntityManager()->getRepository('Application\Model\Entity\TrackInfo');
        $result = $repository->getAllData();

        $result_array = array();
        if(!empty($result)){
            $idx=0;
            foreach($result as $row){

                if($row instanceof \Application\Model\Entity\TrackInfo)
                {
                    //
                    $result_array[$idx]['id'] = $row->getId();
                    $result_array[$idx]['title'] = $row->getTitle();
                    $result_array[$idx]['description'] = $row->getDescription();
                    $result_array[$idx]['year'] = $row->getYear();

                    //
                    $category = $this->getEntityManager()->getRepository('Application\Model\Entity\Categories')->find($row->getCategoryId());
                    if(!empty($category)){
                        $result_array[$idx]['category_name'] = $category->getName();
                    }else{
                        $result_array[$idx]['category_name'] = '';
                    }

                    //
                    $result_array[$idx]['created_date'] = ($row->getCreatedDate() != null) ? $row->getCreatedDate()->format('Y-m-d H:i:s') : '';
                    $result_array[$idx]['updated_date'] = ($row->getUpdatedDate() != null) ? $row->getUpdatedDate()->format('Y-m-d H:i:s') : '';


                }

                $idx++;
            }
        }

        $viewModel->setVariable('result', $result_array);


        return $viewModel;

    }

    public function addAction()
    {
        //
        $viewModel = new ViewModel();

        //
        //header script
        $validate_js = $this->getServiceLocator()->get('viewhelpermanager')->get('headScript');
        $validate_js->prependFile('//code.jquery.com/ui/1.9.0/jquery-ui.js');
        $validate_js->appendFile('/module/application/js/jquery.validate.min.js','text/javascript');

        //add js
        $this->getServiceLocator()->get('viewhelpermanager')->get('inlineScript')->appendFile('/module/application/js/app-trackinfo.js');


        $request = $this->getRequest();

        if($request->isPost())
        {
            //
            $params = array_merge_recursive(
                $this->params()->fromPost(),
                $this->params()->fromRoute()

            );

            //
            if(isset($params['inputTitle'])){

                $trackInfo = new \Application\Model\Entity\TrackInfo();

                $trackInfo->setTitle($params['inputTitle']);
                $trackInfo->setDescription($params['inputDescription']);
                $trackInfo->setCategoryId($params['inputCategory']);
                $trackInfo->setYear($params['inputYear']);

                //
                $trackInfo->setCreatedDate(new \DateTime());
                $trackInfo->setUpdatedDate(new \DateTime());

                //
                $this->getEntityManager()->persist($trackInfo);
                $this->getEntityManager()->flush();
            }


            return $this->redirect()->toRoute('track');

        }else{
            //
        }



        $repository = $this->getEntityManager()->getRepository('Application\Model\Entity\Categories');
        $result = $repository->getAllCategories();
        $viewModel->setVariable('categories', $result);




        return $viewModel;
    }


    public function editAction()
    {
        $viewModel = new ViewModel();

        $params = array_merge_recursive(
            $this->params()->fromQuery(),
            $this->params()->fromPost(),
            $this->params()->fromRoute()

        );

        //
        //header script
        $validate_js = $this->getServiceLocator()->get('viewhelpermanager')->get('headScript');
        $validate_js->prependFile('//code.jquery.com/ui/1.9.0/jquery-ui.js');
        $validate_js->appendFile('/module/application/js/jquery.validate.min.js','text/javascript');

        //add js
        $this->getServiceLocator()->get('viewhelpermanager')->get('inlineScript')->appendFile('/module/application/js/app-trackinfo.js');


        $request = $this->getRequest();

        if($request->isPost()){
            //save
            $params = array_merge_recursive(
                $this->params()->fromPost(),
                $this->params()->fromRoute()
            );


            if(isset($params['inputId'])){

                $repository = $this->getEntityManager()->getRepository('Application\Model\Entity\TrackInfo');
                $trackInfo = $repository->find($params['inputId']);

                $trackInfo->setTitle($params['inputTitle']);
                $trackInfo->setDescription($params['inputDescription']);
                $trackInfo->setCategoryId($params['inputCategory']);
                $trackInfo->setYear($params['inputYear']);

                //
                $trackInfo->setUpdatedDate(new \DateTime());

                //
                $this->getEntityManager()->flush();
            }

            return $this->redirect()->toRoute('track');
        }else{
            //edit mode
            $repository = $this->getEntityManager()->getRepository('Application\Model\Entity\TrackInfo');
            $result = $repository->find($params['id']);

            $result_array = array();
            if(!empty($result)){

                if($result instanceof \Application\Model\Entity\TrackInfo)
                {
                    //
                    $result_array['id'] = $result->getId();
                    $result_array['title'] = $result->getTitle();
                    $result_array['description'] = $result->getDescription();
                    $result_array['year'] = $result->getYear();
                    $result_array['category_id'] = $result->getCategoryId();

                    //
                    $category = $this->getEntityManager()->getRepository('Application\Model\Entity\Categories')->find($result->getCategoryId());
                    if(!empty($category)){
                        $result_array['category_name'] = $category->getName();
                    }else{
                        $result_array['category_name'] = '';
                    }

                    //
                    $result_array['created_date'] = ($result->getCreatedDate() != null) ? $result->getCreatedDate()->format('Y-m-d H:i:s') : '';
                    $result_array['updated_date'] = ($result->getUpdatedDate() != null) ? $result->getUpdatedDate()->format('Y-m-d H:i:s') : '';


                }
            }


            $viewModel->setVariable('trackinfo', $result_array);
        }

        $repository = $this->getEntityManager()->getRepository('Application\Model\Entity\Categories');
        $result = $repository->getAllCategories();
        $viewModel->setVariable('categories', $result);

        return $viewModel;
    }


    public function deleteAction()
    {
        $viewModel = new ViewModel();

        $request = $this->getRequest();

        $params = array_merge_recursive(
            $this->params()->fromRoute(),
            $this->params()->fromQuery()
        );

        //
        //header script
        $validate_js = $this->getServiceLocator()->get('viewhelpermanager')->get('headScript');
        $validate_js->prependFile('//code.jquery.com/ui/1.9.0/jquery-ui.js');
        $validate_js->appendFile('/module/application/js/jquery.validate.min.js','text/javascript');

        //add js
        $this->getServiceLocator()->get('viewhelpermanager')->get('inlineScript')->appendFile('/module/application/js/app-trackinfo.js');


        if($request->isPost())
        {
            //if confirmed to delete
            if(isset($params['id']))
            {
                $repository = $this->getEntityManager()->getRepository('Application\Model\Entity\TrackInfo')->find($params['id']);

                $this->getEntityManager()->remove($repository);
                $this->getEntityManager()->flush();
            }
            //redirect to main

            return $this->redirect()->toRoute('track');

        }else{
            //to confirm
            $repository = $this->getEntityManager()->getRepository('Application\Model\Entity\TrackInfo');
            $result = $repository->find($params['id']);

            $result_array = array();
            if($result instanceof \Application\Model\Entity\TrackInfo)
            {
                //
                $result_array['id'] = $result->getId();
                $result_array['title'] = $result->getTitle();
                $result_array['description'] = $result->getDescription();
                $result_array['year'] = $result->getYear();
                $result_array['category_id'] = $result->getCategoryId();

                //
                $category = $this->getEntityManager()->getRepository('Application\Model\Entity\Categories')->find($result->getCategoryId());
                if(!empty($category)){
                    $result_array['category_name'] = $category->getName();
                }else{
                    $result_array['category_name'] = '';
                }

                //
                $result_array['created_date'] = ($result->getCreatedDate() != null) ? $result->getCreatedDate()->format('Y-m-d H:i:s') : '';
                $result_array['updated_date'] = ($result->getUpdatedDate() != null) ? $result->getUpdatedDate()->format('Y-m-d H:i:s') : '';


            }

            $viewModel->setVariable('trackinfo', $result_array);
        }

        $repository = $this->getEntityManager()->getRepository('Application\Model\Entity\Categories');
        $result = $repository->getAllCategories();
        $viewModel->setVariable('categories', $result);

        return $viewModel;
    }





} 