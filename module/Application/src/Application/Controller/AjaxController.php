<?php
/**
 * AjaxController.php
 * User: winston.c
 * Date: 16/12/13
 * Time: 6:08 PM
 */

namespace Application\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use Application\Library;


class AjaxController extends BaseController {

    public function indexAction() {
        //
        $this->viewModel = new ViewModel( array(
                'response' => \Zend\Json\Json::encode(array('params1'=>1,'params2'=>2))
            )
        );
        $this->viewModel->setTemplate('application/ajax/response');
        $this->viewModel->setTerminal(true);

        return $this->viewModel;
    }

    public function getConvertedToPayPerDayAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/ajax/response');
        $viewModel->setTerminal(true);

        $params = $this->params()->fromQuery();

        $config = $this->getConfig();

        $max = $config['page_limit'];

        //$paginator = $this->getRepository('Admin\Model\Entity\SummaryDailyWithoutSubIds')->getSummaryWithConvertedToPay($params,0,$max);
        $result = $this->getRepository('Application\Model\Entity\SummaryDailyWithoutSubIds')->getSummaryWithConvertedToPayNativeQuery($params,0,$max);
//        $paginator
//            ->setCurrentPageNumber(1)
//           ->setItemCountPerPage($max);
//
//        $total_rows = $paginator->getTotalItemCount();
//        $result = 0;
//        if(!empty($paginator)){
//            foreach($paginator as $row){
//                $result = $row->getTotalPaidUser();
//            }
//        }

        //var_dump($result);

        $total_ctp = (count($result) > 0 ? $result[0]['paid']: 0);



        $viewModel->setVariable('response', \Zend\Json\Json::encode(array('result'=>(int)$total_ctp,'total_rows'=>0)));

