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
use Fuel\Core\DB;
use Exception;
use Model_Base_Agency;
class Controller_Agency_Document extends Controller_Base_Rest
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

    // public function router($resource, $arguments)
    // {
    //     if (!$this->is_login) {
    //          $this->resp(Lang::get('exception_msg.' . ExceptionCode::E_APP_ERROR_PERMISSION), ExceptionCode::E_APP_ERROR_PERMISSION);
    //          return $this->response($this->resp);
    //      }
    //     parent::router($resource, $arguments);
    // }
    
    /**
     * @return type
     */
    public function post_list() {
        try {
            $arrInput = Input::post();

            $arrDocs = [];
            $arrWhere = [
                ['del_flg','=',0]
            ];
            $oQuery = DB::select_array(['id','doc_name','doc_filename','url',DB::expr("DATE_FORMAT(update_time,'%Y/%m/%d %H:%i:%s') AS update_time")])
            ->from(DB::expr('m_document'));

            if($this->user['system_role_cd'] < 20) {
                $arrWhere[] = [DB::expr('FIND_IN_SET(id,(SELECT agency_doc_list FROM m_agency WHERE del_flg=0 AND agency_cd ="'.$this->user['agency_cd'].'" LIMIT 1 ))'),null,null];
            }

            $iTemPerPage = empty($arrInput['pageSize']) ? _DEFAULT_LIMIT_ : (int) $arrInput['pageSize'];
            $iPage = empty($arrInput['currentPage']) ? 1 : (int) $arrInput['currentPage'];

            $oQuery->limit($iTemPerPage);
            $oQuery->offset(($iPage - 1) * $iTemPerPage);
            foreach ($arrWhere as $where) {
                $oQuery->where($where[0],$where[1],$where[2]);
            }
            
            $arrDocs = $oQuery ->execute()->as_array();

            foreach ($arrDocs as &$doc) {
                $sUrlDownload = \Uri::base() . str_replace([DOCROOT,'\\'], ['','/'], DOCUMENT_FILE_PATH.DS.$doc['url']);
                $doc['doc_url'] = $sUrlDownload;
            }

            $rowNum = Model_Base_Core::getRowNum('Model_MDocument', $arrWhere);
        
            $arrResult = [
                'totalItems' => $rowNum,
                'currentPage' => $iPage,
                'pageSize' => $iTemPerPage,
                'list' => $arrDocs
            ];
            $this->resp(null, null, $arrResult);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage(). '/' . $e->getLine();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
  
}
