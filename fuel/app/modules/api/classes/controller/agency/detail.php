<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Fuel\Core\DB;
use Fuel\Core\Log;
use Fuel\Core\Lang;
use Fuel\Core\Validation;
use Fuel\Core\Input;
use Fuel\Core\Config;
use Api\Exception\ExceptionCode;
use Fuel\Core\Date;
use Exception;

class Controller_Agency_Detail extends Controller_Base_Rest
{

    public function before()
    {
        parent::before();
    }

    public function after($response)
    {
        $response = parent::after($response);
        return $response;
    }

    public function router($resource, $arguments)
    {
        if (!$this->is_login) {
            $this->resp(Lang::get('exception_msg.' . ExceptionCode::E_APP_ERROR_PERMISSION), ExceptionCode::E_APP_ERROR_PERMISSION);
            return $this->response($this->resp);
        }
        parent::router($resource, $arguments);
    }
    
    public function get_agencyName() {
        try {
            $agencyName = DB::select_array(['id','agency_cd','agency_nm'])
            ->from(DB::expr('m_agency'))            
            ->where('del_flg', '=', 0)
            ->execute()->as_array();

            $this->resp(null, null, $agencyName);
        } catch(Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    public function get_getDevice() {
        try {
            $getDevice = DB::select_array(['id','maker_nm','type_cd','product_nm','model_nm'])
            ->from(DB::expr('m_oa_device'))  
            ->group_by('maker_nm')          
            ->where('del_flg', '=', 0)
            ->execute()->as_array();

            $this->resp(null, null, $getDevice);
        } catch(Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    public function get_agencyTypeCd() {
        try {
            $getTypeCd = DB::select_array(['id','maker_nm','type_cd'])
            ->from(DB::expr('m_oa_device'))  
            ->group_by('type_cd','maker_nm')                    
            ->where('del_flg', '=', 0)
            ->execute()->as_array();
            $this->resp(null, null, $getTypeCd);
        } catch(Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    public function get_agencyProductNm() {
        try {
            $getProductNm = DB::select_array(['id','type_cd','product_nm'])
            ->from(DB::expr('m_oa_device'))  
            ->group_by('product_nm','type_cd')                     
            ->where('del_flg', '=', 0)
            ->execute()->as_array();
            $this->resp(null, null, $getProductNm);
        } catch(Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    public function get_agencyModelNm() {
        try {
            $getModelNm = DB::select_array([DB::expr('id AS machine_id'),'product_nm','model_nm'])
            ->from(DB::expr('m_oa_device'))  
            ->group_by('model_nm','product_nm')          
            ->where('del_flg', '=', 0)
            ->execute()->as_array();
            $this->resp(null, null, $getModelNm);
        } catch(Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    public function post_uploadOrderMngFile() {
        try {
            $sPath = UPLOAD_TEMP_PATH;
            \Model_Service_Upload::create_folder($sPath);
            $config = ['path' => $sPath, 'randomize' => true, 'ext_whitelist' => ['pdf', 'png','jpeg','jpg'], 'max_size'=> MAX_UPLOAD_SIZE];
            $upload_checked = \Model_Service_Upload::check_upload($config);
            if ($upload_checked !== true) {
                $msg = empty($upload_checked['message']) ? Lang::get('exception_msg.' . $upload_checked['code']) : $upload_checked['message'];
                throw new Exception($msg, $upload_checked['code']);
            }
            $itemDetail = [];
            foreach (\Upload::get_files() as $file) {        
                $itemDetail[] = [
                            'file_nm'=> $file['name'],
                            'file_path' => $file['saved_as'],
                            'url_preview' => \Uri::base(). "files/upload_temp/".$file['saved_as'],                            
                        ];
            }  
            $this->resp($itemDetail, null, null);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    public function post_uploadInspect() {
        try {
            $id = Input::post('id');
            $sPath = UPLOAD_TEMP_PATH;
            \Model_Service_Upload::create_folder($sPath);
            $config = ['path' => $sPath, 'randomize' => true, 'ext_whitelist' => ['pdf', 'png','jpeg','jpg'], 'max_size'=> MAX_UPLOAD_SIZE];
            $upload_checked = \Model_Service_Upload::check_upload($config);
            if ($upload_checked !== true) {
                $msg = empty($upload_checked['message']) ? Lang::get('exception_msg.' . $upload_checked['code']) : $upload_checked['message'];
                throw new Exception($msg, $upload_checked['code']);
            }
            $itemDetail = [];
            foreach (\Upload::get_files() as $file) {        
                $itemDetail[] = [
                            'file_nm'=> $file['name'],
                            'file_path' => $file['saved_as'],
                            'url_preview' => \Uri::base(). "files/upload_temp/".$file['saved_as'],
                            
                        ];
            }   
            $this->resp($itemDetail, null, null);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    public function post_uploadAccept() {
        try {
            $id = Input::post('id');            
            $sPath = UPLOAD_TEMP_PATH;
            \Model_Service_Upload::create_folder($sPath);
            $config = ['path' => $sPath, 'randomize' => true, 'ext_whitelist' => ['pdf', 'png','jpeg','jpg'], 'max_size'=> MAX_UPLOAD_SIZE];
            $upload_checked = \Model_Service_Upload::check_upload($config);
            if ($upload_checked !== true) {
                $msg = empty($upload_checked['message']) ? Lang::get('exception_msg.' . $upload_checked['code']) : $upload_checked['message'];
                throw new Exception($msg, $upload_checked['code']);
            }
            $itemDetail = [];
            foreach (\Upload::get_files() as $file) {        
                $itemDetail[] = [
                            'file_nm'=> $file['name'],
                            'file_path' => $file['saved_as'],
                            'url_preview' => \Uri::base(). "files/upload_temp/".$file['saved_as'],
                            
                        ];
            }  
            $this->resp($itemDetail, null, null);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    
    // disable comment
    public function post_disableCommentOfOrderMng() {
        try {
            $id = Input::post('id');
            if (!Model_Base_Core::update('Model_TComment', $id, ['disable_flg' => '1'])) {
                throw new Exception();
            }
            $this->resp(null, null, []);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    
    // insert new comment
    public function post_insertCommentOfOrderMng() {
        try {
            $orderMngId = Input::post('order_mng_id');
            $content = Input::post('content');     
            $comment = array(
                'order_mng_id' => $orderMngId,
                'content' => $content
            );            
            Model_Base_Core::insert('Model_TComment', $comment);  
            
            // Send mail
            if ($this->user['system_role_cd'] < 20) {                
                
                $arrParamMail = DB::select_array([
                    't_order_mng.id', 'm_agency.agency_nm'
                    , DB::expr("CAST(AES_DECRYPT(t_order_mng.company_name, '".encrypt_key."') as char(200) ) as company_name")
                ])
                ->from('t_order_mng')
                ->join('m_agency','LEFT')
                ->on('t_order_mng.agency_cd','=','m_agency.agency_cd')
                ->where('t_order_mng.id', '=', $orderMngId)
                ->where('t_order_mng.del_flg', '=', 0)
                ->execute()->current();  

                // SEND MAIL 

                $arrDataMail = [
                    'company_name' => $arrParamMail['company_name'],
                    'agency' => (!empty($arrParamMail['agency_nm'])) ? $arrParamMail['agency_nm'] : null,
                    'order_id' => $orderMngId
                ];
               
                if(\Model_Base_Agency::send_mail_add_comment($arrDataMail) === false){
                    throw new Exception();
                }
                
            } // End send mail            
            
            $this->resp(null, null, []);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    
    // get all comments of this ordermng
    public function post_getCommentOfOrderMng() {
        try {
            $orderMngId = Input::post('order_mng_id');
            
            $arrComments = DB::select_array([
                't_comment.cmt_id', 't_comment.content', 't_comment.disable_flg', 't_comment.create_user_id'
                , DB::expr("DATE_FORMAT(t_comment.create_time,'%Y/%m/%d %h:%i:%s') AS create_time")
                , DB::expr("CAST(AES_DECRYPT(m_employee.emp_nm, '".encrypt_key."') as char(200) ) as create_emp_nm"),
            ])
            ->from('t_comment')
            ->join('m_employee','LEFT')
            ->on('t_comment.create_user_id','=','m_employee.emp_id')
            ->where('t_comment.order_mng_id', '=', $orderMngId)
            ->where('t_comment.del_flg', '=', 0)
            ->order_by('t_comment.create_time','DESC')
            ->execute()->as_array();   
            
            $this->resp(null, null, $arrComments);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    
    public function get_detail() {
        try {            
            $orderMngId = Input::get('order_mng_id');
            $userRole = $this->user;
            $agencyLogged = $userRole['agency_cd'];
            
            $dataOrderMng = Model_Base_Core::getOne('Model_TOrderMng', [
                    'select' => ['id','agency_cd'],
                    'where' => [
                        'del_flg' => 0,
                        'id' => $orderMngId,
                    ],
            ]);

            if(($agencyLogged != $dataOrderMng['agency_cd']) && ($userRole['system_role_cd'] < 20)) {
                $this->resp(null,[]);
                return $this->response($this->resp);
            }

            $orderMng = DB::select_array([
               'id','req_dt',
               DB::expr("CAST(AES_DECRYPT(company_name, '".encrypt_key."') as char(200) ) as company_name"),
               DB::expr("CAST(AES_DECRYPT(address_other, '".encrypt_key."') as char(200) ) as address_other"),
               DB::expr("CAST(AES_DECRYPT(rep_nm, '".encrypt_key."') as char(200) ) as rep_nm"),
               DB::expr("CAST(AES_DECRYPT(tel, '".encrypt_key."') as char(200) ) as tel"),
               'agency_cd','emp_id',
               'zip_cd','pref_cd','state',
               'note','contract_type_cd','prj_sts_flg',
               DB::expr("DATE_FORMAT(accept_dt,'%Y/%m/%d') AS accept_dt"),
               DB::expr("DATE_FORMAT(order_dt,'%Y/%m/%d') AS order_dt"),
               'doc_sts_flg','doc_fubi_desc',
               DB::expr("DATE_FORMAT(app_ok_date,'%Y/%m/%d') AS app_ok_date"),
               DB::expr("DATE_FORMAT(doc_rtn_dt,'%Y/%m/%d') AS doc_rtn_dt"),
               DB::expr("DATE_FORMAT(doc_re_arrv_dt,'%Y/%m/%d') AS doc_re_arrv_dt"),
               'inspect_sts',
               DB::expr("DATE_FORMAT(vis_origin_arrv_dt,'%Y/%m/%d') AS vis_origin_arrv_dt"),
               'warranty_collect_sts_cd'
               ,'accessories_note', 'memo',
               DB::expr("DATE_FORMAT(target_dt,'%Y/%m') AS target_dt"),
               'confirmed_lease_comp_nm','apo_sent_flg'
            ])
            ->from(DB::expr('t_order_mng AS order_mng'))
            ->where('order_mng.id', '=', $orderMngId)
            ->where('order_mng.del_flg', '=', 0)
            ->execute()->current();
            
            if ($orderMng === false) {
                throw new Exception();
            }

            if(!empty($orderMng) && !empty($orderMng['id'])) {
                $orderMngFile = $this->getOrderMngFile($orderMng['id']);
                $buildingDetal = $this->getTBuildingDetail($orderMng['id']);
                $LeaseMng = $this->getLeaseMng($orderMng['id']);
                $WorkMng = $this->getWorkMng($orderMng['id']);
            }
            
            if(!empty($orderMngFile)) {
                $orderMng['order_mng_file'] = $orderMngFile;     
            } else {
                $orderMng['order_mng_file'] = [];
            }           
            if(!empty($buildingDetal)) {
                $orderMng['building_detail'] = $buildingDetal;     
            } else {
                $orderMng['building_detail'] = [];
            }
            if(!empty($LeaseMng)) {
                $orderMng['lease_mng'] = $LeaseMng;     
            } else {
                $orderMng['lease_mng'] = [];
            } 
            if(!empty($WorkMng)) {
                $orderMng['work_mng'] = $WorkMng;     
            } else {
                $orderMng['work_mng'] = []; 
            }              
            
            $this->resp(null, null, ['order_mng' => $orderMng]);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    public function post_update() {
        try {
            DB::start_transaction();
            
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('order_mng_id', Lang::get('label.order_mng_id'), []);
            $val->add_field('order_mng', Lang::get('label.order_mng'), []);
            
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }
            
            $arrFileUpload = [];
            $arrInput = $val->validated();  
            $orderMngId = $arrInput['order_mng_id'];
            $userRole = $this->user;
            $agencyLogged = $userRole['agency_cd'];
            
            $dataOrderMng = Model_Base_Core::getOne('Model_TOrderMng', [
                    'select' => ['id','agency_cd'],
                    'where' => [
                        'del_flg' => 0,
                        'id' => $orderMngId,
                    ],
            ]);
            if(($agencyLogged != $dataOrderMng['agency_cd']) && ($userRole['system_role_cd'] < 20)) {
                throw new Exception(Lang::get('exception_msg.' . ExceptionCode::E_NOT_HAVE_PERMISSION));
            }
          
            $oaConstructFileNM = !empty($arrInput['order_mng']['order_mng_file']) ? $arrInput['order_mng']['order_mng_file'] : null;
            $buidingDetail = !empty($arrInput['order_mng']['building_detail']) ? $arrInput['order_mng']['building_detail'] : null;  
            $orderMng = $arrInput['order_mng'];    
                   
            $leaseMng = !empty($arrInput['order_mng']['lease_mng']) ? $arrInput['order_mng']['lease_mng'] : null; 
                       
            $workMng = !empty($arrInput['order_mng']['work_mng']) ? $arrInput['order_mng']['work_mng'] : null; 
            
            if($workMng) {
                $arrworkMngPluck = \Arr::pluck($workMng, 'id');
            }
        

            /* Update order_mng*/  
            $itemOrderMng = [
                'company_name' => isset($orderMng['company_name']) ? DB::expr("AES_ENCRYPT(".DB::quote($orderMng['company_name']).", '".encrypt_key."')")  : null,
                'agency_cd' => isset($orderMng['agency_cd']) ? $orderMng['agency_cd'] : null,
                'emp_id' => isset($orderMng['emp_id']) ? $orderMng['emp_id'] : null,
                'zip_cd' => isset($orderMng['zip_cd']) ? $orderMng['zip_cd'] : null,
                'pref_cd' => isset($orderMng['pref_cd']) ? $orderMng['pref_cd'] : null,
                'state' => isset($orderMng['state']) ? $orderMng['state'] : null,
                'address_other' => isset($orderMng['address_other']) ? DB::expr("AES_ENCRYPT(".DB::quote($orderMng['address_other']).", '".encrypt_key."')")  : null,
                'rep_nm' => isset($orderMng['rep_nm']) ? DB::expr("AES_ENCRYPT(".DB::quote($orderMng['rep_nm']).", '".encrypt_key."')")  : null,
                'tel' => isset($orderMng['tel']) ? DB::expr("AES_ENCRYPT(".DB::quote($orderMng['tel']).", '".encrypt_key."')")  : null,
                'note' => isset($orderMng['note']) ? $orderMng['note'] : null,
                'contract_type_cd' => isset($orderMng['contract_type_cd']) ? $orderMng['contract_type_cd'] : null,
                'prj_sts_flg' => isset($orderMng['prj_sts_flg']) ? $orderMng['prj_sts_flg'] : null,
                'accept_dt' => !empty($orderMng['accept_dt']) ? $orderMng['accept_dt'] : null,
                'order_dt' => !empty($orderMng['order_dt']) ? $orderMng['order_dt'] : null,
                'doc_sts_flg' => isset($orderMng['doc_sts_flg']) ? $orderMng['doc_sts_flg'] : null,
                'doc_fubi_desc' => isset($orderMng['doc_fubi_desc']) ? $orderMng['doc_fubi_desc'] : null,
                'app_ok_date' => !empty($orderMng['app_ok_date']) ? $orderMng['app_ok_date'] : null,
                'doc_rtn_dt' => !empty($orderMng['doc_rtn_dt']) ? $orderMng['doc_rtn_dt'] : null,
                'doc_re_arrv_dt' => !empty($orderMng['doc_re_arrv_dt']) ? $orderMng['doc_re_arrv_dt'] : null,
                'inspect_sts' => isset($orderMng['inspect_sts']) ? $orderMng['inspect_sts'] : null,
                'vis_origin_arrv_dt' => !empty($orderMng['vis_origin_arrv_dt']) ? $orderMng['vis_origin_arrv_dt'] : null,
                'warranty_collect_sts_cd' => isset($orderMng['warranty_collect_sts_cd']) ? $orderMng['warranty_collect_sts_cd'] : null,
                'accessories_note' => isset($orderMng['accessories_note']) ? $orderMng['accessories_note'] : null,
                'target_dt' => !empty($orderMng['target_dt']) ? $orderMng['target_dt'].'/01' : null,   
                'confirmed_lease_comp_nm' => isset($orderMng['confirmed_lease_comp_nm']) ? $orderMng['confirmed_lease_comp_nm'] : null,  
                'memo' => isset($orderMng['memo']) ? $orderMng['memo'] : null,           
            ];

            $delCoditionRole = ['agency_cd','contract_type_cd','prj_sts_flg','accept_dt','order_dt','target_dt','doc_sts_flg','app_ok_date','doc_rtn_dt'
                                ,'doc_re_arrv_dt','vis_origin_arrv_dt','inspect_sts','warranty_collect_sts_cd','confirmed_lease_comp_nm'
                                ,'doc_fubi_desc','accessories_note'
                            ];  
                                
            if($userRole['system_role_cd'] < 20) {
                foreach($delCoditionRole as $col) {
                    unset($itemOrderMng[$col]);
                }
            }
            $itemDetail['del_flg'] = 0;
            if (!Model_Base_Core::update('Model_TOrderMng', $orderMngId, $itemOrderMng)) {
                throw new Exception();
            };
            /**Update Order_Mng_File */  
            if (!Model_Base_Core::deleteByWhere(
                'Model_TOrderMngFile',['order_mng_id' => $orderMngId])) {
                throw new Exception();
            }          
            if($oaConstructFileNM != null) {
                foreach ($oaConstructFileNM as $key => $filename) {
                    
                    $itemDetail = [
                        'file_nm'=> $filename['file_nm'],
                        'file_path' => $filename['file_path'],
                        'order_mng_id' => isset($filename['order_mng_id']) ? $filename['order_mng_id'] : $orderMngId,                      
                    ];
                    if(!isset($filename['id']) || $filename['id'] == '' || $filename['id'] == null ) {
                        # Insert to DB and move file to new directory 
                        $oldFile = UPLOAD_TEMP_PATH.DS. $filename['file_path'];
                        $spath = ORDER_FILE_PATH;
                        \Model_Service_Upload::create_folder($spath);
                        $newFile = $spath.DS. $filename['file_path'];
                        if($filename['file_path'] != "") {
                            if (!copy($oldFile,$newFile)) {
                                throw new Exception();
                            }
                            unlink($oldFile);
                        }
                        
                        if($filename['file_nm'] != "") {
                            if (!Model_Base_Core::insert('Model_TOrderMngFile', $itemDetail)) {
                                throw new Exception();
                            }
                        }
                    } else {
                        # Update to DB
                        $itemDetail['del_flg'] = 0;
                        if (!Model_Base_Core::update('Model_TOrderMngFile', $filename['id'], $itemDetail)) {
                            throw new Exception();
                        }
                    }

                    // push to list file, use for send mail
                    array_push($arrFileUpload,$itemDetail['file_nm']);
                }
            }


            /** Update Building_detail */

            if($this->user['system_role_cd'] >= 20){
                if (!Model_Base_Core::deleteByWhere(
                    'Model_TBuildingDetail',['order_mng_id' => $orderMngId])) {
                    throw new Exception();
                }
                if($buidingDetail != null) {
                    
                    foreach ($buidingDetail as $key => $filename) {
                        $itemBuilding = [   
                            'order_mng_id' => $orderMngId,                
                            'machine_id'=> (isset($filename['machine_id']) && $filename['machine_id'] != '') ? $filename['machine_id'] : null,
                            'item_amnt' => (isset($filename['item_amnt']) && $filename['item_amnt'] != '') ? $filename['item_amnt'] : null,
                        ];
                        if(!isset($filename['id']) || $filename['id'] == '' || $filename['id'] == null ) {
                            # Insert tabel t_building_detail
                            if (!$idTWorkMng = Model_Base_Core::insert('Model_TBuildingDetail', $itemBuilding)) {
                                throw new Exception();
                            };                        
                            
                        } else { 
                            # Update tabel t_building_detail
                            $itemBuilding['del_flg'] = 0;
                            if (!Model_Base_Core::update('Model_TBuildingDetail', $filename['id'], $itemBuilding)) {
                                throw new Exception();
                            };
                        }
                        
                    }
                }
            }
        

            /** Update Lease_Mng */ 

            if($this->user['system_role_cd'] >= 20){
                if (!Model_Base_Core::deleteByWhere(
                    'Model_TLeaseMng',['order_mng_id' => $orderMngId])) {
                    throw new Exception();
                }           
                if($leaseMng != null) {
                    foreach ($leaseMng as $key => $filename) {
                        
                        $itemDetailLease = [                        
                            'inspect_doc_nm'=> isset($filename['inspect_doc_nm']) ? $filename['inspect_doc_nm'] : null,
                            'inspect_doc_path' => isset($filename['inspect_doc_path']) ? $filename['inspect_doc_path'] : null,
                            'accept_doc_nm'=> isset($filename['accept_doc_nm']) ? $filename['accept_doc_nm'] : null,
                            'accept_doc_path' => isset($filename['accept_doc_path']) ? $filename['accept_doc_path'] : null,
                            'order_mng_id' => isset($filename['order_mng_id']) ? $filename['order_mng_id'] : null,
                            'lease_comp_cd' => isset($filename['lease_comp_cd']) ? $filename['lease_comp_cd'] : null,
                            'lease_cost_mm' => (isset($filename['lease_cost_mm']) && $filename['lease_cost_mm'] != '') ? $filename['lease_cost_mm'] : null,
                            'lease_period_cd' => isset($filename['lease_period_cd']) ? $filename['lease_period_cd'] : null,
                            'utm_lease_rate' => (isset($filename['utm_lease_rate']) && $filename['utm_lease_rate'] != '')  ? $filename['utm_lease_rate'] : null,
                            'inspect_recv_dt' => !empty($filename['inspect_recv_dt']) ? $filename['inspect_recv_dt'] : null,
                            'inspect_sts_flg' => isset($filename['inspect_sts_flg']) ? $filename['inspect_sts_flg'] : null,
                            'inspect_approve_dt' => !empty($filename['inspect_approve_dt']) ? $filename['inspect_approve_dt'] : null,
                            'inspect_approve_flg' => isset($filename['inspect_approve_flg']) ? $filename['inspect_approve_flg'] : null,
                            'inspect_determine_dt' => !empty($filename['inspect_determine_dt']) ? $filename['inspect_determine_dt'] : null,
                            'inspect_note' => isset($filename['inspect_note']) ? $filename['inspect_note'] : null,
                            'accept_sts_flg' => isset($filename['accept_sts_flg']) ? $filename['accept_sts_flg'] : null,
                            'accept_recv_dt' => !empty($filename['accept_recv_dt']) ? $filename['accept_recv_dt'] : null,
                            'accept_end_dt' => !empty($filename['accept_end_dt']) ? $filename['accept_end_dt'] : null,
                            'accept_end_flg' => isset($filename['accept_end_flg']) ? $filename['accept_end_flg'] : null,
                            'accept_note' => isset($filename['accept_note']) ? $filename['accept_note'] : null,                                            
                        ];
                        
                        if(!isset($filename['id']) || $filename['id'] == '' || $filename['id'] == null ) {
                            # Insert to DB and move file to new directory 
                            $this->getArrItemFile($itemDetailLease);
                            if (!Model_Base_Core::insert('Model_TLeaseMng', $itemDetailLease)) {
                                throw new Exception();
                            };                       
                        } else {
                            # Insert to DB and move file to new directory 
                            $this->getArrItemFile($itemDetailLease);
                            $leaseData = \Model_Base_Core::getOne('Model_TLeaseMng', [
                                'select' => ['id','inspect_doc_path','accept_doc_path'],
                                'where' => ['id' , '=',  $filename['id']],
                            ]);
                            
                            
                            # Update to DB
                            $itemDetailLease['del_flg'] = 0;
                            if (!Model_Base_Core::update('Model_TLeaseMng', $filename['id'], $itemDetailLease)) {
                                throw new Exception();
                            };
                            
                            if($leaseData['inspect_doc_path'] != $itemDetailLease['inspect_doc_path'] && $leaseData['inspect_doc_path'] != '') {
                                unlink(LEASE_MNG_PATH.DS. $leaseData['inspect_doc_path']);
                            }
                            if($leaseData['accept_doc_path'] != $itemDetailLease['accept_doc_path'] && $leaseData['accept_doc_path'] != '') {
                                unlink(LEASE_MNG_PATH.DS. $leaseData['accept_doc_path']);
                            }
                        }
    
                        // push to list file, use for send mail
                        array_push($arrFileUpload,$itemDetailLease['inspect_doc_nm']);
                        array_push($arrFileUpload,$itemDetailLease['accept_doc_nm']);
                    }
                }
            }

            
            /** Update Work */

            if($this->user['system_role_cd'] >= 20){
                if(!empty($arrworkMngPluck)) {
                    DB::update('t_order_detail')->value("del_flg", 1)
                    ->where('work_order_id', 'IN', $arrworkMngPluck)
                    ->where('order_mng_id',$orderMngId)
                    ->execute();
                }
                    
                if (!Model_Base_Core::deleteByWhere(
                    'Model_TWorkMng',['order_mng_id' => $orderMngId])) {
                    throw new Exception();
                }
                if($workMng != null) {
                    foreach ($workMng as $key => $filename) {
                        $itemWorkMng = [
                            'order_mng_id'=> isset($filename['order_mng_id']) ? $filename['order_mng_id'] : null,
                            'inst_place_nm' => isset($filename['inst_place_nm']) ? DB::expr("AES_ENCRYPT(".DB::quote($filename['inst_place_nm']).", '".encrypt_key."')")  : null,
                            'inst_zip_cd'=> isset($filename['inst_zip_cd']) ? $filename['inst_zip_cd'] : null,
                            'inst_pref_cd'=> isset($filename['inst_pref_cd']) ? $filename['inst_pref_cd'] : null,
                            'inst_city_nm'=> isset($filename['inst_city_nm']) ? $filename['inst_city_nm'] : null,
                            'insta_tel_no' => isset($filename['insta_tel_no']) ? DB::expr("AES_ENCRYPT(".DB::quote($filename['insta_tel_no']).", '".encrypt_key."')")  : null,
                            'inst_chrgp_nm' => isset($filename['inst_chrgp_nm']) ? DB::expr("AES_ENCRYPT(".DB::quote($filename['inst_chrgp_nm']).", '".encrypt_key."')")  : null,
                            'work_candi_dt'=> !empty($filename['work_candi_dt']) ? $filename['work_candi_dt'] : null,
                            'work_plan_time_from'=> !empty($filename['work_plan_time_from']) ? $filename['work_plan_time_from'] : null,
                            'work_plan_time_to'=> !empty($filename['work_plan_time_to']) ? $filename['work_plan_time_to'] : null,
                            'work_confirmed_dt'=> !empty($filename['work_confirmed_dt']) ? $filename['work_confirmed_dt'] : null,
                            'work_confirmed_time_from'=> !empty($filename['work_confirmed_time_from']) ? $filename['work_confirmed_time_from'] : null,
                            'work_confirmed_time_to'=> !empty($filename['work_confirmed_time_to']) ? $filename['work_confirmed_time_to'] : null,
                            'work_plan_constructor'=> isset($filename['work_plan_constructor']) ? $filename['work_plan_constructor'] : null,
                            'work_sts_flg'=> isset($filename['work_sts_flg']) ? $filename['work_sts_flg'] : null,
                            'work_end_dt'=> !empty($filename['work_end_dt']) ? $filename['work_end_dt'] : null,
                            'work_note'=> isset($filename['work_note']) ? $filename['work_note'] : null,
                            'inst_address' => isset($filename['inst_address']) ? DB::expr("AES_ENCRYPT(".DB::quote($filename['inst_address']).", '".encrypt_key."')")  : null,
                            
                        ];
                        if(!isset($filename['id']) || $filename['id'] == '' || $filename['id'] == null ) {
                            # Insert tabel t_work_mng
                            if (!$idTWorkMng = Model_Base_Core::insert('Model_TWorkMng', $itemWorkMng)) {
                                throw new Exception();
                            };                        
                            
                        } else { 
                            # Update tabel t_work_mng
                            $itemWorkMng['del_flg'] = 0;
                            if (!Model_Base_Core::update('Model_TWorkMng', $filename['id'], $itemWorkMng)) {
                                throw new Exception();
                            };
                        }
                        # Process t_order_detail             
                        foreach($filename['order_detail'] as $item) { 
                            $arrItemOrderDetail = [
                                'order_mng_id' => $orderMngId,
                                'work_order_id' => isset($idTWorkMng) ? $idTWorkMng : $filename['id'],
                                'machine_id' => (isset($item['machine_id']) && $item['machine_id'] != '')  ? $item['machine_id'] : null,
                                'order_dt' => !empty($item['order_dt']) ? $item['order_dt'] : null,
                            ];                        
                            if(!isset($item['order_detail_id']) || $item['order_detail_id'] == '' || $item['order_detail_id'] == null ) {
                                if (!Model_Base_Core::insert('Model_TOrderDetail', $arrItemOrderDetail)) {
                                    throw new Exception();
                                }; 
                            } else {
                                $arrItemOrderDetail['del_flg'] = 0;
                                if (!Model_Base_Core::update('Model_TOrderDetail', $item['order_detail_id'], $arrItemOrderDetail)) {
                                    throw new Exception();
                                };
                            }
                        }
                    }
                }
            }
            
            // Send mail
            if ($this->user['system_role_cd'] < 20) {
                $dataAgency = Model_Base_Core::getOne('Model_MAgency', [
                    'select' => ['id','agency_nm'],
                    'where' => [
                        'del_flg' => 0,
                        'agency_cd' => isset($orderMng['agency_cd']) ? $orderMng['agency_cd'] : null,
                    ],
                ]);

                $arrDataMail = [
                    'company_name' => $orderMng['company_name'],
                    'agency' => (!empty($dataAgency) && !empty($dataAgency['agency_nm'])) ? $dataAgency['agency_nm'] : null,
                    'order_id' => $orderMngId,
                    'update_type' => '更新',
                ];
               
                if(\Model_Base_Agency::send_mail($arrDataMail) === false){
                    throw new Exception();
                }
            }
            
            $this->resp();
            DB::commit_transaction();
        } catch (Exception $e) {
            DB::rollback_transaction();
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    public function getArrItemFile($itemDetailLease = []) {
        # Insert to DB and move file to new directory    
        $spath = LEASE_MNG_PATH;
        \Model_Service_Upload::create_folder($spath);
        if($itemDetailLease['inspect_doc_path'] != "") {
            $oldFile_inspect = UPLOAD_TEMP_PATH.DS. $itemDetailLease['inspect_doc_path'];
            $newFile_inspect = $spath.DS. $itemDetailLease['inspect_doc_path'];
            # move inspect_doc_path from _temp folder to lease_mng folder              
            if(file_exists($oldFile_inspect)) {
                if (!copy($oldFile_inspect,$newFile_inspect)) {
                    throw new Exception();
                }
                unlink($oldFile_inspect);
            }
        }  
        if ($itemDetailLease['accept_doc_path'] !="") {
            $oldFile_accept = UPLOAD_TEMP_PATH.DS. $itemDetailLease['accept_doc_path']; 
            $newFile_accept = $spath.DS. $itemDetailLease['accept_doc_path'];
        
            # move accept_doc_path from _temp folder to lease_mng folder
            if(file_exists($oldFile_accept)) {
                if (!copy($oldFile_accept,$newFile_accept)) {
                    throw new Exception();
                }
                unlink($oldFile_accept);
            }
        }  
    }
    public function getOrderMngFile($iOrderMngId = 0) {                
        $arrOrderMngFile = DB::select_array([
                      't_order_mng_file.*','m_employee.emp_id',
                      DB::expr("CAST(AES_DECRYPT(m_employee.emp_nm, '".encrypt_key."') as char(200) ) as emp_nm"),
                ])
        ->from('t_order_mng_file')
        ->join('m_employee','LEFT')
        ->on('t_order_mng_file.create_user_id','=','m_employee.emp_id')
        ->where('order_mng_id', '=', $iOrderMngId)
        ->where('t_order_mng_file.del_flg', '=', 0)
        ->group_by('t_order_mng_file.id')
        ->execute()->as_array();
        
        if(!empty($arrOrderMngFile)) {
            foreach($arrOrderMngFile as $key => $fileName) {
                $arrOrderMngFile[$key]['url_preview'] = \Uri::base(). "files/upload/order_file_mng/".$fileName['file_path'];
           }
        }
        return $arrOrderMngFile;
       
    }
    public function getTBuildingDetail($iOrderMngId = 0) {
        $arrBuildingDetal = DB::select_array([
            't_building_detail.id','t_building_detail.order_mng_id','t_building_detail.machine_id',
            't_building_detail.item_amnt',
            DB::expr('m_oa_device.id as oa_device_id'),
            'm_oa_device.maker_nm','m_oa_device.type_cd','m_oa_device.product_nm','m_oa_device.model_nm'
            ])
        ->from('t_building_detail')
        ->join('m_oa_device','LEFT')
        ->on('t_building_detail.machine_id','=','m_oa_device.id')
        ->where('t_building_detail.order_mng_id', '=', $iOrderMngId)
        ->where('t_building_detail.del_flg', '=', 0)
        ->execute()->as_array();
        
        return $arrBuildingDetal;
    }
    public function getLeaseMng($iOrderMngId = 0) {
        $arrLeaseMng = DB::select_array([
            'id','order_mng_id','lease_comp_cd','lease_cost_mm','lease_period_cd','utm_lease_rate',
            DB::expr("DATE_FORMAT(inspect_recv_dt,'%Y/%m/%d') AS inspect_recv_dt"),
            'inspect_sts_flg',
            DB::expr("DATE_FORMAT(inspect_approve_dt,'%Y/%m/%d') AS inspect_approve_dt"),
            'inspect_approve_flg',
            DB::expr("DATE_FORMAT(inspect_determine_dt,'%Y/%m/%d') AS inspect_determine_dt"),
            'inspect_note','accept_sts_flg',
            DB::expr("DATE_FORMAT(accept_recv_dt,'%Y/%m/%d') AS accept_recv_dt"),
            DB::expr("DATE_FORMAT(accept_end_dt,'%Y/%m/%d') AS accept_end_dt"),
            'accept_end_flg','accept_note',
            'inspect_doc_nm','inspect_doc_path','accept_doc_nm','accept_doc_path','del_flg','create_user_id'
        ])
        ->from('t_lease_mng')
        ->where('order_mng_id', '=', $iOrderMngId)
        ->where('del_flg', '=', 0)
        ->execute()->as_array();
        if(!empty($arrLeaseMng)){
            foreach ($arrLeaseMng as &$row) {
                $row['url_preview_inspect'] = \Uri::base().'files/upload/lease_mng/'.$row['inspect_doc_path'];
                $row['url_preview_accept'] = \Uri::base().'files/upload/lease_mng/'.$row['accept_doc_path'];
            }
        }
        return $arrLeaseMng;
    }
    public function getWorkMng($iOrderMngId = 0) {
        $arrTempOrderDetail = [];
        $arrWorkMng = DB::select_array([
                'id','order_mng_id','work_note',
                DB::expr("CAST(AES_DECRYPT(inst_place_nm, '".encrypt_key."') as char(200) ) as inst_place_nm"),
                'inst_zip_cd','inst_pref_cd','inst_city_nm',
                DB::expr("CAST(AES_DECRYPT(insta_tel_no, '".encrypt_key."') as char(200) ) as insta_tel_no"),
                DB::expr("CAST(AES_DECRYPT(inst_chrgp_nm, '".encrypt_key."') as char(200) ) as inst_chrgp_nm"),
                DB::expr("DATE_FORMAT(work_candi_dt,'%Y/%m/%d') AS work_candi_dt"),
                DB::expr("DATE_FORMAT(work_plan_time_from,'%H:%i') AS work_plan_time_from"),
                DB::expr("DATE_FORMAT(work_plan_time_to,'%H:%i') AS work_plan_time_to"),
                DB::expr("DATE_FORMAT(work_confirmed_dt,'%Y/%m/%d') AS work_confirmed_dt"),
                DB::expr("DATE_FORMAT(work_confirmed_time_from,'%H:%i') AS work_confirmed_time_from"),
                DB::expr("DATE_FORMAT(work_confirmed_time_to,'%H:%i') AS work_confirmed_time_to"),
                'work_plan_constructor', 
                'work_sts_flg',
                DB::expr("DATE_FORMAT(work_end_dt,'%Y/%m/%d') AS work_end_dt"),
                DB::expr("CAST(AES_DECRYPT(inst_address, '".encrypt_key."') as char(200) ) as inst_address"),
            ])
            ->from('t_work_mng')
            ->where('order_mng_id', '=', $iOrderMngId)
            ->where('del_flg', '=', 0)
            ->execute()->as_array();                
        $arrOrderDetail = \Arr::pluck($arrWorkMng, 'id');        

        if (!empty($arrOrderDetail)) {
            $arrOrder_Detail = DB::select_array([
                DB::expr('order_detail.id as order_detail_id'),
                DB::expr('order_detail.work_order_id as work_order_id'),
                DB::expr('order_detail.order_mng_id as order_mng_id'),
                DB::expr('order_detail.machine_id as machine_id'),
                // DB::expr('order_detail.order_dt as order_dt'),
                DB::expr("DATE_FORMAT(order_detail.order_dt,'%Y/%m/%d') AS order_dt"),                
                'oa_device.maker_nm','oa_device.type_cd','oa_device.product_nm','oa_device.model_nm'
            ])
            ->from(DB::expr('t_order_detail AS order_detail'))
            ->join(DB::expr('m_oa_device AS oa_device'), 'LEFT')
            ->on('order_detail.machine_id','=','oa_device.id')
            ->where('order_detail.work_order_id', 'IN', $arrOrderDetail)
            ->where('order_detail.order_mng_id', '=', $iOrderMngId)
            ->where('order_detail.del_flg', '=', 0)
            ->execute()->as_array();            
            foreach ($arrOrder_Detail as $key => $item) {
                $arrTempOrderDetail[$item['work_order_id']] [] = $item;
            }
            
        }        
        foreach ($arrWorkMng as $index => $row) {
            $arrWorkMng[$index]['order_detail'] = isset($arrTempOrderDetail[$row['id']]) ? $arrTempOrderDetail[$row['id']] : [[
                'id' => null,
                'order_detail_id' => null,
                'work_order_id' => null,
                'machine_id' => null,
                'order_dt' => null
            ]];
        }
        return array_values($arrWorkMng);
    }

    // sendAPOCallSystem     
    public function post_sendAPOCallSystem() {
        try {
            $id = Input::post('orderMng');            
            $orderMng = DB::select_array([
               'om.id',
               DB::expr("CAST(AES_DECRYPT(om.company_name, '".encrypt_key."') as char(200) ) as company_name"),
               DB::expr("CAST(AES_DECRYPT(om.address_other, '".encrypt_key."') as char(200) ) as address_other"),
               DB::expr("CAST(AES_DECRYPT(om.rep_nm, '".encrypt_key."') as char(200) ) as rep_nm"),
               DB::expr("CAST(AES_DECRYPT(om.tel, '".encrypt_key."') as char(200) ) as tel"),               
               'om.zip_cd', 'pre.pref_nm', 'om.state', 'om.pref_cd'
            ])
            ->from(DB::expr('t_order_mng AS om'))
            ->join(DB::expr('m_prefecture AS pre'), 'LEFT')
            ->on('om.pref_cd','=','pre.pref_cd')
            ->where('om.id', '=', $id)
            ->where('om.del_flg', '=', 0)
            ->execute()->current();
            
            // Call API
            $config = Config::load('app', true);
            $url = $config['url_api_call_system'];
            // $url = "http://localhost/call_system/public/api/public/agencymngmt/"; 
            $method = "POST";
            $dataToServer = array(
                'visit_company_nm' => $orderMng['company_name'],
                'represents_nm' => $orderMng['rep_nm'],
                'zip_cd' => $orderMng['zip_cd'],
                'pref_cd' => $orderMng['pref_cd'],
                'city_nm' => $orderMng['state'],
                'address_3rd' => $orderMng['address_other'],
                'company_tel' => $orderMng['tel'],                
                'agency_mgnt_id' => $orderMng['id']
            );
            
            $result = $this->call_api($url, $method, $dataToServer);
            $result = json_decode($result);
            
            // Update t_order_mng.apo_sent_flg
            if (!Model_Base_Core::update('Model_TOrderMng', $orderMng['id'], ['apo_sent_flg'=>'1'])) {
                throw new Exception();
            }
            
            if(!isset($result->code) || $result->code != 200) {                
                throw new Exception("Call api fail, please try again later!");
            } else {
                $this->resp(null, null, []);
            }
            
            

        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode(). ':' . $e->getLine();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage(). ':' . $e->getLine();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    
    // call_api
    private function call_api($url, $method, $dataToServer)
    {
        try {
            $curl = curl_init();
            $headers = array('Authoration-Key: cCaMlIlNsOyIsStIeVm');

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $dataToServer,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            
            if($err) {                
                throw new Exception(var_dump($err));
            } else {   
                return $response;
            }   
            
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ );
            return false; 
        }
        
    }
}