        return $viewModel;
    }

    public function getFilterOptionsAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/ajax/response');
        $viewModel->setTerminal(true);

        $params = $this->params()->fromQuery();

        $filter = $params['filter'];
        $result_data  = array();

        switch($filter){
            case 'affiliate':
                $result = $this->getRepository('Application\Model\Entity\SummaryDailyWithoutSubIds')->getAllAffiliates();
                if(!empty($result)){
                    $idx = 1;
                    $result_data[0]['id'] = '';
                    $result_data[0]['label'] = 'All';
                    foreach($result as $row){
                        if(!empty($row['affiliate_id'])){
                            $result_data[$idx]['id'] = $row['affiliate_id'];
                            $result_data[$idx]['label'] = $row['affiliate_id'];
                            $idx++;
                        }
                    }

                }
                break;
            case 'subaid1':
            case 'subaid2':
            case 'subaid3':
                $id = substr($filter, -1);
                $result = $this->getRepository('Admin\Model\Entity\SummaryDailyWithoutSubIds')->getAllSubAffiliates($id);
                if(!empty($result)){
                    $idx = 1;
                    $result_data[0]['id'] = '';
                    $result_data[0]['label'] = 'All';
                    foreach($result as $row){
                        if($row['subaid'] != null){
                            $result_data[$idx]['id'] = $row['subaid'];
                            $result_data[$idx]['label'] = $row['subaid'];
                            $idx++;
                        }

                    }

                }

                break;
            case 'campaign':
                $result = $this->getRepository('Admin\Model\Entity\SummaryDailyWithoutSubIds')->getAllCampaignIds();
                if(!empty($result)){
                    $idx = 1;
                    $result_data[0]['id'] = '';
                    $result_data[0]['label'] = 'All';
                    foreach($result as $row){
                        if($row['campaign_id'] != null
                          || !empty($row['campaign_id'])){
                            $result_data[$idx]['id'] = $row['campaign_id'];
                            $result_data[$idx]['label'] = $row['campaign_id'];
                            $idx++;
                        }

                    }

                }

                break;
            case 'traffic_source':
                $result = $this->getRepository('Admin\Model\Entity\DownloadTracking')->getAllTrafficSources();
                if(!empty($result)){
                    $idx = 0;
                    foreach($result as $row){
                        $result_data[$idx]['id'] = $row['traffic_source'];
                        $result_data[$idx]['label'] = $row['traffic_source'];
                        $idx++;
                    }

                }

                break;
            case 'app_version':
                $config = $this->getConfig();
                $result = $this->getRepository('Admin\Model\Entity\Devices')->getAllAppVersion();
                $idx = 1;
                $result_data[0]['id'] = '';
                $result_data[0]['label'] = 'All';
                foreach($result as $index =>$row){
                    if($row instanceof \Admin\Model\Entity\Devices){
                        $id = $row->getAppVersion();
                       // var_dump(is_null($id));
                        if(!is_null($id)){
                            $result_data[$idx]['id'] = $row->getAppVersion();
                            $result_data[$idx]['label'] = $row->getAppVersion();
                            $idx++;
                        }

                    }

                }
                break;
            case 'version_requested':
                $config = $this->getConfig();
                $result = $this->getRepository('Admin\Model\Entity\DownloadTracking')->getAllVersionSources();
                $idx = 1;
                $result_data[0]['id'] = '';
                $result_data[0]['label'] = 'All';
                foreach($result as $index =>$row){
                    if($row instanceof \Admin\Model\Entity\DownloadTracking){
                        $id = $row->getVersionRequested();
                        if(!is_null($id)){
                            $result_data[$idx]['id'] = $row->getVersionRequested();
                            $result_data[$idx]['label'] = $row->getVersionRequested();
                            $idx++;
                        }
                    }

                }
                break;
            case 'country':
                $config = $this->getConfig();
                $result = $this->getRepository('Admin\Model\Entity\Country')->getAllCountry(array(),0,1000);
                $idx = 1;
                $result_data[0]['id'] = '';
                $result_data[0]['label'] = 'All';
                foreach($result as $index =>$row){
                    $result_data[$idx]['id'] = $row['country_code'];
                    $result_data[$idx]['label'] = $row['country_name'];
                    $idx++;
                }
                break;
            case 'transaction_status':
                $config = $this->getConfig();
                $result = $config['transaction_status_list'];
                $idx = 1;
                $result_data[0]['id'] = '';
                $result_data[0]['label'] = 'All';
                foreach($result as $index =>$row){
                    $result_data[$idx]['id'] = $row;
                    $result_data[$idx]['label'] = $row;
                    $idx++;
                }

                break;
            case 'status':
                $config = $this->getConfig();
                $result = $config['customer_status_list'];
                $idx = 1;
                $result_data[0]['id'] = '';
                $result_data[0]['label'] = 'All';
                foreach($result as $index =>$row){
                    $result_data[$idx]['id'] = $row;
                    $result_data[$idx]['label'] = $row;
                    $idx++;
                }

                break;
            default:
                $result_data = array();
                break;
        }

        $viewModel->setVariable('response', \Zend\Json\Json::encode(array('result'=>$result_data,'total_rows'=>count($result_data))));



        return $viewModel;
    }

    public function getAllCustomerListAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);


        //$customer_id = $this->params()->fromPost('id');

        $params = $this->params()->fromQuery();
        $results = $this->getRepository('Admin\Model\Entity\Customers')->getAllCustomers($params,  $params['jtStartIndex'], $params['jtPageSize'],$params['jtSorting']);

        //var_dump($paginator);
