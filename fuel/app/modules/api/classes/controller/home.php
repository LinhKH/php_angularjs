<?php

namespace Api;

use Controller_Base_Rest, Fuel\Core\Lang, Api\Exception\ExceptionCode;

class Controller_Home extends Controller_Base_Rest
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
        parent::router($resource, $arguments);
        if (!$this->is_login) {
            $this->resp(Lang::get('exception_msg.' . ExceptionCode::E_APP_ERROR_PERMISSION), ExceptionCode::E_APP_ERROR_PERMISSION);
            return $this->response($this->resp);
        }
    }

    public function get_not_found()
    {
        $this->resp(Lang::get('exception_msg.' . ExceptionCode::E_NOT_FOUND), ExceptionCode::E_NOT_FOUND);
        return $this->response($this->resp);
    }

}
