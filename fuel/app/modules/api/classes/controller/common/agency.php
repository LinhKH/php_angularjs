<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Fuel\Core\Lang;
use Fuel\Core\Input;
use Api\Exception\ExceptionCode;
use Exception;
use Fuel\Core\Log;

class Controller_Common_Agency extends Controller_Base_Rest
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

    public function post_getAgency() {
        try {
            $result = Model_Base_Core::queryAll([
                    'select' => 'id,agency_cd,agency_nm',
                    'from' => 'm_agency',
                    'where' => 'del_flg = 0',
                    'order_by' => 'id'
            ]);
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