//        $paginator->setDefaultItemCountPerPage(10);
//        $page = (int)$params['jtStartIndex'] + 1;
//        $paginator->setCurrentPageNumber($page);
//        $paginator->setItemCountPerPage($params['jtPageSize']);
//        var_dump($paginator);

        //var_dump($results);



        $result_array = array();
        if(!empty($results)){
            $idx=0;
            foreach($results as $row){
                //Customer & device

                if(!empty($row)
                  && sizeof($row)>1){
                    try{

                        foreach($row as $key=>$data){
                            if($data instanceof \DateTime){
                                $result_array[$idx][$key] = $data->format('Y-m-d H:i:s');
                            }else{
                                $result_array[$idx][$key] = $data;
                            }
                        }

                        //scalar result
                        $result_array[$idx]['transaction_count'] = $row;
                        $idx++;

                    }catch(Exception $e){

                        //die($e->getMessage());
                    }

                }


            }
        }



        $viewModel->setVariable('response',\Zend\Json\Json::encode(array('Result'=>'OK', 'Records'=>$result_array, 'TotalRecordCount'=>$results['foundRows'])));


        return $viewModel;

    }

    public function getCustomerListAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);


        $params = $this->params()->fromQuery();
        $config = $this->getConfig();

        //echo 'params='.print_r($params, true);

        $query = new \Admin\Model\Reports\CustomerList($this->getAdapter());
        $offset = (isset($params['jtStartIndex']) ? $params['jtStartIndex']: 0);
        $limit = (isset($params['jtPageSize']) ? $params['jtPageSize']: $config['page_limit']);
        $resultset = $query->getTransactionOfCustomer($params['id'], $offset, $limit);
        $result = $query->initialize($resultset['result'])->toArray();


