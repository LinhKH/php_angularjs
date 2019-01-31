<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Model_Base_User;
use Model_Service_Mail;
use Model_Service_Util;
use Fuel\Core\Log, Fuel\Core\Lang, Fuel\Core\Validation, Api\Exception\ExceptionCode;
use Exception;
use Email\Email;

class Controller_Auth extends Controller_Base_Rest
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
    }

    public function post_login()
    {
        try {
            $userIdLabel = Lang::get('label.user_id');
            $passwordLabel = Lang::get('label.password');
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('user_id', $userIdLabel, 'required|min_length[2]|max_length[255]|valid_user_id');
            $val->add_field('password', $passwordLabel, 'required|max_length[50]');
            if (!$val->run()) {
               $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
                // throw new Exception(Lang::get('exception_msg.' . 'LOGIN_FAIL'), ExceptionCode::E_VALIDATION_ERROR_FIELD);
            }
            
            $userId = $val->validated('user_id');
            $password = $val->validated('password');            
            if (!Model_Base_User::user_login($userId, $password)) {
                // throw new Exception(Lang::get($this->lang . '.error'), ExceptionCode::E_APP_ERROR_LOGIN);
                throw new Exception(Lang::get('exception_msg.' . 'LOGIN_FAIL'), ExceptionCode::E_APP_ERROR_LOGIN);
            }

            $this->resp(Lang::get($this->lang . '.success'));
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    public function post_forgot()
    {
        try {
            $emailLabel = Lang::get('label.email');
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('email', $emailLabel, 'required|valid_email');
            if (!$val->run()) {
              $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
              return $this->response($this->resp);
            }
            $arrInput = \Input::post();
            $emailEncryt = \DB::expr("AES_ENCRYPT(".\DB::quote($arrInput['email']).", '".encrypt_key."')");
            $arrList = \DB::select_array(['id']);
            $arrList->from('m_employee');
            $arrList->where('del_flg' ,'=', 0);
            $arrList->where('email' ,'=', $emailEncryt);
            $arrData = $arrList->execute()->as_array();
            if(!$arrData) {
              $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, ['email'=>'メールアドレスが存在していません。']);
              return $this->response($this->resp);
            }
            $email = $val->validated('email');
            if (!Model_Base_User::user_forgot($emailEncryt)) {
              throw new Exception(Lang::get($this->lang . '.error'), ExceptionCode::E_APP_ERROR_LOGIN);
            }            
            // $user = Model_Base_Core::getOne('Model_MEmployee', [
            //   'from_cache' => true,
            //   'where' => ['email' => $emailEncryt]
            // ]);

            $arrList = \DB::select_array([
              'id','emp_id', \DB::expr("CAST(AES_DECRYPT(emp_nm, '".encrypt_key."') as char(200) ) as emp_nm"),
              \DB::expr("CAST(AES_DECRYPT(emp_kana_nm, '".encrypt_key."') as char(200) ) as emp_kana_nm"), 'user_id',
              \DB::expr("CAST(AES_DECRYPT(email, '".encrypt_key."') as char(200) ) as email"), 
              'active', 'system_role_cd','emp_type_cd','aff_dept_cd','agency_cd','position_cd','forgot_token'
            ]);
            $arrList->from('m_employee');
            $arrList->where('del_flg' ,'=', 0);
            $arrList->where('email' ,'=', $emailEncryt);

            $user = $arrList->execute()->current();

            if ($user === false) {
              throw new Exception();
            }
              
            $mail_data = [
              'to' => $email,
              'subject' => 'パスワードの再設定｜Agency Management',
              'view' => 'auth_forgot',
              'body' => $user
            ];
            Model_Service_Mail::send($mail_data);

            $this->resp(Lang::get($this->lang . '.success'));
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    public function post_reset_password()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('forgot_token', Lang::get('label.forgot_token'), 'required|valid_field[Model_MEmployee,forgot_token]');
            $val->add_field('password', Lang::get('label.password'), 'required|valid_password|max_length[50]');
            $val->add_field('password_confirm', Lang::get('label.password_confirm'), 'match_field[password]');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $forgotToken = $val->validated('forgot_token');
            $password = Model_Service_Util::hash_password($val->validated('password'));
            if (!Model_Base_Core::updateByWhere('Model_MEmployee', ['forgot_token' => $forgotToken], ['password' => $password, 'forgot_token' => ''])) {
                throw new Exception();
            }

            $this->resp(Lang::get($this->lang . '.success'));
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    public function post_updateSession()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('manage_unit_cd', Lang::get('label.manage_unit_cd'), 'required_array');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }
            $manage_unit_cd = $val->validated('manage_unit_cd');
            $manage_unit_cd = implode(',', $manage_unit_cd);
            if (isset($this->user['manage_unit_cd'])) {
                $this->user['manage_unit_cd'] = $manage_unit_cd;
            }
            \Session::set('user_manage_unit_cd', $manage_unit_cd);
            $this->resp(Lang::get($this->lang . '.success'));
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    } 

}
 