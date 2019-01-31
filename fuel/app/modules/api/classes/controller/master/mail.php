<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Fuel\Core\Log;
use Fuel\Core\Lang;
use Fuel\Core\Validation;
use Fuel\Core\Input;
use Api\Exception\ExceptionCode;
use Exception;

class Controller_Master_Mail extends Controller_Base_Rest
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
            $val->add_field('name', Lang::get('label.name'), []);
            $val->add_field('mail_address', Lang::get('label.mail_address'), []);
            $val->add_field('type', Lang::get('label.type'), []);
            
            $val->add_field('itemperpage', Lang::get('label.itemperpage'), []);
            $val->add_field('page', Lang::get('label.page'), []);
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrType = [
                1 => 'To',
                2 => 'Cc',
                3 => 'Bcc'
            ];

            $arrInput = $val->validated();
            $arrQuery = [
                'select' => ['id', 'name', 'mail_address','type'],
                'where' => [['del_flg' => 0]],
                'order_by' => ['id' => 'DESC']
            ];
            if (!empty($arrInput['name'])) {
                $arrQuery['where'][] = ['name', 'LIKE', "%{$arrInput['name']}%"];
            }
            if (!empty($arrInput['mail_address'])) {
                $arrQuery['where'][] = ['mail_address', 'LIKE', "%{$arrInput['mail_address']}%"];
            }
            if (!empty($arrInput['type'])) {
                $arrQuery['where'][] = ['type', '=', $arrInput['type']];
            }
            
            $arrData = ['total' => 0, 'list' => []];
            $rowNum = Model_Base_Core::getRowNum('Model_MMailAcc', $arrQuery['where']);
            
            if ($rowNum > 0) {
                $iTemPerPage = empty($arrInput['itemperpage']) ? _DEFAULT_LIMIT_ : (int) $arrInput['itemperpage'];
                $iPage = empty($arrInput['page']) ? 1 : (int) $arrInput['page'];
                $arrQuery['limit'] = $iTemPerPage;
                $arrQuery['offset'] = ($iPage - 1) * $iTemPerPage;
                $arrList = Model_Base_Core::getAll('Model_MMailAcc', $arrQuery);
                if ($arrList === false) {
                    throw new Exception();
                }

                foreach ($arrList as &$row) {
                    if(isset($row['type']) && isset($arrType[$row['type']])){
                        $row['type'] = $arrType[$row['type']];
                    }
                }

                $arrData['total'] = $rowNum;
                $arrData['list'] = $arrList;
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
                $arrData = Model_Base_Core::getOne('Model_MMailAcc', [
                        'where' => [['del_flg' => 0], ['id', '=', $id]]
                ]);
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
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('id', Lang::get('label.id'), 'valid_field[Model_MMailAcc,id]');
            $val->add_field('name', Lang::get('label.mail_name'), 'required|max_length[255]');
            $val->add_field('mail_address', Lang::get('label.mail_address'), 'required|valid_email|unique_field_v2[Model_MMailAcc,mail_address,id,' . Input::param('id') . ']');
            $val->add_field('type', Lang::get('label.mail_type'), 'required|max_length[1]');
            
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            
            if (!empty($arrInput['id'])) {
                if (!Model_Base_Core::update('Model_MMailAcc', $arrInput['id'], $arrInput)) {
                    throw new Exception();
                }
            } else {
                if (isset($arrInput['id'])) {
                    unset($arrInput['id']);
                }
                if (!Model_Base_Core::insert('Model_MMailAcc', $arrInput)) {
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
            $val->add_field('id', Lang::get('label.id'), 'required|valid_field[Model_MMailAcc,id]');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            if (!Model_Base_Core::update('Model_MMailAcc', $arrInput['id'], ['del_flg' => 1])) {
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
