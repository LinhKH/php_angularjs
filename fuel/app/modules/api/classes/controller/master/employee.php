<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Fuel\Core\Log;
use Fuel\Core\Lang;
use Fuel\Core\Validation;
use Fuel\Core\Input;
use Fuel\Core\DB;
use Api\Exception\ExceptionCode;
use Exception;

class Controller_Master_Employee extends Controller_Base_Rest
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
    //         $this->resp(Lang::get('exception_msg.' . ExceptionCode::E_APP_ERROR_PERMISSION), ExceptionCode::E_APP_ERROR_PERMISSION);
    //         return $this->response($this->resp);
    //     }
    //     $user = $this->user;
    //     if($user['system_role_cd'] < 50) {
    //         $this->resp(Lang::get('exception_msg.' . ExceptionCode::E_NOT_HAVE_PERMISSION), ExceptionCode::E_NOT_HAVE_PERMISSION);
    //         return $this->response($this->resp);
    //     }
    //     parent::router($resource, $arguments);
    // }

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function post_list()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            
            $val->add_field('emp_id', Lang::get('label.emp_id'), []);
            $val->add_field('emp_nm', Lang::get('label.emp_nm'), []);
            $val->add_field('emp_kana_nm', Lang::get('label.emp_kana_nm'), []);
            $val->add_field('agency_cd', Lang::get('label.agency_cd'), []);
            $val->add_field('system_role_cd', Lang::get('label.system_role_cd'), []);
            $val->add_field('user_id', Lang::get('label.user_id'), []);
            $val->add_field('email', Lang::get('label.email'), []);
            $val->add_field('active', Lang::get('label.active'), []);
            
            $val->add_field('itemperpage', Lang::get('label.itemperpage'), []);
            $val->add_field('page', Lang::get('label.page'), []);
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();

            $arrWhere = [];
            if (!empty($arrInput['emp_id'])) {
              $arrWhere[]  = ['emp_id', 'LIKE', "%{$arrInput['emp_id']}%"];
            }
            if (!empty($arrInput['emp_nm'])) {
              $arrWhere[]  = [DB::expr("AES_DECRYPT(emp_nm, '".encrypt_key."')"), 'LIKE', "%{$arrInput['emp_nm']}%"];
            }
            if (!is_null($arrInput['emp_kana_nm']) && $arrInput['emp_kana_nm'] !== '') {
              $arrWhere[]  = [DB::expr("AES_DECRYPT(emp_kana_nm, '".encrypt_key."')"), 'LIKE', "%{$arrInput['emp_kana_nm']}%"];
            }
            if (!empty($arrInput['agency_cd'])) {
              $arrWhere[]  = ['agency_cd', '=', "{$arrInput['agency_cd']}"];
            }
            if (!empty($arrInput['system_role_cd'])) {
              $arrWhere[]  = ['system_role_cd', '=', "{$arrInput['system_role_cd']}"];
            }
            if (!empty($arrInput['user_id'])) {
              $arrWhere[]  = ['user_id', 'LIKE', "%{$arrInput['user_id']}%"];
            }
            if (!empty($arrInput['email'])) {
              $arrWhere[]  = [DB::expr("AES_DECRYPT(email, '".encrypt_key."')"), 'LIKE', "%{$arrInput['email']}%"];
            }
            if (!empty($arrInput['active'])) {
              $arrWhere[]  = ['active', '=', "{$arrInput['active']}"];
            }
            $arrWhere[]  = ['del_flg', '=', 0];

            // total 
            $arrQueryTotal = DB::select_array([
                DB::expr("COUNT(id) as total")
            ]);
            $arrQueryTotal->from('m_employee');
            $arrQueryTotal->where($arrWhere);
            $arrTotal = $arrQueryTotal->execute()->current();

            $arrData = ['total' => 0, 'list' => []];
            $rowNum = $arrTotal['total'];
            
            if ($rowNum > 0) {
                $iTemPerPage = empty($arrInput['itemperpage']) ? _DEFAULT_LIMIT_ : (int) $arrInput['itemperpage'];
                $iPage = empty($arrInput['page']) ? 1 : (int) $arrInput['page'];
                $arrList = DB::select_array([
                    'id','emp_id', DB::expr("CAST(AES_DECRYPT(emp_nm, '".encrypt_key."') as char(200) ) as emp_nm"),
                    DB::expr("CAST(AES_DECRYPT(emp_kana_nm, '".encrypt_key."') as char(200) ) as emp_kana_nm"), 'user_id',
                    DB::expr("CAST(AES_DECRYPT(email, '".encrypt_key."') as char(200) ) as email"), 
                    'active', 'system_role_cd'
                ]);
                $arrList->from('m_employee');
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
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage(). ':' . $e->getLine();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function get_detail()
    {
        try {
            $arrData = [];
            $id = Input::get('id');
            if (!empty($id)) {
              $arrList = DB::select_array([
                'id','emp_id', DB::expr("CAST(AES_DECRYPT(emp_nm, '".encrypt_key."') as char(200) ) as emp_nm"),
                DB::expr("CAST(AES_DECRYPT(emp_kana_nm, '".encrypt_key."') as char(200) ) as emp_kana_nm"), 'user_id',
                DB::expr("CAST(AES_DECRYPT(email, '".encrypt_key."') as char(200) ) as email"), 
                'active', 'system_role_cd','emp_type_cd','aff_dept_cd','agency_cd','position_cd'
              ]);
              $arrList->from('m_employee');
              $arrList->where('del_flg' ,'=', 0);
              $arrList->where('id' ,'=', $id);


              $arrData = $arrList->execute()->current();
                if ($arrData === false) {
                    throw new Exception();
                }
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

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function post_upsert()
    {
        try {
            $param = Input::post();
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('id', Lang::get('label.id'), 'valid_field[Model_MEmployee,id]');
            // $val->add_field('emp_id', Lang::get('label.emp_id'), 'required|max_length[6]|unique_field_v2[Model_MEmployee,emp_id,id,' . Input::param('id') . ']');
            $val->add_field('emp_nm', Lang::get('label.emp_nm'), 'required|max_length[50]');
            $val->add_field('emp_kana_nm', Lang::get('label.emp_kana_nm'), []);
            // $val->add_field('emp_type_cd', Lang::get('label.emp_type_cd'), []);
            // $val->add_field('aff_dept_cd', Lang::get('label.aff_dept_cd'), []);
            $val->add_field('system_role_cd', Lang::get('label.system_role_cd'), 'required|max_length[2]');
            $val->add_field('agency_cd', Lang::get('label.agency_cd'), []);
            // $val->add_field('position_cd', Lang::get('label.position_cd'), []);
            $val->add_field('user_id', Lang::get('label.user_id'), 'required|unique_field_v2[Model_MEmployee,user_id,id,' . Input::param('id') . ']');
            $val->add_field('email', Lang::get('label.email'), 'required|valid_email|unique_field_v2[Model_MEmployee,email,id,' . Input::param('id') . ']');
            $val->add_field('active', Lang::get('label.active'), 'required');
            
            if(!isset($param['id'])) {
                $val->add_field('password', Lang::get('label.password'), 'required|valid_password');
            }
            
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();            

            $arrInput['emp_nm'] = DB::expr("AES_ENCRYPT(".DB::quote($arrInput['emp_nm']).", '".encrypt_key."')");
            $arrInput['emp_kana_nm'] = DB::expr("AES_ENCRYPT(".DB::quote($arrInput['emp_kana_nm']).", '".encrypt_key."')");
            $arrInput['email'] = DB::expr("AES_ENCRYPT(".DB::quote($arrInput['email']).", '".encrypt_key."')");

            if (!empty($arrInput['id'])) {
                $arrInput['emp_id'] = str_pad($arrInput['id'], 6, '0', STR_PAD_LEFT);
                if (!Model_Base_Core::update('Model_MEmployee', $arrInput['id'], $arrInput)) {
                    throw new Exception();
                }
            } else {
                if (isset($arrInput['id'])) {
                    unset($arrInput['id']);
                }
                $arrInput['emp_id'] = 999999;
                $arrInput['password'] = \Model_Service_Util::hash_password($arrInput['password']);
                if (!$lastestId = Model_Base_Core::insert('Model_MEmployee', $arrInput)) {
                    throw new Exception();
                }
                if(!empty($lastestId)) {
                  if (!Model_Base_Core::update('Model_MEmployee', $lastestId, ['emp_id' => str_pad($lastestId, 6, '0', STR_PAD_LEFT)])) {
                    throw new Exception();
                  }
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

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function post_resetPassword()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('id', Lang::get('label.id'), 'required|valid_field[Model_MEmployee,id]');
            $val->add_field('password', Lang::get('label.password'), 'required|valid_password');
            $val->add_field('password_confirm', Lang::get('label.password_confirm'), 'match_field[password]');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            $password = \Model_Service_Util::hash_password($arrInput['password']);
            
            if (!Model_Base_Core::update('Model_MEmployee', $arrInput['id'], ['password' => $password])) {
                throw new Exception();
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

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function post_delete()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('id', Lang::get('label.id'), 'required|valid_field[Model_MEmployee,id]');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            if (!Model_Base_Core::update('Model_MEmployee', $arrInput['id'], ['del_flg' => 1])) {
                throw new Exception();
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

}
