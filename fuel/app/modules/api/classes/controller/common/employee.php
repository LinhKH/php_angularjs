<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Fuel\Core\Lang;
use Fuel\Core\Input;
use Api\Exception\ExceptionCode;
use Exception;
use Fuel\Core\Log;
use Model_Base_Employee;
use Fuel\Core\Validation;

class Controller_Common_Employee extends Controller_Base_Rest
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
     * 
     * @return type
     */
    public function post_getEmployee() {
        try {
            $arrList = \DB::select_array([
              'id','emp_id','agency_cd', \DB::expr("CAST(AES_DECRYPT(emp_nm, '".encrypt_key."') as char(200) ) as emp_nm")              
            ]);
            $arrList->from('m_employee');
            $arrList->where('del_flg' ,'=', 0);
            $arrList->order_by('id');

            $result = $arrList->execute()->as_array();
            $this->resp(null, null, $result);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage(). '/' . $e->getLine();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }

}
