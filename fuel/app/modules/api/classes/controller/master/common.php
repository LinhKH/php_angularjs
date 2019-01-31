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

class Controller_Master_Common extends Controller_Base_Rest
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

    public function post_list()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('mst_id', Lang::get('label.mst_id'), 'max_length[50]');
            $val->add_field('mst_nm', Lang::get('label.mst_nm'), 'max_length[50]');
            $val->add_field('elem_change_ok_flg', Lang::get('label.elem_change_ok_flg'), 'trim');
            $val->add_field('code_len', Lang::get('label.code_len'), 'trim');
            $val->add_field('code_value', Lang::get('label.code_value'), 'trim|max_length[10]');
            $val->add_field('sort_value', Lang::get('label.sort_value'), 'trim');
            $val->add_field('disp_value', Lang::get('label.disp_value'), 'trim|max_length[255]');
            $val->add_field('itemperpage', Lang::get('label.itemperpage'), 'trim');
            $val->add_field('page', Lang::get('label.page'), 'trim');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            $arrQuery = [
                'select' => ['id', 'mst_id', 'mst_nm', 'code_value', 'disp_value', 'sort_value', 'elem_change_ok_flg', 'code_len'],
                'where' => [['del_flg' => 0]],
                'order_by' => ['mst_id' => 'ASC', 'sort_value' => 'ASC']
            ];
            if (!empty($arrInput['mst_id'])) {
                $arrQuery['where'][] = ['mst_id', 'LIKE', "%{$arrInput['mst_id']}%"];
            }
            if (!empty($arrInput['mst_nm'])) {
                $arrQuery['where'][] = ['mst_nm', 'LIKE', "%{$arrInput['mst_nm']}%"];
            }
            if (!is_null($arrInput['elem_change_ok_flg']) && $arrInput['elem_change_ok_flg'] !== '') {
                $arrQuery['where'][] = ['elem_change_ok_flg', '=', $arrInput['elem_change_ok_flg']];
            }
            if (!empty($arrInput['code_value'])) {
                $arrQuery['where'][] = ['code_value', '=', $arrInput['code_value']];
            }
            if (!empty($arrInput['disp_value'])) {
                $arrQuery['where'][] = ['disp_value', 'LIKE', "%{$arrInput['disp_value']}%"];
            }
            if (!empty($arrInput['code_len'])) {
                $arrQuery['where'][] = ['code_len', '=', $arrInput['code_len']];
            }
            if (!empty($arrInput['sort_value'])) {
                $arrQuery['where'][] = ['sort_value', '=', $arrInput['sort_value']];
            }

            $arrData = ['total' => 0, 'list' => []];
            $rowNum = Model_Base_Core::getRowNum('Model_MCommonMst', $arrQuery['where']);
            if ($rowNum > 0) {
                $iTemPerPage = empty($arrInput['itemperpage']) ? _DEFAULT_LIMIT_ : (int) $arrInput['itemperpage'];
                $iPage = empty($arrInput['page']) ? 1 : (int) $arrInput['page'];
                $arrQuery['limit'] = $iTemPerPage;
                $arrQuery['offset'] = ($iPage - 1) * $iTemPerPage;
                $arrList = Model_Base_Core::getAll('Model_MCommonMst', $arrQuery);
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
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    public function get_detail()
    {
        try {
            $arrData = [];
            $id = Input::get('id');
            if (!empty($id)) {
                $arrData = Model_Base_Core::getOne('Model_MCommonMst', [
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

    public function post_upsert()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('id', Lang::get('label.id'), 'valid_field[Model_MCommonMst,id]');
            $val->add_field('mst_id', Lang::get('label.mst_id'), 'required|max_length[50]');
            $val->add_field('mst_nm', Lang::get('label.mst_nm'), 'required|max_length[50]');
            $val->add_field('elem_change_ok_flg', Lang::get('label.elem_change_ok_flg'), 'required_select');
            $val->add_field('code_len', Lang::get('label.code_len'), 'required');
            $val->add_field('code_value', Lang::get('label.code_value'), 'required|max_length[10]');
            $val->add_field('sort_value', Lang::get('label.sort_value'), 'required');
            $val->add_field('disp_value', Lang::get('label.disp_value'), 'required|max_length[255]');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            if (!empty($arrInput['id'])) {
                if (!Model_Base_Core::update('Model_MCommonMst', $arrInput['id'], $arrInput)) {
                    throw new Exception();
                }
            } else {
                if (isset($arrInput['id'])) {
                    unset($arrInput['id']);
                }
                if (!Model_Base_Core::insert('Model_MCommonMst', $arrInput)) {
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

    public function post_delete()
    {
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('id', Lang::get('label.id'), 'required|valid_field[Model_MCommonMst,id]');
            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            if (!Model_Base_Core::update('Model_MCommonMst', $arrInput['id'], ['del_flg' => 1])) {
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
