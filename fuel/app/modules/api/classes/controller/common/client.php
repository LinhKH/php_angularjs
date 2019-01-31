<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Model_Service_Util;
use Model_TClientBase;
use Fuel\Core\Config;
use Fuel\Core\Lang;
use Fuel\Core\DB;
use Fuel\Core\Log;
use Fuel\Core\Validation;
use Fuel\Core\Input;
use Fuel\Core\Date;
use Api\Exception\ExceptionCode;
use Exception;
use Elastic;

class Controller_Common_Client extends Controller_Base_Rest
{

    public $arrFields = [
        'pic' => [
            'id' => 'mem_editor_id',
            'dt' => 'mem_edit_start_dt'
        ],
        'base' => [
            'id' => 'editor_id',
            'dt' => 'edit_start_dt'
        ]
    ];

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


    public function get_getDetailClientPic()
    {

        try {
            $getParam = Input::get();
            $conditions['del_flg'] = 0;
            if ($getParam) {
                foreach ($getParam as $c => $v) {
                    $conditions[$c] = $v;
                }
            }
            $clientPicLists = Model_Base_Core::getOne('Model_TClientBasePic', [
                    'select' => ['id', 'pic_nm', 'sex_cd', 'position_cd', 'call_name_cd', 'app_ok_type_cd', 'tel_no_num_only', 'management_no'],
                    'where' => $conditions,
                    'order_by' => ['management_no' => 'desc'],
            ]);
            $this->resp(null, null, $clientPicLists);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    public function get_getDataByZipCode()
    {
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

    public function post_getApokinZumi()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('cust_id', Lang::get('label.cust_id'), 'required|valid_field[Model_TClientBase,id]');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrApoZumi = [];
            $cust_id = $val->validated('cust_id');
            // select all tel
            $sql = "SELECT t1.tel_no_num_only, t2.inquire_tel_no
                    FROM t_client_base t1
                    JOIN t_client_base_line t2 ON t1.id = t2.cust_id AND t2.del_flg=0
                    WHERE t1.del_flg=0 AND t1.id=$cust_id";
            $arrData = DB::query($sql)->execute()->as_array();
            $arrTel = [];
            if (!empty($arrData) && is_array($arrData)) {
                foreach ($arrData as $k => $data) {
                    if ($k = 0) {
                        $arrTel[] = $data['tel_no_num_only'];
                    }
                    if (!empty($data['inquire_tel_no'])) {
                        $arrTel[] = $data['inquire_tel_no'];
                    }
                }
                $tel = '"' . implode('","', $arrTel) . '"';
                $arrApoZumi = Model_Base_Core::queryAll([
                        'select' => '
                            t1.id, t1.cust_id, t1.unit_cd,
                            DATE_FORMAT(t1.last_contact_create_dt, "%Y/%m/%d") AS last_contact_create_dt,
                            DATE_FORMAT(t1.last_contact_create_time, "%H:%i:%s") AS last_contact_create_time,
                            t1.last_contact_result_cd,
                            DATE_FORMAT(t1.re_contact_dt, "%Y/%m/%d") AS re_contact_dt,
                            t1.re_contact_time, t1.expected_cnt,
                            t2.sale_item_cd, t2.create_time
                        ',
                        'from' => 't_apo_zumi t2',
                        'join' => 'JOIN t_list_user t1 ON t2.list_user_id = t1.id AND t1.del_flg=0',
                        'where' => "t2.del_flg=0 AND t2.tel_no_num_only IN ($tel)",
                        'group_by' => 't2.list_user_id,t2.create_time'
                ]);

                if (!empty($arrApoZumi)) {
                    Config::load('call_list', true);
                    $mappingUnitCd = Config::get('call_list.mapping_unit_cd');
                    $arrType = [];
                    foreach ($mappingUnitCd as $k => $mapping) {
                        $arrType[$k] = $mapping['value'];
                    }
                    foreach ($arrApoZumi as $k => $apoZumi) {
                        $arrApoZumi[$k]['unit_cd'] = !empty($arrType[$apoZumi['unit_cd']]) ? $arrType[$apoZumi['unit_cd']] : null;
                        $arrApoZumi[$k]['last_contact_create_dt'] = $apoZumi['last_contact_create_dt'] != '0000/00/00' ? $apoZumi['last_contact_create_dt'] : null;
                        $arrApoZumi[$k]['last_contact_create_time'] = $apoZumi['last_contact_create_time'] != '00:00:00' ? $apoZumi['last_contact_create_time'] : null;
                        $arrApoZumi[$k]['re_contact_dt'] = $apoZumi['re_contact_dt'] != '0000/00/00' ? $apoZumi['re_contact_dt'] : null;
                    }
                }
            }

            $this->resp(null, null, $arrApoZumi);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

}
