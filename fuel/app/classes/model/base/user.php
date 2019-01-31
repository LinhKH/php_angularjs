<?php

use Auth\Auth;

class Model_Base_User
{

    public static function is_login()
    {
        return Auth::check();
    }

    public static function getUserInfo()
    {
        if (Auth::check()) {
            return Auth::get();
        }
        return false;
    }

    public static function user_login($user_id, $password, $is_remember = false)
    {
        if (Auth::instance()->login($user_id, $password)) {
            if ($is_remember) {
                Auth::remember_me();
            }
            return true;
        }
        return false;
    }

    public static function user_forgot($email)
    {
        return Model_Base_Core::updateByWhere('Model_MEmployee', ['email' => $email], ['forgot_token' => Model_Service_Util::gen_code($email)]);
    }

    public static function getEmpCode()
    {
        return Auth::get('emp_id');
    }

    public static function getAffDeptCode()
    {
        return Auth::get('aff_dept_cd');
    }

    public static function getRoleVlCode()
    {
        $sys_role_cd = Auth::get('system_role_cd');
        $sys_role = Model_MSysRole::find_by_system_role_cd($sys_role_cd);
        $sys_role_vl_cd = $sys_role['role_lv_cd'];
        return $sys_role_vl_cd;
    }

    public static function getRole()
    {
        $sys_role_cd = Auth::get('system_role_cd');
        $sys_role = Model_MSysRole::find_by_system_role_cd($sys_role_cd);
        $sys_role_vl_cd = $sys_role;
        return $sys_role_vl_cd;
    }

    public static function getParentOrgCode(){
        $aff_code = \Model_Base_User::getAffDeptCode();
        $arr_org = \Model_MOrg::find_by_org_cd($aff_code);
        return $arr_org['parent_org_cd'];
    }

    public static function getMainSaleItemCd(){
        $aff_code = \Model_Base_User::getAffDeptCode();
        $arr_org = \Model_MOrg::find_by_org_cd($aff_code);
        return $arr_org['main_sale_item_cd'];
    }

    public static function getEmpNmByEmpid($empid) {
        $emp_nm = Model_Base_Core::getOne('Model_MEmployee', [
                        'select' => ['emp_nm'],
                        'where' => ['emp_id' , '=',  $empid],
                    ]);
        return isset($emp_nm['emp_nm'])? $emp_nm['emp_nm']: '';
    }

}
