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

class Controller_Master_Oamachine extends Controller_Base_Rest
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
            $val->add_field('maker_nm', Lang::get('label.maker_nm'), []);
            $val->add_field('type_cd', Lang::get('label.type_cd'), []);
            $val->add_field('product_nm', Lang::get('label.product_nm'), []);
            $val->add_field('model_nm', Lang::get('label.model_nm'), []);
            
            $val->add_field('itemperpage', Lang::get('label.itemperpage'), []);
            $val->add_field('page', Lang::get('label.page'), []);
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            $arrQuery = [
                'select' => ['id', 'maker_nm', 'type_cd', 'product_nm', 'model_nm'],
                'where' => [['del_flg' => 0]],
                'order_by' => ['id' => 'DESC']
            ];
            if (!empty($arrInput['maker_nm'])) {
                $arrQuery['where'][] = ['maker_nm', 'LIKE', "%{$arrInput['maker_nm']}%"];
            }
            if (!empty($arrInput['type_cd'])) {
                $arrQuery['where'][] = ['type_cd', 'LIKE', "%{$arrInput['type_cd']}%"];
            }
            if (!is_null($arrInput['product_nm']) && $arrInput['product_nm'] !== '') {
                $arrQuery['where'][] = ['product_nm', 'LIKE', "%{$arrInput['product_nm']}%"];
            }
            if (!empty($arrInput['model_nm'])) {
                $arrQuery['where'][] = ['model_nm', 'LIKE', "%{$arrInput['model_nm']}%"];
            }
            
            $arrData = ['total' => 0, 'list' => []];
            $rowNum = Model_Base_Core::getRowNum('Model_MOaDevice', $arrQuery['where']);
            
            if ($rowNum > 0) {
                $iTemPerPage = empty($arrInput['itemperpage']) ? _DEFAULT_LIMIT_ : (int) $arrInput['itemperpage'];
                $iPage = empty($arrInput['page']) ? 1 : (int) $arrInput['page'];
                $arrQuery['limit'] = $iTemPerPage;
                $arrQuery['offset'] = ($iPage - 1) * $iTemPerPage;
                $arrList = Model_Base_Core::getAll('Model_MOaDevice', $arrQuery);
                if ($arrList === false) {
                    throw new Exception();
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
                $arrData = Model_Base_Core::getOne('Model_MOaDevice', [
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
            $val->add_field('id', Lang::get('label.id'), 'valid_field[Model_MOaDevice,id]');
            
            $val->add_field('maker_nm', Lang::get('label.maker_nm'), 'required|max_length[100]');
            $val->add_field('type_cd', Lang::get('label.type_cd'), 'required|max_length[50]');
            $val->add_field('product_nm', Lang::get('label.product_nm'), 'required|max_length[60]');
            $val->add_field('model_nm', Lang::get('label.model_nm'), 'required|max_length[200]');
            
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            
            if (!empty($arrInput['id'])) {
                if (!Model_Base_Core::update('Model_MOaDevice', $arrInput['id'], $arrInput)) {
                    throw new Exception();
                }
            } else {
                if (isset($arrInput['id'])) {
                    unset($arrInput['id']);
                }
                if (!Model_Base_Core::insert('Model_MOaDevice', $arrInput)) {
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
            $val->add_field('id', Lang::get('label.id'), 'required|valid_field[Model_MOaDevice,id]');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            if (!Model_Base_Core::update('Model_MOaDevice', $arrInput['id'], ['del_flg' => 1])) {
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