//        $view = new \Zend\View\Renderer\PhpRenderer();
//        $resolver = new \Zend\View\Resolver\TemplateMapResolver();
//        $resolver->setMap(array( 'customerDetailTemplate' => __DIR__ . '/../../../view/admin/template/customer-detail-listing.phtml' ));
//        $view->setResolver($resolver);
//        $_viewModel = new \Zend\View\Model\ViewModel();
//        $_viewModel->setTemplate('customerDetailTemplate');
//        $_viewModel->setVariable('result', $result);
//        $html_output = $view->render($_viewModel);



        $viewModel->setVariable('response',\Zend\Json\Json::encode(array('Result'=>'OK','Records'=>$result, 'TotalRecordCount'=>$resultset['foundRows'])));


        return $viewModel;

    }

    public function getFormAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $config = $this->getConfig();
        $params = $this->params()->fromQuery();

        $partial = $this->getViewHelper()->get('partial');

        $partial_html = '';
        $current_date = new \DateTime();

        switch($params['source']){
            case 'privilege-add':
                  //retrieve limelight information
                $order_id = '';
                 if(isset($params['data'])
                   && !empty($params['data'])){
                    $order_arr = explode('=',$params['data']);
                     $order_id = (int)$order_arr[1];
                 }
                 $raw_information = array();
                  if(!empty($order_id) ){
                      $raw_information = $this->getOrderInformation($order_id);
                  }

                  $sku = '';
                  $amount = 0.00;
                  $limelight_customer_id = 0;
                  if(!empty($raw_information)){
                      $sku = $raw_information['products'][0]['sku'];
                      $amount = $raw_information['order_total'];
                      $order_id = $order_id;
                      $limelight_customer_id = $raw_information['customer_id'];
                  }

                  //echo 'data='.print_r($raw_information, true);
                   $partial_html = $partial('partial/transaction-info', array('current_date'=>$current_date->format($config['display_short_date'])
                                                                              ,'sku'=>$sku
                                                                              , 'order_id'=>$order_id
                                                                              , 'amount'=>$amount
                                                                              ,'limelight_customer_id'=>$limelight_customer_id
                                                                        ));
                break;
            case 'customer-view':
                $partial_html = $partial('partial/limelight-customer-view', array());
                break;
            case 'customer-find':
                   $partial_html = $partial('partial/limelight-customer-find', array('current_date'=>$current_date->format($config['display_short_date'])));
                break;
            case 'order-view':
                $partial_html = $partial('partial/limelight-order-view',array());
                break;
            case 'order-find':
                $partial_html = $partial('partial/limelight-order-find', array('current_date'=>$current_date->format($config['display_short_date'])));
                break;
        }

        $viewModel->setVariable('response', \Zend\Json\Json::encode(array('status'=>'success','result'=>$partial_html)));



        return $viewModel;
    }

    public function getAllResourcesAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $config = $this->getConfig();
        $params = $this->params()->fromQuery();

        $limit = (isset($params['jtPageSize']) ? $params['jtPageSize'] : $config['page_limit']);
        $offset = ((isset($params['jtStartIndex']) && $params['jtStartIndex'] > 0) ? (($params['jtStartIndex']/$limit) + 1) : 1);


        //
        $result_obj = $this->getRepository('Admin\Model\Entity\AdminResources')->getAllResourceQuery($params, $offset, $limit);

        $result = array();
        $total_records = 0;
        $result_msg = 'ERROR';


        if(!empty($result_obj)){

            $idx = 0;
            foreach($result_obj['pagination'] as $row){
                if($row instanceof \Admin\Model\Entity\AdminResources){
                    $result[$idx] = array('resource_id'=>$row->getResourceId(),'resource'=>$row->getResource(), 'deleted'=>$row->getDeleted());
                }
                $idx++;
            }

            //
            $total_records = $result_obj['paginator']->getCount();
            $result_msg = 'OK';
        }


        $viewModel->setVariable('response',\Zend\Json\Json::encode(array('Result'=>$result_msg, 'TotalRecordCount'=>$total_records,'Records'=>$result)));

        return $viewModel;
    }

    public function doDailySummaryGeoipAction(){
            //
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $params = $this->params()->fromQuery();
        $_date = $params['selected_date'];

        $start_time = new \DateTime();
        $log[] = 'record-date='.$_date ."\r\n";
        $log[] = 'start_time:'. $start_time->format('Y-m-d H:i:s') . "\r\n";

        //get all the leads for today
        $leads = $this->getRepository('Admin\Model\Entity\DownloadTracking')->getLeadsForGeoip($_date);

        //
        $params['lead_date'] = $_date;
        $resultset = $this->getRepository('Admin\Model\Entity\SummaryDailyWithoutSubIdsGeoipTemp')->getSummaryWithGeoipTempExtraNativeQuery($params,0, 0, 'transaction_id ASC');

        $raw_data = $resultset;
        $end_time = new \DateTime();

        $log[] = 'end of retrieve the result:'. $end_time->format('Y-m-d H:i:s'). "\r\n";


        if(!empty($raw_data)){
            $row_stmt = array();
            foreach($raw_data as $index => $row){
                //get the associated record and update row
                $ip_address = trim($row['ip_address']);

                if(empty($ip_address)) continue;


                if(trim($row['country']) == ''){
                    $geolocation = $this->getServiceLocator()->get('geoip')->getRecord($ip_address);
                    //echo 'location='. print_r($geolocation, true). "\r\n";

                    //
                    $raw_data[$index]['country']  = $geolocation->getCountryCode();
                    //$raw_data[$index]['region']  = $geolocation->getRegion();
                    //$raw_data[$index]['city']  = $geolocation->getCity();
                }

                $data_str = array();
                foreach($raw_data[$index] as $key=>$xrow){
                    switch($key){
                        case 'transaction_date':
                        case 'affiliate_id':
                        case 'subaid1':
                        case 'subaid2':
                        case 'subaid3':
                        case 'ip_address':
                        case 'country':
                        case 'region':
                        case 'city':
                            $data_str[] = $this->getServiceLocator()->get('adapter')->getPlatform()->quoteValue($xrow);
                            break;
                        default:
                            $data_str[] = $xrow;
                            break;
                    }

                }

                $row_stmt[] = "(". implode(",",$data_str) . ")";

            }


            //clean
            $this->getRepository('Admin\Model\Entity\SummaryDailyWithoutSubIdsGeoipTemp')->removeGeoipDataWithCountry($_date);

            //batch update with country
            $this->getRepository('Admin\Model\Entity\SummaryDailyWithoutSubIdsGeoipTemp')->insertDataWithCountry($row_stmt);

            //insert into final table
            $this->getRepository('Admin\Model\Entity\SummaryDailyWithoutSubIdsGeoipTemp')->insertDataWithCountryIntoFinalTable($_date);



        }

        $final_time = new \DateTime();
        $log[] = 'end of setting country:'. $final_time->format('Y-m-d H:i:s'). "\r\n";

        $end_time = new \DateTime();
        $log[] = 'end_time:'. $end_time->format('Y-m-d H:i:s') . "\r\n";

        $diff = $start_time->diff($end_time);

        $log[] = 'time difference='. $diff->format('%H:%i:%s') . "\r\n";

        $viewModel->setVariable('response', \Zend\Json\Json::encode(array('status'=>'success','result'=>$log)));

        return $viewModel;
    }

    public function getSubidsAction() {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $params = $this->params()->fromQuery();

        //
        $result = $this->getRepository('Admin\Model\Entity\DownloadTracking')->getSubId($params['id'],0, $params['pageSize']);


        $viewModel->setVariable('response', \Zend\Json\Json::encode(array('status'=>'success','result'=>$result)));

        return $viewModel;

    }    

