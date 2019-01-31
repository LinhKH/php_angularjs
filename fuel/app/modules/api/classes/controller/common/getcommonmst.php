<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Fuel\Core\Lang;
use Fuel\Core\Input;
use Api\Exception\ExceptionCode;
use Exception;
use Fuel\Core\Log;

class Controller_Common_GetCommonMst extends Controller_Base_Rest
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

    public function post_index()
    {
        try {
            $mstId = (array) Input::post('mst_id');
            $where = 'del_flg=0';
            if (!empty($mstId)) {
                $where .= " AND mst_id IN ('" . implode("','", $mstId) . "')";
            }
            $arrData = Model_Base_Core::queryAll([
                    'select' => 'id,mst_id,mst_nm,code_value,disp_value',
                    'from' => 'm_common_mst',
                    'where' => $where,
                    'order_by' => 'mst_id,sort_value'
            ]);
            if ($arrData === false) {
                throw new Exception();
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
