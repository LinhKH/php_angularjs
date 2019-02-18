<?php

namespace Api;

use Controller_Base_Rest;
use Model_Base_Core;
use Model_Service_Util;
use Fuel\Core\DB;
use Fuel\Core\Log;
use Fuel\Core\Lang;
use Fuel\Core\Validation;
use Fuel\Core\Input;
use Api\Exception\ExceptionCode;
use Model_Base_ApoGadgetOtokuline;
use Model_Base_ApoGadget;
use Exception;

class Controller_Apo_Otokuline extends Controller_Base_Rest
{

    private $_productId = 1;
    
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

    public function post_businessconfirmemployee()
    {
        try {
            $arrEmployee = DB::select('m.emp_id', 'm.emp_nm', 'm.emp_kana_nm')
                    ->from(DB::expr('m_employee m'))
                    ->join(DB::expr('m_sys_role role'), 'left')
                    ->on('m.system_role_cd', '=', 'role.system_role_cd')
                    ->join(DB::expr('m_org org'), 'left')
                    ->on('m.aff_dept_cd', '=', 'org.org_cd')
                    ->where('role.role_lv_cd', 'IN', [20, 30])
                    ->where('m.del_flg', '=', 0)
                    ->where('org.sales_dept_flg', 1)
                    ->group_by('m.id')->execute()->as_array();

            $this->resp(null, null, Model_Service_Util::reindexArrBykey($arrEmployee, 'emp_id'));
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    public function post_update()
    {
        try {
            if ($this->user['sys_role']['role_lv_cd'] == 10) {
                throw new Exception(Lang::get('exception_msg.' . ExceptionCode::E_NOT_HAVE_PERMISSION), ExceptionCode::E_NOT_HAVE_PERMISSION);
            }
            DB::start_transaction();

            // $sApoVisitHistSalesResult = "";
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('visit_hist_id', Lang::get('label.visit_hist_id'), 'required|valid_field[Model_TApoVisitHist,id]');
            $val->add_field('sales_result', Lang::get('label.sales_result'), []);
            $val->add_field('sugestion_product', Lang::get('label.sugestion_product'), []);
            $val->add_field('class1_ac_cnt', Lang::get('label.class1_ac_cnt'), 'valid_numeric');
            $val->add_field('class1_ins_cnt', Lang::get('label.class1_ins_cnt'), 'valid_numeric');
            $val->add_field('class1_ana_cnt', Lang::get('label.class1_ana_cnt'), 'valid_numeric');
            $val->add_field('class2_ac_cnt', Lang::get('label.class2_ac_cnt'), 'valid_numeric');
            $val->add_field('class2_ins_cnt', Lang::get('label.class2_ins_cnt'), 'valid_numeric');
            $val->add_field('class2_ana_cnt', Lang::get('label.class2_ana_cnt'), 'valid_numeric');
            $val->add_field('class3_ac_cnt', Lang::get('label.class3_ac_cnt'), 'valid_numeric');
            $val->add_field('class3_ins_cnt', Lang::get('label.class3_ins_cnt'), 'valid_numeric');
            $val->add_field('class3_ana_cnt', Lang::get('label.class3_ana_cnt'), 'valid_numeric');
            $val->add_field('otokuline_expected_l_cnt', Lang::get('label.otokuline_expected_l_cnt'), 'valid_numeric');
            $val->add_field('reduce_amount', Lang::get('label.reduce_amount'), 'valid_numeric');
            $val->add_field('ys_line_num', Lang::get('label.ys_line_num'), 'valid_numeric');
            $val->add_field('atoi_num', Lang::get('label.atoi_num'), 'valid_numeric');
            $val->add_field('regist_num', Lang::get('label.regist_num'), 'valid_numeric');
            $val->add_field('detail_ins', Lang::get('label.detail_ins'), 'valid_numeric');
            $val->add_field('detail_ana', Lang::get('label.detail_ana'), 'valid_numeric');
            $val->add_field('detail_anatoins', Lang::get('label.detail_anatoins'), 'valid_numeric');
            $val->add_field('white_line_ins', Lang::get('label.white_line_ins'), 'valid_numeric');
            $val->add_field('white_line_ana', Lang::get('label.white_line_ana'), 'valid_numeric');
            $val->add_field('1en_ins', Lang::get('label.1en_ins'), 'valid_numeric');
            $val->add_field('1en_ana', Lang::get('label.1en_ana'), 'valid_numeric');
            $val->add_field('other_3year', Lang::get('label.other_3year'), 'valid_numeric');
            $val->add_field('sbm_ins_has', Lang::get('label.sbm_ins_has'), 'valid_numeric');
            $val->add_field('sbm_ana_has', Lang::get('label.sbm_ana_has'), 'valid_numeric');
            $val->add_field('sbm_ins_no', Lang::get('label.sbm_ins_no'), 'valid_numeric');
            $val->add_field('sbm_ana_no', Lang::get('label.sbm_ana_no'), 'valid_numeric');
            $val->add_field('expected_l', Lang::get('label.expected_l'), 'valid_numeric');
            $val->add_field('ys_campaing', Lang::get('label.ys_campaing'), []);
            $val->add_field('ys_wifi_lan', Lang::get('label.ys_wifi_lan'), []);
            $val->add_field('pic_cd', Lang::get('label.pic_cd'), []);
            $val->add_field('ipad_flg', Lang::get('label.ipad_flg'), []);
            $val->add_field('fd_flg', Lang::get('label.fd_flg'), []);
            $val->add_field('seal_flg', Lang::get('label.seal_flg'), []);
            $val->add_field('consult_date', Lang::get('label.consult_date'), []);
            $val->add_field('direct_consult_flg', Lang::get('label.direct_consult_flg'), []);
            $val->add_field('sbtm_confirm_flg', Lang::get('label.sbtm_confirm_flg'), []);
            $val->add_field('notify_method', Lang::get('label.notify_method'), []);
            $val->add_field('ins_switch_yn', Lang::get('label.ins_switch_yn'), []);
            $val->add_field('transfer_flg', Lang::get('label.transfer_flg'), []);
            $val->add_field('telno_display_flg', Lang::get('label.telno_display_flg'), []);
            $val->add_field('visitortelecall', Lang::get('label.visitortelecall'), []);
            $val->add_field('change_work_date', Lang::get('label.change_work_date'), []);
            $val->add_field('change_work_time', Lang::get('label.change_work_time'), []);
            $val->add_field('new_establish_date', Lang::get('label.new_establish_date'), []);
            $val->add_field('arrangement_state', Lang::get('label.arrangement_state'), []);
            $val->add_field('option_result', Lang::get('label.option_result'), []);
            $val->add_field('other_bank_num_flg', Lang::get('label.other_bank_num_flg'), []);
            $val->add_field('other_confirm_flg', Lang::get('label.other_confirm_flg'), []);
            $val->add_field('36month_reduce_flg', Lang::get('label.36month_reduce_flg'), []);
            $val->add_field('genie_confirm_state', Lang::get('label.genie_confirm_state'), []);
            $val->add_field('giga_hastel', Lang::get('label.giga_hastel'), 'valid_numeric');
            $val->add_field('giga_notel', Lang::get('label.giga_notel'), 'valid_numeric');
            $val->add_field('ocn_hastel', Lang::get('label.ocn_hastel'), 'valid_numeric');
            $val->add_field('ocn_notel', Lang::get('label.ocn_notel'), 'valid_numeric');
            $val->add_field('sb_hastel', Lang::get('label.sb_hastel'), 'valid_numeric');
            $val->add_field('sb_notel', Lang::get('label.sb_notel'), 'valid_numeric');
            $val->add_field('otoku_wifi_no', Lang::get('label.otoku_wifi_no'), 'valid_numeric');
            $val->add_field('otoku_wifi_send_dt', Lang::get('label.otoku_wifi_send_dt'), []);
            $val->add_field('hikari_korabo_ok_no', Lang::get('label.hikari_korabo_ok_no'), 'valid_numeric');
            $val->add_field('ng_content', Lang::get('label.ng_content'), []);
            $val->add_field('ng_type_cd', Lang::get('label.ng_type_cd'), []);
            $val->add_field('consider_consider_class_flg', Lang::get('label.consider_class_flg'), []);
            $val->add_field('consider_expected_rank_cd', Lang::get('label.expected_rank_cd'), []);
            $val->add_field('consider_expected_l', Lang::get('label.expected_l'), []);
            $val->add_field('consider_consider_content', Lang::get('label.consider_content'), []);
            $val->add_field('consider_to_ng_ng_type_cd', Lang::get('label.consider_to_ng_ng_type_cd'), 'max_length[2]');
            $val->add_field('consider_to_ng_consider_to_ng_date', Lang::get('label.consider_to_ng_consider_to_ng_date'), []);
            $val->add_field('consider_to_ng_consider_to_ng_content', Lang::get('label.consider_to_ng_consider_to_ng_content'), 'max_length[1000]');
            $val->add_field('consult_info', Lang::get('label.has_consult_cust'), []);
            $val->add_field('ac_sum', Lang::get('label.ac_sum'), []);
            $val->add_field('ins_sum', Lang::get('label.ins_sum'), []);
            $val->add_field('ana_sum', Lang::get('label.ana_sum'), []);
            $val->add_field('class1_sum', Lang::get('label.class1_sum'), []);
            $val->add_field('class2_sum', Lang::get('label.class2_sum'), []);
            $val->add_field('class3_sum', Lang::get('label.class3_sum'), []);
            $val->add_field('sum_all', Lang::get('label.sum_all'), []);
            $val->add_field('direct_decision', Lang::get('label.direct_decision'), []);
            $val->add_field('sbm_ins_has_a', Lang::get('label.sbm_ins_has'), 'valid_numeric');
            $val->add_field('sbm_ana_has_a', Lang::get('label.sbm_ana_has'), 'valid_numeric');
            $val->add_field('sbm_ins_no_a', Lang::get('label.sbm_ins_no'), 'valid_numeric');
            $val->add_field('sbm_ana_no_a', Lang::get('label.sbm_ana_no'), 'valid_numeric');
            $val->add_field('36month_reduce_flg_a', Lang::get('label.36month_reduce_flg'), 'valid_numeric');
            $val->add_field('genie_confirm_state_a', Lang::get('label.genie_confirm_state'), 'valid_numeric');
            $val->add_field('sbm_ins_has_b', Lang::get('label.sbm_ins_has'), 'valid_numeric');
            $val->add_field('sbm_ana_has_b', Lang::get('label.sbm_ana_has'), 'valid_numeric');
            $val->add_field('sbm_ins_no_b', Lang::get('label.sbm_ins_no'), 'valid_numeric');
            $val->add_field('sbm_ana_no_b', Lang::get('label.sbm_ana_no'), 'valid_numeric');
            $val->add_field('36month_reduce_flg_b', Lang::get('label.36month_reduce_flg'), 'valid_numeric');
            $val->add_field('genie_confirm_state_b', Lang::get('label.genie_confirm_state'), 'valid_numeric');
            $val->add_field('msn_a', Lang::get('label.msn'), 'max_length[50]');
            $val->add_field('msn_b', Lang::get('label.msn'), 'max_length[50]');
            $val->add_field('msn', Lang::get('label.msn'), 'max_length[50]');
            
            // $val->add_field('survey', Lang::get('label.survey'), []);
            // validate SalesResult OK
            if (!is_null(Input::post('sales_result')) && (Model_Base_ApoGadget::isSaleOk(Input::post('sales_result')) || Input::post('sales_result') == '1404')) {
                $val->add_field('calculated_date', Lang::get('label.calculated_date'), 'required');
            } else {
                $val->add_field('calculated_date', Lang::get('label.calculated_date'), []);
            }

            // validate SalseResult Consider
            if (!is_null(Input::post('sales_result')) && substr(Input::post('sales_result'), 0, 2) == '17') {
                $val->add_field('consider_reply_waiting_date', Lang::get('label.reply_waiting_date'), 'required');
            } else {
                $val->add_field('consider_reply_waiting_date', Lang::get('label.reply_waiting_date'), []);
            }

            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            Lang::load('validation');
            $arrInput = $val->validated();

            /*
            //validate new customer
            $arrSaleResultValidate = [1701, 1201, 1202, 1205, 1204, 1208, 1213, 1210, 1212, 1211, 1401, 1302, 1402];

            if(isset($arrInput['sales_result']) && in_array($arrInput['sales_result'], $arrSaleResultValidate)){

                // validate hearing_mobile
                $arrCurrentHearing = Model_Base_Core::getOne('Model_TApoHearingMobileHist',[
                    'select' => ['id','mobile_new_exist'],
                    'where' => [['del_flg' => 0],['visit_hist_id' => $arrInput['visit_hist_id']] ]
                ]);
                if( empty($arrCurrentHearing) || $arrCurrentHearing['mobile_new_exist'] == null ||$arrCurrentHearing['mobile_new_exist'] == '' ){
                    $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, ['mobile_new_exist' => 'ヒアリングモバイルの新規・既存を入力してください。']);
                    return $this->response($this->resp);
                }

                // validate apo_visit_hist
                $arrCurrentVisitHist = Model_Base_Core::getOne('Model_TApoVisitHist',[
                    'select' => ['id','new_customer_flg','sugestion_product'],
                    'where' => [['del_flg' => 0],['id' => $arrInput['visit_hist_id']] ]
                ]);
                if($arrCurrentVisitHist['new_customer_flg'] == null || $arrCurrentVisitHist['new_customer_flg'] == ''){
                    $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, ['new_customer_flg' => 'APO詳細の新規・既存を入力してください。']);
                    return $this->response($this->resp);
                }
                if($arrCurrentVisitHist['sugestion_product'] == null || $arrCurrentVisitHist['sugestion_product'] == ''){
                    $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, ['sugestion_product' => 'APO提案種別を入力してください。']);
                    return $this->response($this->resp);
                }
            }

            $arrSaleOK = [1201, 1202, 1205, 1204, 1208, 1213, 1210, 1211];
            if(isset($arrInput['sales_result']) && in_array($arrInput['sales_result'], $arrSaleOK)){
                //direct_decision
                if( empty($arrInput['direct_decision']) || ($arrInput['direct_decision'] != '1' && $arrInput['direct_decision'] != '2' )){
                    $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, ['direct_decision' => '即決または検討OKを入力してください。']);
                    return $this->response($this->resp);
                }
            }
            */

            foreach ($arrInput as $k => $v) {
                if (!is_array($v) && strlen(rtrim($v)) === 0) {
                    $arrInput[$k] = null;
                }
            }

            $oGadgetOtokuline = new Model_Base_ApoGadgetOtokuline($this->_productId);

            $arrResult = $oGadgetOtokuline -> update($arrInput);
            if(isset($arrResult['errors'])) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $arrResult['errors']);
            } else {
                $this->resp(Lang::get($this->lang . '.success'), null, $arrResult);
            }
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
    
    // Update tab survey アンケート
    public function post_updateSurvey(){
        try {
            if ($this->user['sys_role']['role_lv_cd'] == 10) {
                throw new Exception(Lang::get('exception_msg.' . ExceptionCode::E_NOT_HAVE_PERMISSION), ExceptionCode::E_NOT_HAVE_PERMISSION);
            }
            
            $survey = Input::post('survey');
            $visit_hist_id = Input::post('visit_hist_id');
            
            $val = Validation::forge();
            $val->add_callable('MyRules');
            
            $val->add_field('num_of_emp_cd', Lang::get('label.num_of_emp_cd'), 'required');
            $val->add_field('plan_of_change_cd', Lang::get('label.plan_of_change_cd'), 'required');
            $val->add_field('plan_of_increasement_cd', Lang::get('label.plan_of_increasement_cd'), 'required');
            
            $val->add_field('electric_billing_source_cd', Lang::get('label.electric_billing_source_cd'), 'required');
            $val->add_field('electric_comp_cd', Lang::get('label.electric_comp_cd'), 'required');
            $val->add_field('electric_contract_type_cd', Lang::get('label.electric_contract_type_cd'), 'required');
            
            $val->add_field('electric_contract_type_other', Lang::get('label.electric_contract_type_other'), []);
            $val->add_field('electric_billing_amount_cd', Lang::get('label.electric_billing_amount_cd'), 'required');
            $val->add_field('electric_num_of_light_cd', Lang::get('label.electric_num_of_light_cd'), 'required');
            
            $val->add_field('electric_led_changed_cd', Lang::get('label.electric_led_changed_cd'), 'required');
            $val->add_field('mobile_carrier_cd', Lang::get('label.mobile_carrier_cd'), 'required');
            $val->add_field('mobile_billing_amount_cd', Lang::get('label.mobile_billing_amount_cd'), 'required');
            
            $val->add_field('aircon_num_of_year_cd', Lang::get('label.aircon_num_of_year_cd'), 'required');
            $val->add_field('aircon_plan_of_change_cd', Lang::get('label.aircon_plan_of_change_cd'), 'required');
            $val->add_field('camera_usage_cd', Lang::get('label.camera_usage_cd'), 'required');
            
            $val->add_field('ibanking_usage_cd', Lang::get('label.ibanking_usage_cd'), 'required');
            $val->add_field('hp_cd', Lang::get('label.hp_cd'), 'required');
            $val->add_field('gas_comp_cd', Lang::get('label.gas_comp_cd'), 'required');
            $val->add_field('sale_support_sys_cd', Lang::get('label.sale_support_sys_cd'), 'required');
            $val->add_field('mobile_carrier_other', Lang::get('label.mobile_carrier_other'), []);
            
            if (!$val->run($survey)) {
                // $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, Lang::get('exception_msg.NOT_INPUT_ALL'));
                return $this->response($this->resp);
            } 
            
            $arrInput = $val->validated();
            
            // Click "other" of mobile_carrier_cd but no input
            if($arrInput['mobile_carrier_cd']=='05' && empty($arrInput['mobile_carrier_other'])) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, Lang::get('exception_msg.NOT_INPUT_ALL'));
                return $this->response($this->resp);
            }
            
            // No tick any item of electric_contract_type_cd
            if(count($arrInput['electric_contract_type_cd'])==1 && empty($arrInput['electric_contract_type_cd'][0])) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, Lang::get('exception_msg.NOT_INPUT_ALL'));
                return $this->response($this->resp);
            }
            
