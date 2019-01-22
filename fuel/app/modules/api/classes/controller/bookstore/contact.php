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

use Model_Service_Mail;
use Model_Service_Util;
use Model_Base_Core;
use Model_Service_Upload;
use Exception;


class Controller_Bookstore_Contact extends Controller_Base_Rest
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

  public function post_sendMailContact() {
    try {
      DB::start_transaction();
      $val = Validation::forge();
      $val->add_callable('MyRules');
      $val->add_field('data.email', 'email', 'required');
      $val->add_field('data', 'data', []);

      if (!$val->run()) {
        $this->resp(null, 3000, $val->error_message());
        return $this->response($this->resp);
      }

      $arrInput = $val->validated('data');
      $arrSendTo = ['mr.linh1090@gmail.com','linhkhpk00213@gmail.com'];
      $infoData = [
        'name' => !empty($arrInput['name']) ? $arrInput['name'] : null,
        'email' => !empty($arrInput['email']) ? $arrInput['email'] : null,
        'mobile' => !empty($arrInput['mobile']) ? $arrInput['mobile'] : null,
        'subject' => !empty($arrInput['subject']) ? $arrInput['subject'] : null,
      ];
      $mail_data = [
        'from' => $infoData['email'],
        'to' => $arrSendTo,
        'subject' => 'Mail phản hồi｜Shop',
        'view' => 'contact_mail',
        'body' => $infoData
      ];
      if (!Model_Service_Mail::send($mail_data)) {
        throw new Exception("Error Processing Request", 1);
      }
      
      $this->resp('success');

      DB::commit_transaction();
    } catch (Exception $e) {
      DB::rollback_transaction();
      Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
      $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
      $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
      $this->resp($msg, $code);
    }

    return $this->response($this->resp);
  }

}