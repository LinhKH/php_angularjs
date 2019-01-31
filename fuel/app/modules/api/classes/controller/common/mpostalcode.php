<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Fuel\Core\Lang;
use Fuel\Core\Input;
use Api\Exception\ExceptionCode;
use Exception;
use Fuel\Core\Log;

class Controller_Common_Mpostalcode extends Controller_Base_Rest
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

    public function get_getDataByZipCode(){
        try {
            $zipcode = Input::get('zipcode');
            if (!empty($zipcode)) {
                $client = Model_Base_Core::getOne('Model_MPostalCode', [
                        'select' => ['id', 'zip_cd', 'pref_cd', 'city_nm', 'town_area'],
                        'where' => [
                            'del_flg' => 0,
                            'zip_cd' => $zipcode,
                        ],
                ]);
                if ($client === false) {
                    throw new Exception();
                }
                if (!empty($client)) {
                    $pref = Model_Base_Core::getOne('Model_MPrefecture', [
                            'select' => ['pref_nm'],
                            'where' => [
                                'del_flg' => 0,
                                'pref_cd' => $client['pref_cd'],
                            ],
                    ]);
                    if ($pref === false) {
                        throw new Exception();
                    }
                    $client['pref_nm'] = empty($pref['pref_nm']) ? '' : $pref['pref_nm'];
                }
                $this->resp(null, null, $client);
            }
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

}
