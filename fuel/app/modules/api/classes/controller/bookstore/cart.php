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


class Controller_Bookstore_Cart extends Controller_Base_Rest
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
    
    public function post_getAllCart() {
        try {            
            $arrList = DB::select_array(['*']);
            $arrList->from('tbl_cart');
            $arrList->where('del_flg',0);
            $arrList->order_by('cartId','DESC');
            $arrResult = $arrList->execute()->as_array();

            if ($arrList === false) {
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
        $arrData = $arrInput['data'];
        $arrCartUpdate = [
          'sId' => isset($arrData['sId']) ? $arrData['sId'] : null,
          'productId' => isset($arrData['productId']) ? $arrData['productId'] : null,
          'productName' => isset($arrData['productName']) ? $arrData['productName'] : null,
          'price' => isset($arrData['price']) ? $arrData['price'] : null,
          'quantity' => isset($arrData['quantity']) ? $arrData['quantity'] : null,
          'price' => isset($arrData['price']) ? $arrData['price'] : null,
        ];

        if(!empty($arrData['cartId'])) {
          if (!Model_Base_Core::update('Model_TblCart', $arrData['cartId'], $arrCartUpdate)) {
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

    public function post_deleteCart() {
      try {
        $val = Validation::forge();
        $val->add_callable('MyRules');

        $val->add_field('id', 'id', []);

        if (!$val->run()) {
            $this->resp(null, 3000, $val->error_message());
            return $this->response($this->resp);
        }

        $arrInput = $val->validated();
        if (!Model_Base_Core::delete('Model_TblCart', $arrInput['id'])) {
          throw new Exception();
        }
        $this->resp();
      } catch(Exception $e) {
        Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
        $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
        $this->resp($msg, $code);
      }
      return $this->response($this->resp);
    }
}