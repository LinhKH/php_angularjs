<?php

use Fuel\Core\Config;
use Fuel\Core\DBUtil;

class MyRules
{

    public static function _empty($val)
    {
        return ($val === false or $val === null or $val === '' or $val === []);
    }

    public static function _validation_required($val)
    {
        return !self::_empty($val);
    }

    public static function _validation_required_select($val)
    {
        return !self::_empty($val);
    }

    public static function _validation_required_array($val)
    {
        if (is_array($val)) {
            foreach ($val as $item) {
                if (self::_empty($item)) {
                    return false;
                }
            }
            return true;
        } else {
            return !self::_empty($val);
        }
    }

    public static function _validation_valid_numeric($val)
    {
        if (self::_empty($val)) {
            return true;
        }
        $pattern = '/^([[0-9])+$/';
        return preg_match($pattern, $val) > 0;
    }

    public static function _validation_valid_password($val)
    {
        if (self::_empty($val)) {
            return true;
        }
        $pattern = '/^([a-zA-Z0-9])+$/u';
        return preg_match($pattern, $val) > 0;
    }

    public static function _validation_valid_emp_id($val)
    {
        if (self::_empty($val)) {
            return true;
        }
        $pattern = '/^([0-9])+$/u';
        return (preg_match($pattern, $val) > 0);
    }

    public static function _validation_valid_user_id($val)
    {
        return Model_Base_Core::validFields('Model_MEmployee', [
                'user_id' => $val,
                'active' => 0
        ]);
    }

    public static function _validation_check_mapping($val)
    {
        if (empty($val) || empty($val['map_id']) || empty($val['map_column_id'])) {
            return false;
        }
        if (!is_array($val['map_id']) || !is_array($val['map_column_id'])) {
            return false;
        }
        if (count($val['map_id']) !== count($val['map_column_id'])) {
            return false;
        }

        Config::load('call_list', true);
        $arrIdConlumnConfig = Config::get('call_list.t_mapping_template_detail.map_column_id');
        foreach ($val['map_column_id'] as $v) {
            if (!array_key_exists($v, $arrIdConlumnConfig) && strlen($v) > 0) {
                return false;
            }
        }

        return true;
    }

    public static function _validation_valid_common_master($val, $mst_id)
    {
        if (self::_empty($val)) {
            return true;
        }
        return Model_Base_Core::validFields('Model_MCommonMst', [['mst_id' => $mst_id], ['code_value' => $val]]);
    }

    public static function _validation_valid_visit_dt($val)
    {
        if (self::_empty($val)) {
            return true;
        }
        return !(strtotime(date('Y/m/d')) > strtotime($val) );
    }

    public static function _validation_valid_tel_no($val)
    {
        if (self::_empty($val)) {
            return true;
        }
        $pattern = '/^[0-9\-]{6,15}+$/u';
        return (preg_match($pattern, trim($val)) > 0);
    }

    public static function _validation_valid_tel_no_num($val)
    {
        if (\Input::param('apokin_range_type_cd') == 1) {
            return !empty($val);
        }
        return true;
    }

    public static function _validation_valid_checkinglist_field($val)
    {
        if (self::_empty($val)) {
            return true;
        }
        Config::load('call_list', true);
        $arrIdConlumnConfig = Config::get('call_list.t_display_layout_detail.disp_column_id');
        foreach ($arrIdConlumnConfig as $field) {
            if (isset($field[0]) && $field[0] == trim($val)) {
                return true;
            }
        }
        return false;
    }

    public static function _validation_valid_column($val, $table)
    {
        if (self::_empty($val)) {
            return true;
        }
        return DBUtil::field_exists($table, [$val]);
    }

    public static function _validation_valid_columnS($val)
    {
        if (self::_empty($val)) {
            return true;
        }
        list($table, $column) = explode('.', $val);
        return DBUtil::field_exists($table, [$column]);
    }

    public static function _validation_valid_field($val, $modelName, $field)
    {
        if (self::_empty($val)) {
            return true;
        }
        return Model_Base_Core::validField($modelName, $field, $val);
    }

    public static function _validation_unique_field($val, $modelName, $field)
    {
        if (self::_empty($val)) {
            return true;
        }
        return !Model_Base_Core::validField($modelName, $field, $val);
    }

    public static function _validation_unique_field_v2($val, $modelName, $field, $pKeyName = 'id', $pKeyVal)
    {
        if (self::_empty($val)) {
            return true;
        }
        if (is_null($pKeyVal)) {
            return !Model_Base_Core::validField($modelName, $field, $val);
        } else {
            return count(Model_Base_Core::getAll($modelName, ['select' => ['id'], 'where' => ['del_flg' => 0, $field => $val, [$pKeyName, '!=', $pKeyVal]]])) == 0;
        }
    }

    public static function _validation_max_length_tel($val, $length)
    {
        return self::_empty($val) || \Str::length($val) <= $length;
    }

    public static function _validation_valid_file_name($val)
    {
        if (self::_empty($val)) {
            return true;
        }
        $pattern = '/[\'\"\*\?\&\:\#\/\\\|\<\>\t+\s+\s+$]+/';
        return !(preg_match($pattern, $val) > 0);
    }

//    public static function _validation_required($val)
//    {
//        $val = Model_Service_Util::mb_trim($val);
//        return !Model_Service_Util::_empty($val);
//    }
//
//
//    public static function _validation_max_length($val, $length)
//    {
//        $val = preg_replace('/\r\n/', ' ', $val);
//        $val = html_entity_decode($val, ENT_QUOTES);
//        return Model_Service_Util::_empty($val) || Str::length($val) <= $length;
//    }
//
//    public static function _validation_input_notcontain_email($val)
//    {
//        $matches = array();
//        $pattern = '/[A-Za-z0-9_-]+@[A-Za-z0-9_-]+\.([A-Za-z0-9_-][A-Za-z0-9_]+)/';
//        preg_match($pattern, $val, $matches);
//        if (!empty($matches) && count($matches) > 0) {
//            return false;
//        } else {
//            return true;
//        }
//    }
}
