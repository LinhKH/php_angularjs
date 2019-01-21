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

use Model_Service_Util;
use Model_Base_Core;
use Model_Service_Upload;
use Exception;


class Controller_Bookstore_Product extends Controller_Base_Rest
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
    
    public function post_allProduct()
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
            $arrQueryTotal->where('del_flg',0);
            $arrTotal = $arrQueryTotal->execute()->current();

            $arrData = ['total' => 0, 'list' => []];
            $rowNum = $arrTotal['total'];

            if ($rowNum > 0) {
              $iTemPerPage = empty($arrInput['itemperpage']) ? (int)10 : (int) $arrInput['itemperpage'];
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
    public function post_getProductById()
    {        
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');

            $val->add_field('id', 'itemperpage', []);

            if (!$val->run()) {
                $this->resp(null, 3000, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();

            $arrList = DB::select_array(['tbl_product.*', 'tbl_category.catName','tbl_brand.brandName'])
            ->from('tbl_product')
            ->join('tbl_category','LEFT')
            ->on('tbl_product.productId','=','tbl_category.catId')
            ->and_on('tbl_category.del_flg','=',DB::expr('0'))

            ->join('tbl_brand','LEFT')
            ->on('tbl_product.brandId','=','tbl_brand.brandId')
            ->and_on('tbl_brand.del_flg','=',DB::expr('0'))
            

            ->where('tbl_product.productId', $arrInput['id']);

            $arrResult = $arrList->execute()->current();

            if ($arrResult === false) {
                throw new Exception();
            }
            
            $this->resp(null, null, $arrResult);
                        
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        
        return $this->response($this->resp);
    }
    public function post_getProductByCate()
    {        
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');

            $val->add_field('catid', 'catid', []);

            if (!$val->run()) {
                $this->resp(null, 3000, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();

            $arrList = DB::select_array(['tbl_product.*'])
            ->from('tbl_product')
            ->where('tbl_product.catId', $arrInput['catid'])
            ->where('tbl_product.del_flg', 0);

            $arrResult = $arrList->execute()->as_array();

            if ($arrResult === false) {
                throw new Exception();
            }
            
            $this->resp(null, null, $arrResult);
                        
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        
        return $this->response($this->resp);
    }

    public function post_updateCart() {
      try {
        $val = Validation::forge();
        $val->add_callable('MyRules');

        $val->add_field('data', 'data', []);

        if (!$val->run()) {
            $this->resp(null, 3000, $val->error_message());
            return $this->response($this->resp);
        }

        $arrInput = $val->validated();
        // var_dump($arrInput);die;




      } catch (Exception $e) {
        Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
        $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
        $this->resp($msg, $code);
      }      
    }
}