//////////Chart Data Functions/////////////////////////////////////////////////////////////////////////////	

    public function getLastSevenDayDownloadAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $query = new \Admin\Model\Reports\ChartData($this->getAdapter());
        $resultset = $query->getReport();
        $result = $query->initialize($resultset)->toArray();

		$chart = array("caption" => "Last 7 Days Downloads", "xAxisName" => "Date", "yAxisName" => "Downloads");
		$_output = array(
						'chart' => $chart,
						'data' => $result
						);
						
        $viewModel->setVariable('response',\Zend\Json\Json::encode($_output));

		return $viewModel;

    }

    public function getLastSevenDayInstallAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $query = new \Admin\Model\Reports\ChartData($this->getAdapter());
        $resultset = $query->getInstallReport();
        $result = $query->initialize($resultset)->toArray();

		$chart = array("caption" => "Last 7 Days Install", "xAxisName" => "Date", "yAxisName" => "Install");
		$_output = array(
						'chart' => $chart,
						'data' => $result
						);
						
        $viewModel->setVariable('response',\Zend\Json\Json::encode($_output));

		return $viewModel;

    }	

    public function getLastSevenDayLeadAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $query = new \Admin\Model\Reports\ChartData($this->getAdapter());
        $resultset = $query->getLeadReport();
        $result = $query->initialize($resultset)->toArray();

		$chart = array("caption" => "Last 7 Days Lead", "xAxisName" => "Date", "yAxisName" => "Leads");
		$_output = array(
						'chart' => $chart,
						'data' => $result
						);
						
        $viewModel->setVariable('response',\Zend\Json\Json::encode($_output));

		return $viewModel;

    }	

    public function getTopAffiliateAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $query = new \Admin\Model\Reports\ChartData($this->getAdapter());
        $resultset = $query->getTopAffiliate();
        $result = $query->initialize($resultset)->toArray();

        $chart = array("caption" => "Top 3 Affiliates for Last 7 Days", "xAxisName" => "Affiliate ID", "yAxisName" => "Leads");
        $_output = array(
                        'chart' => $chart,
                        'data' => $result
                        );
                        
        $viewModel->setVariable('response',\Zend\Json\Json::encode($_output));

        return $viewModel;

    }

    public function getTopAffiliateBreakdownAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $affiliate_query = new \Admin\Model\Reports\ChartData($this->getAdapter());
        $affiliate_resultset = $affiliate_query->getTopAffiliate();
        $affiliate_result = $affiliate_query->initialize($affiliate_resultset)->toArray();

        // Loop through Top 3 Affiliates
        $dataset  = array();
        
        foreach($affiliate_result as $row => $innerArray){
            $datavalue = array();
            $lead_query = new \Admin\Model\Reports\ChartData($this->getAdapter());
            $lead_resultset = $lead_query->getLeadsByAffiliate($innerArray["label"]);
            $lead_result = $lead_query->initialize($lead_resultset)->toArray();
//var_dump($lead_result);
            foreach($lead_result as $row){
                $datavalue[] = array(
                                 'value' => $row['value']                               
                            );
            }

            $dataset[] = array(
                                 'seriesname' => $innerArray["label"],
                                 'data' => $datavalue                         
                            );            

        }

        $date_query = new \Admin\Model\Reports\ChartData($this->getAdapter());
        $date_resultset = $date_query->getLastSevenDays();
        $date_result = $date_query->initialize($date_resultset)->toArray();

        $chart = array("caption" => "Top Affiliates for Last 7 Days", "xAxisName" => "Affiliate ID", "yAxisName" => "Leads");
        $_output = array(
                        'chart' => $chart,
                        'categories' => array(array( 'category' => $date_result )),
                        'dataset' => $dataset
                        );
                        
        $viewModel->setVariable('response',\Zend\Json\Json::encode($_output));

        return $viewModel;

    }

    private function getOrderInformation($order_id){

        $config = $this->getConfig();

        $query_params = $this->params()->fromPost();

        $params = array(
            'username'=>$config['limelight']['username'],
            'password'=>$config['limelight']['password'],
            'method'=>'order_view',
            'order_id'=>$order_id
        );

        $curl = new \Admin\Model\Curl($config['limelight']['url'] . $config['limelight']['membership_page'], http_build_query($params));
        $curl->setTimeout($config['curl_timeout']);
        $raw_response = $curl->post();
        $response = \Admin\Library\ParseString::UrlEncodedToArray($raw_response->getResult());

        return $response;
    }

    public function getLimelightInfoAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $config = $this->getConfig();

        $query_params = $this->params()->fromPost();

        $params = array(
            'username'=>$config['limelight']['username'],
            'password'=>$config['limelight']['password']
        );

        if(!empty($query_params)){
            foreach($query_params as $key=>$value){
                switch($key){
                    case 'method':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'customer_id':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'order_id':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'campaign_id':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'criteria':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'start_date':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'end_date':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'start_time':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'end_time':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'criteria':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'search_type':
                        $params = array_merge($params, array($key=>$value));
                        break;
                    case 'return_type':
                        $params = array_merge($params, array($key=>$value));
                        break;

                }
            }
        }


        $curl = new \Admin\Model\Curl($config['limelight']['url'] . $config['limelight']['membership_page'], http_build_query($params));
        $curl->setTimeout($config['curl_timeout']);
        $raw_response = $curl->post();
        $response = \Admin\Library\ParseString::UrlEncodedToArray($raw_response->getResult());

        //format the data here
        $partial = $this->getViewHelper()->get('partial');
        $partial_html = '';
        $status = 'success';
        //update status
        switch($response['response_code']){
            case 100:
                $status = 'success';
                break;
            case 333:
                $status = 'empty';
                break;
            default:
                $status = 'error';
                break;

        }


        switch($params['method']){
            case 'order_find':
               //echo 'response='.print_r($response, true);
                $partial_html = $partial('partial/limelight-order-list', array('result'=>$response));
                break;
            case 'customer_find':

                $partial_html = $partial('partial/limelight-customer-list', array('result'=>$response));
                break;
            case 'customer_view':
                $partial_html = $partial('partial/limelight-customer-info', array('result'=>$response));
                break;
            case 'order_view':
                //pull html template
                $partial_html = $partial('partial/limelight-order-info', array('result'=>$response));
                break;
        }

        $viewModel->setVariable('response', \Zend\Json\Json::encode(array('status'=>$status,'result'=>$partial_html)));

        return $viewModel;
    }

    public function testUpdateAction(){
        $viewModel = new ViewModel();
        $viewModel->setTemplate('admin/ajax/response');
        $viewModel->setTerminal(true);

        $session = $this->getRepository('Admin\Model\Entity\Sessions')->findBy(array('device_id'=>59));

        if(!empty($session)){

            foreach($session as $key=>$row){
                if($row instanceof \Admin\Model\Entity\Sessions){
                    try{
                        $row->setPrivilege('PAID');
                        $row->setUpdatedDate(new \DateTime());
                        $this->getEntityManager()->flush();

                    }catch (Exception $e){
                        echo 'with error='.print_r($e, true);
                        die();
                    }

                }else{
                    echo 'not an session object'.print_r($row, true)."\r\n";
                    die();
                }
            }


        }else{
            echo 'No REsult found';
        }


        $viewModel->setVariable('response', \Zend\Json\Json::encode(array('status'=>'success','result'=>print_r($session,true))));

        return $viewModel;
    }
	
} 