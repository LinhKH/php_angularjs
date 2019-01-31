<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Fuel\Core\Log;
use Fuel\Core\DB;
use Fuel\Core\Lang;
use Fuel\Core\Validation;
use Fuel\Core\Input;
use Fuel\Core\Upload;
use Api\Exception\ExceptionCode;
use Exception;
use Model_Service_Upload;

class Controller_Master_Document extends Controller_Base_Rest
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
            
            $val->add_field('doc_name', Lang::get('label.doc_name'), []);
            $val->add_field('doc_filename', Lang::get('label.doc_filename'), []);
            
            $val->add_field('itemperpage', Lang::get('label.itemperpage'), []);
            $val->add_field('page', Lang::get('label.page'), []);
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            
            $arrQuery = [
                'select' => ['id', 'doc_name','doc_filename','url','update_time'],
                'where' => [['del_flg' => 0]],
                'order_by' => ['id' => 'DESC']
            ];
            if (!empty($arrInput['doc_name'])) {
                $arrQuery['where'][] = ['doc_name', 'LIKE', "%{$arrInput['doc_name']}%"];
            }
            if (!empty($arrInput['doc_filename'])) {
                $arrQuery['where'][] = ['doc_filename', 'LIKE', "%{$arrInput['doc_filename']}%"];
            }
            
            $arrData = ['total' => 0, 'list' => []];
            $rowNum = Model_Base_Core::getRowNum('Model_MDocument', $arrQuery['where']);
            
            if ($rowNum > 0) {

                if(isset($arrInput['itemperpage']) && $arrInput['itemperpage'] != 'all'){
                    $iTemPerPage = empty($arrInput['itemperpage']) ? _DEFAULT_LIMIT_ : (int) $arrInput['itemperpage'];
                    $iPage = empty($arrInput['page']) ? 1 : (int) $arrInput['page'];
                    $arrQuery['limit'] = $iTemPerPage;
                    $arrQuery['offset'] = ($iPage - 1) * $iTemPerPage;
                }
                
                $arrList = Model_Base_Core::getAll('Model_MDocument', $arrQuery);
                if ($arrList === false) {
                    throw new Exception();
                }

                foreach ($arrList as &$row) {
                    if(isset($row['update_time'])){
                        $row['update_time'] = \Model_Service_Util::convertDateTimeFormat($row['update_time'],'Y-m-d H:i:s','Y/m/d H:i:s');
                    }
                    $sUrlDownload = '';
                    if(!empty($row['url'])){
                        $sUrlDownload = \Uri::base() . str_replace([DOCROOT,'\\'], ['','/'], DOCUMENT_FILE_PATH.DS.$row['url']);
                    }
                    $row['doc_url'] = $sUrlDownload;
                    
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
                $arrData = Model_Base_Core::getOne('Model_MDocument', [
                        'select' => ['id','doc_name','doc_filename','update_time'],
                        'where' => [['del_flg' => 0], ['id', '=', $id]]
                ]);
                if ($arrData === false) {
                    throw new Exception();
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
            Lang::load('validation', true);
            $param = Input::post();
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('id', Lang::get('label.id'), []);
            $val->add_field('doc_name', Lang::get('label.doc_name'), 'required');
            
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();

            if(empty($arrInput['id'])){
                // add
                if(empty($_FILES)){
                    $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, 
                        ['doc_filename' => Lang::get('validation.required',[':label' => Lang::get('label.doc_filename')])]
                    );
                    return $this->response($this->resp);
                }else{
                    
                    $Path = DOCUMENT_FILE_PATH;
                    if (!file_exists($Path)) {
                        Model_Service_Upload::create_folder($Path);
                    }
                    
                    $config = ['path' => $Path, 'randomize' => true, 'ext_whitelist' => ['pdf','doc','docx','xls','xlsx'], 'max_size'=> MAX_UPLOAD_SIZE];
                    $upload_checked = Model_Service_Upload::check_upload($config);
                    
                    if ($upload_checked !== true) {
                        $msg = empty($upload_checked['message']) ? Lang::get('exception_msg.' . $upload_checked['code']) : $upload_checked['message'];
                        throw new Exception($msg, $upload_checked['code']);
                    }
                    
                    $infoFile = Upload::get_files();
                    if(!empty($infoFile)){
                        $infoFile = $infoFile[0];
                    }

                    //validate file name
                    $oldFile = Model_Base_Core::getOne('Model_MDocument',[
                        'select' => ['id'],
                        'where' => [['del_flg','=',0],['doc_filename','=',trim($infoFile['name'])]]
                    ]);
                    if(!empty($oldFile)){
                        $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, 
                            ['doc_filename' => Lang::get('validation.unique_field_v2',[':label' => Lang::get('label.doc_filename')])]
                        );
                        return $this->response($this->resp);
                    }
                    
                    $arrInsert = [
                        'doc_name' => $arrInput['doc_name'],
                        'doc_filename' => $infoFile['name'],
                        'url' => $infoFile['saved_as']
                    ];
                    if (!Model_Base_Core::insert('Model_MDocument', $arrInsert)) {
                        throw new Exception();
                    }
                }
            }else{
                // update
                $arrUpdate = [
                    'doc_name' => $arrInput['doc_name']
                ];
                if (!Model_Base_Core::update('Model_MDocument', $arrInput['id'], $arrUpdate)) {
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
            $val->add_field('id', Lang::get('label.id'), 'required|valid_field[Model_MDocument,id]');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            if (!Model_Base_Core::update('Model_MDocument', $arrInput['id'], ['del_flg' => 1])) {
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
