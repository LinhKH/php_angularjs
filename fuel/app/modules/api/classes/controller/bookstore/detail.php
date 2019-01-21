<?php

namespace Api;

use Controller_Base_Rest;
use Fuel\Core\Log;
use Fuel\Core\Lang;
use Fuel\Core\Input;
use Fuel\Core\DB;
use Fuel\Core\Validation;
use Fuel\Core\Str;
use Fuel\Core\Uri;
use Fuel\Core\Date;
use Fuel\Core\Config;
use Api\Exception\ExceptionCode;
use Model_Base_Kanyuken;
use Model_Service_Util;
use Model_Base_Core;
use Model_Service_Upload;
use Model_Base_KanyukenTa;
use Model_Base_KanGadgetNtt;
use Elastic;
use Exception;


class Controller_Bookstore_Detail extends Controller_Base_Rest
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
    //         $this->resp('E_APP_ERROR_PERMISSION', 5000);
    //         return $this->response($this->resp);
    //     }
    //     parent::router($resource, $arguments);
    // }
    
    public function post_featureProduct()
    {        
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');

            $val->add_field('itemperpage', 'itemperpage', []);
            $val->add_field('page', 'page', []);

            if (!$val->run()) {
                $this->resp(null, 3000, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            $arrWhere = [];
            $arrWhere[] = ['feature','=',1];
            
            // total 
            $arrQueryTotal = DB::select_array([
              DB::expr("COUNT(productId) as total")
            ]);
            $arrQueryTotal->from('tbl_product');
            $arrQueryTotal->where($arrWhere);
            $arrTotal = $arrQueryTotal->execute()->current();

            $arrData = ['total' => 0, 'list' => []];
            $rowNum = $arrTotal['total'];

            if ($rowNum > 0) {
              $iTemPerPage = empty($arrInput['itemperpage']) ? (int)4 : (int) $arrInput['itemperpage'];
              $iPage = empty($arrInput['page']) ? 1 : (int) $arrInput['page'];
              $arrList = DB::select_array(['*']);
              $arrList->from('tbl_product');
              $arrList->where($arrWhere);
              $arrList->limit($iTemPerPage)->offset(($iPage - 1) * $iTemPerPage);
              $arrResult = $arrList->execute()->as_array();

              if ($arrList === false) {
                  throw new Exception();
              }
              $arrData['total'] = $rowNum;
              $arrData['list'] = $arrResult;
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
    public function post_newProduct()
    {        
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');

            $val->add_field('itemperpage', 'itemperpage', []);
            $val->add_field('page', 'page', []);

            if (!$val->run()) {
                $this->resp(null, 3000, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            $arrWhere = [];
            
            // total 
            $arrQueryTotal = DB::select_array([
              DB::expr("COUNT(productId) as total")
            ]);
            $arrQueryTotal->from('tbl_product');
            $arrQueryTotal->where($arrWhere);
            $arrTotal = $arrQueryTotal->execute()->current();

            $arrData = ['total' => 0, 'list' => []];
            $rowNum = $arrTotal['total'];

            if ($rowNum > 0) {
              $iTemPerPage = empty($arrInput['itemperpage']) ? (int)4 : (int) $arrInput['itemperpage'];
              $iCurrentPage = empty($arrInput['page']) ? 1 : (int) $arrInput['page'];
              $arrList = DB::select_array(['*']);
              $arrList->from('tbl_product');
              $arrList->where($arrWhere);
              $arrList->limit($iTemPerPage)->offset(($iCurrentPage - 1) * $iTemPerPage);
              $arrList->order_by('productId','DESC');
              $arrResult = $arrList->execute()->as_array();

              if ($arrList === false) {
                  throw new Exception();
              }
              $arrData['total'] = $rowNum;
              $arrData['list'] = $arrResult;
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
   
}