            // Click "other" of electric_contract_type_cd but no input
            if(count($arrInput['electric_contract_type_cd'] > 0) && in_array('04', $arrInput['electric_contract_type_cd'])) {
                if(empty($arrInput['electric_contract_type_other'])) {
                    $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, Lang::get('exception_msg.NOT_INPUT_ALL'));
                    return $this->response($this->resp);
                }
            }
                        
            $objSurvey = Model_Base_Core::getOne('Model_TApoOtokulineSurvey', [
                'select' => ['id', 'visit_hist_id'],
                'where' => [['del_flg', '=', 0], ['visit_hist_id', '=', $visit_hist_id]]
            ]);
            if ($objSurvey === false) {
                throw new Exception();
            }
            if(!empty($arrInput['electric_contract_type_cd'])) {
                $arrInput['electric_contract_type_cd'] = implode(",", $arrInput['electric_contract_type_cd']);
            }
            if (empty($objSurvey['id'])) {
                $arrInput['visit_hist_id'] = $visit_hist_id; 
                if (!Model_Base_Core::insert('Model_TApoOtokulineSurvey', $arrInput)) {
                    throw new Exception();
                }
            } else {
                if (!Model_Base_Core::update('Model_TApoOtokulineSurvey', $objSurvey['id'], $arrInput)) {
                    throw new Exception();
                }
            }
            
            $this->resp(Lang::get($this->lang . '.success'), null, []);
            
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage(). ':' . $e->getLine();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    
    public function post_resultstatisticOtokuline() {
      ini_set('memory_limit', -1);
      set_time_limit(0);        
      try {
          $val = Validation::forge();
          $val->add_callable('MyRules');
          $val->add_field('create_dt_from', Lang::get('label.create_dt_from'), 'required');
          $val->add_field('create_dt_to', Lang::get('label.create_dt_to'), 'required');

          if (!$val->run()) {
              $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
              return $this->response($this->resp);
          }
          $arrInput = $val->validated();
          $dateWhere = [];
          
          $arrInput['create_time'] = $arrInput['create_dt_from'] . '...' . $arrInput['create_dt_to'];
          $arrInput['create_time'] = str_replace('/', '-', $arrInput['create_time']);


          $arrCommon = [
            'survey_num_of_emp', 'survey_plan_of_change', 'survey_plan_of_increasement'
            , 'survey_electric_billing_source', 'survey_electric_comp', 'survey_electric_contract_type'
            , 'survey_electric_billing_amount', 'survey_electric_num_of_light', 'survey_electric_led_changed'
            , 'survey_mobile_carrier', 'survey_mobile_billing_amount', 'survey_aircon_num_of_year'
            , 'survey_aircon_plan_of_change', 'survey_camera_usage', 'survey_ibanking_usage'
            , 'survey_hp', 'survey_gas_comp','survey_sale_support_sys'
          ];
          
          $arrCommonMaster = $arrResult = Model_Base_Core::getAll('Model_MCommonMst', [
            'select' => ['mst_id', 'code_value','disp_value'],
            'where' => [
                ['mst_id', 'IN', $arrCommon],
                ['del_flg' => 0]],
          ]);

          if ($arrCommonMaster === false) {
            throw new Exception();
          }

          $arrDataCommon = [];
          foreach ($arrCommonMaster as $master) {
              $arrDataCommon[$master['mst_id']][$master['code_value']] = $master['disp_value'];
          }
          unset($arrCommonMaster);


          $arrDate = [];
          if ($arrInput['create_time'] != '') {
              $arrDate = explode('...', $arrInput['create_time']);
              $dateWhere[] = ['create_time', 'between', $arrDate];
          }

          $otokuResult = DB::select( '*' )
          ->from(DB::expr('t_apo_otokuline_survey as o'))
          ->where($dateWhere)
          ->execute()->as_array();

          $arrOtokuline = [];


          $arrResult = [
            'total' => count($otokuResult),
            'num_of_emp_cd' => [

            ]
          ];
          
          // var_dump($arrDataCommon['survey_num_of_emp']);die;
          foreach($otokuResult as $row){
            //survey_num_of_emp
            foreach($row as $col => $value){
              if($col == 'num_of_emp_cd'){
                $i = 0;
                foreach ($arrDataCommon['survey_num_of_emp'] as $codeVal => $dispVal) {
                    if($value != $codeVal) {
                      $arrResult[$col][$dispVal] = $i;
                    } else {
                      $arrResult[$col][$dispVal] = $i++;
                    }
                                   
                }
              }; 
              
              // if($col == 'plan_of_change_cd'){
              //   foreach ($arrDataCommon['survey_plan_of_change'] as $codeVal => $dispVal) {
              //       $arrResult[$col] = [
              //         $dispVal => $codeVal
              //       ];                  
              //   }
              // }  

            }
          }          
          var_dump($arrResult);die;
         
        $this->resp(null, null, $otokuResult);
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
            $visitHistId = Input::get('visit_hist_id');
            if (!Model_Base_Core::validField('Model_TApoVisitHist', 'id', $visitHistId)) {
                throw new Exception(Lang::get($this->lang . '.not_exist'), ExceptionCode::E_APP_ERROR_COMMON);
            }

            $apoVisitHist = DB::select_array(array(
                        'otokuline.*',
                        DB::expr('visit_hist.id as apo_visit_hist_id'), DB::expr('visit_hist.cust_id'),
                        DB::expr('visit_hist.consider_to_ng_date as visit_hist_consider_to_ng_date'), DB::expr('visit_hist.consider_to_ng_content as visit_hist_consider_to_ng_content'),
                        DB::expr('consider.consider_class_flg as consider_consider_class_flg'), DB::expr('consider.expected_rank_cd as consider_expected_rank_cd'),
                        DB::expr('consider.expected_l as consider_expected_l'),
                        DB::expr("DATE_FORMAT(consider.reply_waiting_date,'%Y/%m/%d') AS consider_reply_waiting_date "),
                        DB::expr('consider.consider_content as consider_consider_content'), DB::expr('consider.send_flg as consider_send_flg'),
                        DB::expr('consider.id as consider_id'), DB::expr('consider_to_ng.ng_type_cd as consider_to_ng_ng_type_cd'),
                        DB::expr("DATE_FORMAT(consider_to_ng.consider_to_ng_date,'%Y/%m/%d') AS consider_to_ng_consider_to_ng_date "),
                        DB::expr('consider_to_ng.consider_to_ng_content as consider_to_ng_consider_to_ng_content')
                    ))
                    ->from(DB::expr('t_apo_visit_hist as visit_hist'))
                    ->join(DB::expr('t_apo_otokuline_hist as otokuline'), 'LEFT')
                    ->on('visit_hist.id', '=', 'otokuline.visit_hist_id')
                    ->join(DB::expr("(SELECT  id,visit_hist_id,consider_class_flg,expected_rank_cd,expected_l,reply_waiting_date,consider_content,send_flg FROM t_apo_consider_list WHERE del_flg = 0 AND consider_sale_flg = {$this->_productId}) as consider"), 'LEFT')
                    ->on('visit_hist.id', '=', 'consider.visit_hist_id')
                    ->join(DB::expr("(SELECT visit_hist_id,apo_gs_type,ng_type_cd,consider_to_ng_date,consider_to_ng_content  FROM t_apo_consider_to_ng_hist WHERE del_flg = 0 AND apo_gs_type ={$this->_productId}) as consider_to_ng"), 'LEFT')
                    ->on('otokuline.visit_hist_id', '=', 'consider_to_ng.visit_hist_id')
                    ->where('visit_hist.id', '=', $visitHistId)
                    //->where('otokuline.del_flg', '=', 0)
                    ->group_by('apo_visit_hist_id')->execute()->current();
            if ($apoVisitHist === false) {
                throw new Exception();
            }

            foreach ($apoVisitHist as $f => $v) {
                if ($f == 'change_work_date' || $f === 'new_establish_date' || $f === 'consult_date' || $f === 'calculated_date' || $f == 'otoku_wifi_send_dt') {
                    $apoVisitHist[$f] = Model_Service_Util::convertDateTimeFormat($v, 'Y-m-d H:i:s', 'Y/m/d');
                } else if ($f === 'change_work_time') {
                    $apoVisitHist[$f] = Model_Service_Util::convertDateTimeFormat($v, 'H:i:s', 'H:i');
                }
            }

            /* client_base_line */
            $arrClientBaseLine = DB::select_array(['*'])
                    ->from('t_client_base_line')
                    ->where('cust_id', $apoVisitHist['cust_id'])
                    ->where('del_flg', 0)
                    ->order_by(DB::expr('-display_order'), 'DESC')
                    ->order_by('id', 'ASC')
                    ->execute()->as_array();
            foreach ($arrClientBaseLine as $i => $row) {
                $arrClientBaseLine[$i]['ecc_day'] = Model_Service_Util::convertDateTimeFormat($row['ecc_day'], 'Y-m-d H:i:s', 'Y/m/d');
            }
            $apoVisitHist['client_base_line'] = $arrClientBaseLine;
            
            /* t_apo_otokuline_survey */
            $survey = DB::select_array(['*'])
                    ->from('t_apo_otokuline_survey')
                    ->where('visit_hist_id', $visitHistId)
                    ->where('del_flg', 0)
                    ->order_by('id', 'ASC')
                    ->execute()->current();
            $apoVisitHist['survey'] = $survey;
            
            $this->resp(null, null, $apoVisitHist);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }


    public function get_consultinfo(){
        try {
            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('otokuline_hist_id', Lang::get('label.otokuline_hist_id'), 'valid_field[Model_TApoOtokulineHist,id]');
            if (!$val->run(Input::get())) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }

            $arrInput = $val->validated();
            $arrConsultInfo = null;
            if(!empty($arrInput['otokuline_hist_id'])){
                $arrConsultInfo= DB::select_array(array(
                        'has_consult_cust', DB::expr("DATE_FORMAT(consult_confirm_dt,'%Y/%m/%d') AS consult_confirm_dt"), 'cust_confirm_pic_id', 'mail_address', 'biz_market', 'sms_flg',
                        'consult_detail', 'confirm_progress_cd', 'support__flg'
                    ))
                    ->from(DB::expr('t_apo_consult_info'))
                    ->where('apo_otokuline_hist_id', '=', $arrInput['otokuline_hist_id'])
                    ->where('del_flg', '=', 0)
                    ->execute()->current();
                if ($arrConsultInfo === false) {
                    throw new Exception();
                }
            }

            $this->resp(null, null, $arrConsultInfo);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }

        return $this->response($this->resp);
    }

    public function post_updateconsultinfo()
    {
        try {
            if ($this->user['sys_role']['role_lv_cd'] == 10) {
                throw new Exception(Lang::get('exception_msg.' . ExceptionCode::E_NOT_HAVE_PERMISSION), ExceptionCode::E_NOT_HAVE_PERMISSION);
            }
            DB::start_transaction();

            $val = Validation::forge();
            $val->add_callable('MyRules');
            $val->add_field('visit_hist_id', Lang::get('label.visit_hist_id'), 'required|valid_field[Model_TApoVisitHist,id]');
            $val->add_field('otokuline_hist_id', Lang::get('label.otokuline_hist_id'), 'valid_field[Model_TApoOtokulineHist,id]');
            $val->add_field('has_consult_cust', Lang::get('label.has_consult_cust'), 'valid_numeric');
            $val->add_field('consult_confirm_dt', Lang::get('label.consult_confirm_dt'), []);
            $val->add_field('cust_confirm_pic_id', Lang::get('label.cust_confirm_pic_id'), 'valid_field[Model_MEmployee,emp_id]');
            $val->add_field('mail_address', Lang::get('label.mail_address'), 'valid_email');
            $val->add_field('biz_market', Lang::get('label.biz_market'), 'valid_numeric');
            $val->add_field('sms_flg', Lang::get('label.sms_flg'), 'valid_numeric');
            $val->add_field('consult_detail', Lang::get('label.consult_detail'), 'max_length[2000]');
            $val->add_field('confirm_progress_cd', Lang::get('label.confirm_progress_cd'), 'max_length[2]');
            $val->add_field('support__flg', Lang::get('label.support__flg'), 'max_length[2]');



            if (!$val->run()) {
                $this->resp(null, ExceptionCode::E_VALIDATION_ERROR_FIELD, $val->error_message());
                return $this->response($this->resp);
            }
            $arrInput = $val->validated();
            
            $arrApoVisitHist = Model_Base_Core::getOne('Model_TApoVisitHist', [
                'select' => ['id','list_user_id','cust_id'],
                'where' =>  [['del_flg', '=', 0], ['id', '=', $arrInput['visit_hist_id']]]
            ]);
            if(empty($arrApoVisitHist)){
                throw new Exception();
            }

            $arrOtokuline = Model_Base_Core::getOne('Model_TApoOtokulineHist', [
                'select' => ['id'],
                'where' =>  [['del_flg', '=', 0], ['visit_hist_id', '=', $arrInput['visit_hist_id']]]
            ]);

            $iOtokulineHistId = !empty($arrOtokuline)?$arrOtokuline['id']:0;

            $arrUpdateConsultInfo = [
                'has_consult_cust' => isset($arrInput['has_consult_cust']) ? $arrInput['has_consult_cust'] : null,
                'consult_confirm_dt' => !empty($arrInput['consult_confirm_dt']) ? \Model_Service_Util::convertDateTimeFormat($arrInput['consult_confirm_dt'], 'Y/m/d', 'Y-m-d') : null,
                'cust_confirm_pic_id' => isset($arrInput['cust_confirm_pic_id']) ? $arrInput['cust_confirm_pic_id'] : null,
                'mail_address' => (isset($arrInput['mail_address']) && rtrim($arrInput['mail_address']) != '') ? $arrInput['mail_address'] : null,
                'biz_market' => !empty($arrInput['biz_market']) ? $arrInput['biz_market'] : null,
                'sms_flg' => !empty($arrInput['sms_flg']) ? $arrInput['sms_flg'] : null,
                'consult_detail' => isset($arrInput['consult_detail']) ? $arrInput['consult_detail'] : null,
                'confirm_progress_cd' => isset($arrInput['confirm_progress_cd']) ? $arrInput['confirm_progress_cd'] : null,
                'support__flg' => isset($arrInput['support__flg']) ? $arrInput['support__flg'] : null,
                'apo_otokuline_hist_id' => !empty($iOtokulineHistId) ? $iOtokulineHistId : null,
                'update_user_id' => $this ->user['emp_id'],
                'update_time' => date('Y-m-d H:i:s', \Date::forge()->get_timestamp())
            ];

            if ( !empty($iOtokulineHistId) && Model_Base_Core::validField('Model_TApoConsultInfo', 'apo_otokuline_hist_id', $iOtokulineHistId)) {
                if (!Model_Base_Core::updateByWhere('Model_TApoConsultInfo', [['apo_otokuline_hist_id', '=', $iOtokulineHistId]], $arrUpdateConsultInfo)) {
                    throw new Exception();
                }
            }else{
                // not exit otokulinehist -> create new otokuline highlight_string
                if (empty($iOtokulineHistId) ||  !Model_Base_Core::validField('Model_TApoOtokulineHist', 'id', $iOtokulineHistId)) {
                    $arrInsertOtokuline = ['visit_hist_id' => $arrInput['visit_hist_id'],'list_user_id' => $arrApoVisitHist['list_user_id'],'cust_id' => $arrApoVisitHist['cust_id']];
                    if(!$iOtokulineHistId =  Model_Base_Core::insert('Model_TApoOtokulineHist',$arrInsertOtokuline)){
                        throw new Exception();
                    }
                    $arrInsertSaleHist = ['visit_hist_id' => $arrInput['visit_hist_id'], 'otokuline_hist_id' => $iOtokulineHistId];
                    if(!Model_Base_Core::insert('Model_TApoSalesHist',$arrInsertSaleHist)){
                        throw new Exception();
                    }
                }
                $arrUpdateConsultInfo['apo_otokuline_hist_id'] = $iOtokulineHistId;
                if (!Model_Base_Core::insert('Model_TApoConsultInfo', $arrUpdateConsultInfo)) {
                    throw new Exception();
                }
                
            }
            $this->resp();
            DB::commit_transaction();
        }
        catch (Exception $e) {
            DB::rollback_transaction();
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);

    }



}

//t_apo_otokuline_survey