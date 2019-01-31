<?php

namespace Api;

use Controller_Base_Rest;
use Fuel\Core\Upload;
use Model_Base_Core;
use Fuel\Core\Log;
use Fuel\Core\Lang;
use Fuel\Core\Validation;
use Fuel\Core\Input;
use Api\Exception\ExceptionCode;
use Fuel\Core\Date;
use Exception;
use Model_Base_Agency;
class Controller_Agency_Ichiran extends Controller_Base_Rest
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
    
    /**
     * User clicks "search"
     * @return type
     */
    public function post_searchOrderMng() {
        try {
            $param = Input::post();
            $userLogged = $this->user;
            if($userLogged['system_role_cd'] < 20) {
                $param['agency_cd'] = $userLogged['agency_cd']; 
            }
            $result = \Model_Base_OrderMng::searchOrderMng($param);
            $this->resp(null, null, $result);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage(). '/' . $e->getLine();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
  
    /**
     * Save new order: information and list file uploaded
     * @return type
     */
    public function post_saveOrder() {
        try {
            $val = \Validation::forge();
            $val->add_callable('MyRules');
            
            $val->add_field('agency_cd', Lang::get('label.agency_cd'), 'required');
            $val->add_field('emp_id', Lang::get('label.emp_id'), 'required');
            $val->add_field('company_name', Lang::get('label.company_name'), 'required');
            $val->add_field('tel', Lang::get('label.tel'), 'required');
            
            $val->add_field('pref_cd', Lang::get('label.pref_cd'), []);
            $val->add_field('note', Lang::get('label.note'), []);
            $val->add_field('zip_cd', Lang::get('label.zip_cd'), []);
            $val->add_field('address_other', Lang::get('label.address_other'), []);
            $val->add_field('state', Lang::get('label.state'), []);

            $val->add_field('listFileUpload', Lang::get('label.listFileUpload'), []);
            
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }
            
            $param = $val->validated();
            
            $userLogged = $this->user;
            if($userLogged['system_role_cd'] < 20) {
                $param['agency_cd'] = $userLogged['agency_cd']; 
            }
            
            $objOrder = array(
                'state' => isset($param['state'])? $param['state']: NULL,
                'pref_cd' => isset($param['pref_cd'])? $param['pref_cd']: NULL,
                'emp_id' => isset($param['emp_id'])? $param['emp_id']: NULL,
                'agency_cd' => isset($param['agency_cd'])? $param['agency_cd']: NULL,
                'note' => isset($param['note'])? $param['note']: NULL,
                'company_name' => isset($param['company_name'])? \DB::expr('AES_ENCRYPT("' . $param['company_name'] . '", "' . encrypt_key . '")') : NULL,
                'tel' => isset($param['tel'])? \DB::expr('AES_ENCRYPT("' . $param['tel'] . '", "' . encrypt_key . '")') : NULL,
                'zip_cd' => isset($param['zip_cd'])? $param['zip_cd']: NULL,
                'address_other' => isset($param['address_other'])? \DB::expr('AES_ENCRYPT("' . $param['address_other'] . '", "' . encrypt_key . '")') : NULL,
                'create_user_id' => $userLogged['emp_id'],
                'req_dt' => date('Y-m-d H:i:s', Date::forge()->get_timestamp())
            );
            
            \DB::start_transaction();
            $id = \Model_Base_Core::insert("Model_TOrderMng", $objOrder);
            
            $listFileUploaded = [];
            if($id && isset($param['listFileUpload']) && !empty($param['listFileUpload'])) {
                $listFileUploaded = $this->uploadFile();
            }
            
            if(!empty($listFileUploaded)) {
                foreach($listFileUploaded AS $file) {
                    $objFile = array(
                        'order_mng_id' => $id,
                        'file_nm' => $file['name'],
                        'file_path' => $file['saved_as'],
                        'create_user_id' => $userLogged['emp_id']
                    );
                    \Model_Base_Core::insert("Model_TOrderMngFile", $objFile);
                } 
            }
            

            if ($this->user['system_role_cd'] < 20) {

                // Send mail
                $dataAgency = Model_Base_Core::getOne('Model_MAgency', [
                    'select' => ['id','agency_nm'],
                    'where' => [
                        'del_flg' => 0,
                        'agency_cd' => isset($param['agency_cd'])? $param['agency_cd']: NULL,
                    ],
                ]);

                // Send mail
                $arrDataMail = [
                    'company_name' => isset($param['company_name']) ? $param['company_name'] : null,
                    'agency' => (!empty($dataAgency) && !empty($dataAgency['agency_nm'])) ? $dataAgency['agency_nm'] : null,
                    'order_id' => $id,
                    'update_type' => '登録',
                ];
               
                if(\Model_Base_Agency::send_mail($arrDataMail) === false){
                    throw new Exception();
                }
            }
            
            $this->resp(null, null, $id);
            \DB::commit_transaction();
        } catch (Exception $e) {
            \DB::rollback_transaction();
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    
    /**
     * Use in post_saveOrder
     * @return type
     * @throws Exception
     */
    public function uploadFile() {
        $sPath = ORDER_FILE_PATH;
        \Model_Service_Upload::create_folder($sPath);
        $config = [
            'path' => $sPath,
            'randomize' => true,
            'ext_whitelist' => [
                'png','jpeg','jpg','pdf'
                ],
            'max_size' => MAX_UPLOAD_SIZE // Max file upload 10 MB 
        ];
        $upload_checked = \Model_Service_Upload::check_upload($config);
        
        // Check error upload
        if (isset($upload_checked ['code'])) {
            // File uploaded is not IMAGE/PDF
            if ($upload_checked ['code'] == \Api\Exception\ExceptionCode::UPLOAD_ERR_EXT_NOT_WHITELISTED) {
                throw new Exception(Lang::get('exception_msg.' . 'MSG_UPLOAD_WRONG_FILE'), $upload_checked ['code']);
            }
            // Max file upload 10 MB 
            if ($upload_checked ['code'] == \Api\Exception\ExceptionCode::UPLOAD_ERR_MAX_SIZE) {
                throw new Exception($upload_checked['message'], $upload_checked ['code']);
            }
        }
        $arrFileInfo = [];
        foreach (\Upload::get_files() as $file) {
            $arrFileInfo[] = $file;
        }
        return $arrFileInfo;
    }


    public function post_delete(){

        try {

            if ($this->user['system_role_cd'] < 20) {
                throw new Exception(Lang::get('exception_msg.' . ExceptionCode::E_NOT_HAVE_PERMISSION), ExceptionCode::E_NOT_HAVE_PERMISSION);
            }

            Lang::load('validation', true);
            $arrInput = Input::post();
            $isCheckAll = isset($arrInput['check_all']) ? $arrInput['check_all'] : '0';
            $arrListIds = isset($arrInput['list_ids']) ? $arrInput['list_ids'] : [];
            $arrQuery = isset($arrInput['query_search']) ? $arrInput['query_search'] : [];

            if($isCheckAll != 'true' && empty($arrListIds)){
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, ['MSGA0005' => Lang::get('validation.MSGA0005')]);
                return $this->response($this->resp);
            }
            
            \DB::start_transaction();

            if(\Model_Base_OrderMng::deleteOrder($isCheckAll, $arrListIds, $arrQuery) === false){
                throw new Exception();
            }

            $this->resp(null, null, null);
            \DB::commit_transaction();
        } catch (Exception $e) {
            \DB::rollback_transaction();
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }

}
