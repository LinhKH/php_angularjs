<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Fuel\Core\Log;
use Fuel\Core\Lang;
use Fuel\Core\Validation;
use Fuel\Core\Input;
use Api\Exception\ExceptionCode;
use Model_Service_Util;
use Exception;

class Controller_Master_Agency extends Controller_Base_Rest
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
        $user = $this->user;
        if($user['system_role_cd'] < 50) {
            $this->resp(Lang::get('exception_msg.' . ExceptionCode::E_NOT_HAVE_PERMISSION), ExceptionCode::E_NOT_HAVE_PERMISSION);
            return $this->response($this->resp);
        }
        parent::router($resource, $arguments);
    }

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function post_list()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('agency_cd', Lang::get('label.agency_cd'), []);
            $val->add_field('agency_nm', Lang::get('label.agency_nm'), []);
            
            $val->add_field('itemperpage', Lang::get('label.itemperpage'), []);
            $val->add_field('page', Lang::get('label.page'), []);
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();

            $arrQuery = [
                'select' => ['id', 'agency_cd', 'agency_nm','agency_doc_list'],
                'where' => [['del_flg' => 0]],
                'order_by' => ['id' => 'DESC']
            ];
            if (!empty($arrInput['agency_cd'])) {
                $arrQuery['where'][] = ['agency_cd', 'LIKE', "%{$arrInput['agency_cd']}%"];
            }
            if (!empty($arrInput['agency_nm'])) {
                $arrQuery['where'][] = ['agency_nm', 'LIKE', "%{$arrInput['agency_nm']}%"];
            }
            
            $arrData = ['total' => 0, 'list' => []];
            $rowNum = Model_Base_Core::getRowNum('Model_MAgency', $arrQuery['where']);
            
            if ($rowNum > 0) {
                $iTemPerPage = empty($arrInput['itemperpage']) ? _DEFAULT_LIMIT_ : (int) $arrInput['itemperpage'];
                $iPage = empty($arrInput['page']) ? 1 : (int) $arrInput['page'];
                $arrQuery['limit'] = $iTemPerPage;
                $arrQuery['offset'] = ($iPage - 1) * $iTemPerPage;
                $arrList = Model_Base_Core::getAll('Model_MAgency', $arrQuery);
                if ($arrList === false) {
                    throw new Exception();
                }

                // get list document 

                $arrDoc = Model_Base_Core::getAll('Model_MDocument', [
                    'select' => ['id','doc_name','doc_filename','url'],
                    'where' => [['del_flg','=',0]]
                ]);
                $arrDoc = Model_Service_Util::reindexArrBykey($arrDoc);

                foreach ($arrList as &$row) {
                    if(!empty($row['agency_doc_list'])){
                        $arrDocList = explode(',', $row['agency_doc_list']);
                        $row['agency_doc_list'] = $arrDocList;

                        foreach ($arrDocList as $doc) {
                            if(isset($arrDoc[$doc])){
                                $sUrlDownload = \Uri::base() . str_replace([DOCROOT,'\\'], ['','/'], DOCUMENT_FILE_PATH.DS.$arrDoc[$doc]['url']);
                                $row['list_file'][] = [
                                    'doc_filename' => $arrDoc[$doc]['doc_filename'],
                                    'doc_url' => $sUrlDownload,
                                ];
                            }
                        }
                    }
                }

                $arrData['total'] = $rowNum;
                $arrData['list'] = $arrList;
            }

            $this->resp(null, null, $arrData);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage(). ':' . $e->getLine();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function get_detail()
    {
        try {
            $arrData = [];
            $id = Input::get('id');
            if (!empty($id)) {
                $arrData = Model_Base_Core::getOne('Model_MAgency', [
                    'select' => ['id','agency_cd', 'agency_nm','agency_doc_list'],
                    'where' => [['del_flg' => 0], ['id', '=', $id]]
                ]);
                if ($arrData === false) {
                    throw new Exception();
                }

                if(!empty($arrData['agency_doc_list'])){
                    $arrData['agency_doc_list'] = explode(',', $arrData['agency_doc_list']);
                }
            }

            $this->resp(null, null, $arrData);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function post_upsert()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('id', Lang::get('label.id'), 'valid_field[Model_MAgency,id]');
            $val->add_field('agency_cd', Lang::get('label.agency_cd'), 'required|max_length[20]|unique_field_v2[Model_MAgency,agency_cd,id,' . Input::param('id') . ']');
            $val->add_field('agency_nm', Lang::get('label.agency_nm'), 'required|max_length[100]');
            $val->add_field('agency_doc_list', Lang::get('label.agency_doc_list'), []);
            
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            
            if(!empty($arrInput['agency_doc_list']) && is_array($arrInput['agency_doc_list'])){
                $arrInput['agency_doc_list'] = implode(',',$arrInput['agency_doc_list']);
            }else{
                $arrInput['agency_doc_list'] = null;
            }
            
            if (!empty($arrInput['id'])) {
                if (!Model_Base_Core::update('Model_MAgency', $arrInput['id'], $arrInput)) {
                    throw new Exception();
                }
            } else {
                if (isset($arrInput['id'])) {
                    unset($arrInput['id']);
                }
                if (!Model_Base_Core::insert('Model_MAgency', $arrInput)) {
                    throw new Exception();
                }
            }

            $this->resp();
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function post_delete()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('id', Lang::get('label.id'), 'required|valid_field[Model_MAgency,id]');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            if (!Model_Base_Core::update('Model_MAgency', $arrInput['id'], ['del_flg' => 1])) {
                throw new Exception();
            }

            $this->resp();
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

}
