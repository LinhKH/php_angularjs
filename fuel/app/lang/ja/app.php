<?php

return [
    'title' => 'コールシステム',
    'label' => [
        // common
        'id' => 'ID',
        'ids' => 'IDs',
        'is_update' => '更新',
        'file_content' => 'ファイル内容',
        'type' => 'タイプ',
        'record_no' => 'レコード数',
        'filename' => 'ファイル名',
        'format' => 'format',
        'ecc_div_member_flg' => 'ECC振分け担当フラグ',
        'query' => '検索条件',
        'only_show_error' => 'エラーのみ表示',
        // m_common_mst
        'mst_id' => 'マスタID',
        'mst_nm' => 'マスタ名',
        'elem_change_ok_flg' => '要素変更可否フラグ',
        'code_len' => 'コード桁数',
        'code_value' => 'コード値',
        'disp_value' => '表示値',
        'sort_value' => 'ソート順',
        // m_sys_role
        'system_role_cd' => '業務役割',
        'role_lv_cd' => '権限レベル',
        // m_employee
        'user_id' => 'ユーザー',
        'emp_id' => '社員',
        'emp_ids' => '社員',
        'forgot_token' => 'コード',
        'password' => 'パスワード',
        'password_confirm' => '再度入力',
        'email' => 'メールアドレス',
        'active' => '有効フラグ',
        'menu_priv_id' => 'メニュー権限マスタ',
        'manage_unit_cd' => '管理ユニット',
        'distribute_dept_cd' => '割振担当部署',
        'statistic_dept_cd' => 'コール集計表示部署',
        // m_org
        'org_cd' => '組織',
        'org_nm' => '組織名',
        'org_type_cd' => '組織区分',
        'parent_org_cd' => '親組織',
        'sales_dept_flg' => '営業部門',
        'main_sale_item_cd' => '主商材',
        // m_resp_area
        'pref_cd' => '都道府県',
        'pref_nm' => '都道府県名',
        'city_nm' => '市区町村',
        'ns_dept_cd' => 'NSユニット',
        'ms_dept_cd' => 'MSユニット',
        // m_l_list_type
        'list_l_type_cd' => 'リスト大区分',
        'list_l_type_nm' => 'リスト大区分名',
        // m_m_list_type
        'list_m_type_cd' => 'リスト中区分',
        'list_m_type_nm' => 'リスト中区分名',
        // m_s_list_type
        'list_s_type_cd' => 'リスト小区分',
        'list_s_type_nm' => 'リスト小区分名',
        // m_c_maker_master
        'maker_nm' => 'メーカー名',
        // m_c_model_master
        'maker_master_id' => 'メーカーマスタ',
        'maker_prod_type_cd' => 'メーカー商品',
        'product_nm' => '商品名',
        'maker_prod_nm' => '仕入れ金額',
        'purchase_amount' => '税区分',
        'terminal_type' => '端末種別',
        'terminal_type_cd' => '端末種別',
        // m_device_mst
        'vendor_nm' => '販売元名',
        'product_cd' => '商品',
        'imei_cd' => 'IMEI',
        'model_nm' => '機種名',
        'color_nm' => 'カラー名',
        'make_start_dt' => '製造開始日',
        'make_stop_dt' => '製造停止日',
        'import_limit' => '入荷不可端末',
        // m_post
        'position_cd' => '役職',
        'position_nm' => '役職名',
        // m_apo_schedule_color
        'sche_color_cd' => 'スケジュールカラー',
        'color_nm' => 'カラー名',
        'frame_color_value' => 'フレームカラー',
        'background_color_value' => '背景カラー',
        'text_color_value' => '文字カラー',
        'text_font_style' => 'フォントスタイル',
        // m_business_type
        'business_cd' => '業種コード',
        'business_nm' => '業種名',
        'parent_business_cd' => '親業種コード',
        'business_type_cd' => '業種タイプコード',
        // m_bank
        'bank_cd' => '銀行コード',
        'bank_nm' => '銀行名',
        'bank_kana_nm' => '銀行名カナ',
        // m_bank_office
        'bank_cd' => '銀行コード',
        'bank_office_cd' => '支店コード',
        'bank_office_nm' => '支店名',
        'bank_office_kana_nm' => '支店名カナ',
        // m_pm_service
        'service_cd' => 'サービスコード',
        'service_nm' => 'サービス名',
        'require_detail_num_flg' => '詳細番号必須flg',
        'contract_file' => '規約',
        // m_pm_campaign
        'service_cd' => 'サービスコード',
        'campaign_cd' => 'キャンペーンコード',
        'campaign_nm' => 'キャンペーン名',
        // m_pm_detail
        'campaign_cd' => 'キャンペーンコード',
        'detail_cd' => '詳細区分コード',
        'detail_nm' => '詳細名',
        // Provid List
        'provide_list_id' => '提供元リスト',
        'list_provider_cd' => 'リスト提供元名',
        'note' => '備考',
        'call_call_cd' => '架電呼称',
        'carr_l_type_cd' => 'キャリア大区分',
        'carr_m_type_cd' => 'キャリア中区分',
        'list_nm' => 'リスト名',
        'list_memo' => 'リストメモ',
        'prefer_provided_list' => '精査中リスト',
        // Mapping template
        'template_id' => 'テンプレートID',
        'template_nm' => 'テンプレート名',
        'mapping_template_id' => 'マッピングテンプレートID',
        'mapping_template_detail' => 'マッピングテンプレート詳細',
        // Display Layout
        'disp_layout_nm' => '表示テンプレート名',
        'disp_layout_id' => '表示テンプレートID',
        // Checking List
        'column' => '任意文字',
        'provided_list_id' => '提供元リスト名',
        'checking_list' => '精査中リスト',
        'checking_list_id' => '精査中リストID',
        'checking_list_ids' => '精査中リストID',
        'inquire_tel_no' => '照会電話番号',
        'class_round' => '級地',
        'line_type_cd' => '回線種別コード',
        'no_portal_support' => '番号ポータル対応',
        'station_cd' => '局舎コード',
        'ins_port' => 'INS',
        'ntt_housing_station_nm' => 'NTT収容局舎名',
        'apo_ok_flg' => 'アポ可否フラグ',
        'analog_port' => 'アナログ',
        'conv_fiber_opt' => '光化情報',
        'check_result_cd' => '精査結果コード',
        'dest_type' => '投下先タイプ',
        'dest_cd' => '投下先コード',
        'dest_nm' => '投下先名',
        // Client Base
        'cust_id' => '顧客基本ID',
        'list_nm_id' => 'リスト名ID',
        'call_list_user_id' => 'リスト名ID',
        'tel_no' => '電話番号',
        'tel_no_num_only' => '電話番号数字のみ',
        'company_nm' => '会社名',
        'company_kana_nm' => '会社カナ名',
        'address_3rd' => '住所３',
        'fax_no' => 'FAX番号',
        'shop_num' => '店舗数',
        'cust_num_of_emp' => '従業員数',
        'reg_holiday_cd' => '定休日',
        'free_dial' => 'フリーダイヤル',
        'url' => 'URL',
        'url_media' => 'URL(媒体）',
        'open_from_time' => '営業時間（から）',
        'open_to_time' => '営業時間（まで）',
        'break_from_time' => '休業時間（から）',
        'break_to_time' => '休業時間（まで）',
        'represents_nm' => '代表者名',
        'info_update_flg' => '新設更新フラグ',
        'info_expansion_flg' => '新設増設フラグ',
        // Client Base Pic
        'pic_nm' => '担当者名',
        'sex_cd' => '性別',
        'app_ok_type_cd' => '決済',
        'call_name_cd' => '呼称',
        'mail_address' => 'メールアドレス',
        // t_list_user
        'sale_channel_cd' => '販売ﾁｬﾝﾈﾙ',
        'list_use_div_cd' => '運用事業部',
        'list_use_dept_cd' => '運用所属部',
        'list_use_emp_id' => '運用者',
        // Contact hist
        'business_memo' => '経営者FLG',
        'contact_result_cd' => 'コール結果',
        // Expect
        'expected_rank_cd' => '見込みランク',
        're_contact_dt' => '再コンタクト予定日',
        're_contact_time' => '再コンタクト予定時間',
        'expected_cnt' => '見込数',
        // Vision Apokin
        'vision_apokin_id' => 'ビジョンAPO禁ID',
        'list_user_id' => 'リスト運用者ID',
        'apokin_kind_type_cd' => 'APO禁種別コード',
        'apokin_type_cd' => 'APO禁区分コード',
        'apokin_sale_item_cd' => 'APO禁商材コード',
        'apokin_detail' => 'APO禁内容',
        'apokin_range_type_cd' => 'APO禁範囲区分コード',
        'apokin_app_type_cd' => 'APO禁承認区分コード',
        'company_tel' => '担当者連絡先',
        // t_apo_visit_hit
        'visit_dt' => '訪問日',
        'visit_from_time' => '訪問時間From',
        'visit_to_time' => '訪問時間To',
        'div_cd' => '営業事業部',
        'dept_cd' => '営業所属部',
        'apo_rank_cd' => 'APOランク',
        'apo_div_cd' => 'APO事業',
        'apo_dept_cd' => 'APO所属',
        'apo_pic_id' => 'APO担当',
        'tm_account_dept_cd' => 'TM計上部門',
        'sales_div_cd' => '営業事業部',
        'sales_dept_cd' => '営業所属部',
        'calculate_plan_date' => '計上予定日',
        'correspondence_date' => '訪問日',
        'correspondence_from' => '訪問時間From',
        'correspondence_to' => '訪問時間To',
        'esca_apo_div_cd' => 'エスカ先事業部',
        'esca_apo_dept_cd' => 'エスカ先所属部',
        'visit_company_nm' => '訪問先会社名',
        'esca_rank' => 'エスカランク',
        'zip_cd' => '郵便番号',
        'visit_pic_id' => '訪問先担当者',
        'sale_item_cd' => '同行商材',
        'sale_item_nm' => '同行商材名',
        'parent_sale_item_cd' => '親商材コード',
        'apo_content' => 'APO内容',
        'sale_item_cd_introduce' => '商材',
        'apo_type_cd' => '販売チャネル',
        'visit_hist_id' => 'APO詳細ID',
        'company_no' => '法人番号',
        'visit_count' => '訪問カウント',
        'sales_pic_emp_id' => '営業担当者ID',
        'corp_type_cd' => '法人区分コード',
        'mail_send_pic_nm' => '送信者',
        'introcude_company' => '紹介元会社名',
        'introduce_div_cd' => '協力事業部',
        'introduce_dept_cd' => '協力所属部',
        'introduce_pic_id' => '協力営業担当',
        'introduce_apo_pic_id' => '協力APO担当',
        'introduce_profit_dept_cd' => '協力TM計上部門',
        'new_customer_flg' => '新規・既存',
        'calculate_plan_date_from' => '計上予定日',
        'calculate_plan_date_to' => '計上予定日To',
        //esca
        'esca_sale_item_cd' => '商材',
        //t_apo_reconfirm_hist
        'reconfirm_result' => '詰め直し結果',
        'responder_nm' => '応答者',
        'apo_rank_cd' => 'APOランク',
        'reconfirm_content' => '詰め直し内容',
        'apo_memo' => 'APOメモ',
        'order_memo' => '受注メモ',
        'ng_type_cd' => 'NG区分',
        'reconfirm_ng_detail' => '詰め直し内容',
        'revisit_reason' => '再訪問理由',
        'reconfirm_recontact_type' => '再コンタクト種別',
        // recontact
        'recontact_from_time' => '再コンタクト予定時間',
        'recontact_to_time' => '再コンタクト予定時間To',
        //otokuline
        'sugestion_product' => '提案種別',
        'sales_result' => '営業結果',
        'class1_ac_cnt' => 'クラス１AC数',
        'class1_ins_cnt' => 'クラス１INS',
        'class1_ana_cnt' => 'クラス１ANA',
        'class2_ac_cnt' => 'クラス2AC数',
        'class2_ins_cnt' => 'クラス2INS',
        'class2_ana_cnt' => 'クラス2ANA',
        'class3_ac_cnt' => 'クラス3AC数',
        'class3_ins_cnt' => 'クラス3INS',
        'class3_ana_cnt' => 'クラス3ANA',
        'reduce_amount' => 'おとくライン導入削減額',
        'ys_line_num' => 'YAHOO!SOHO回線数',
        'atoi_num' => 'ANA→INS数',
        'regist_num' => '該当申番',
        'detail_ins' => '明細書INS',
        'detail_ana' => '明細書ANA',
        'detail_anatoins' => '明細書切替',
        'white_line_ins' => 'ホワイトライン-INS',
        'white_line_ana' => 'ホワイトライン-ANA',
        '1en_ins' => '1円/分-INS',
        '1en_ana' => '1円/分-ANA',
        'other_3year' => 'その他-3年割',
        'sbm_ins_has' => 'SBM有-INS',
        'sbm_ana_has' => 'SBM有-ANA',
        'sbm_ins_no' => 'SBM無-INS',
        'sbm_ana_no' => 'SBM無-ANA',
        'expected_l' => '見込L',
        'consider_class_flg' => '検討区分',
        'reply_waiting_date' => '返待ち日',
        'giga_hastel' => 'ギガTEL有',
        'giga_notel' => 'ギガTEL無',
        'giga_sum' => 'ギガ合計',
        'ocn_hastel' => 'OCNTEL有',
        'ocn_notel' => 'OCNTEL無',
        'ocn_sum' => 'OCN合計',
        'sb_hastel' => 'SBTEL有',
        'sb_notel' => 'SBTEL無',
        'sb_sum' => 'SB合計',
        'otoku_wifi_no' => '台数',
        'otoku_wifi_send_dt' => '送付日',
        'hikari_korabo_ok_no' => 'ひかりコラボOK数',
        'direct_decision' => '即決',
        'otokuline_hist_id' => 'おとくライン ID',
        // ichiran
        'contact_result' => 'コンタクト結果＋内容',
        'expected' => '見込み ',
        're_contact_time' => '再コンタクト日時',
        'list_person_name' => 'リスト者名',
        // forwardbalancing
        'caculate_type' => '計上',
        //mobile visit hist
        'mobile_visit_hist_sales_result' => '営業結果',
        // t_apo_oa_hist
        'direct_consult_flg' => '即決',
        //t_apo_utm_hist
        'utm_app_no' => '申込み台数',
        'utm_lease_cost' => 'リース金額',
        'utm_pay_no' => '支払回数',
        'utm_maintenance_cost' => '保守金額',
        'utm_profit_cost' => '粗利金額',
        'calculated_date' => '計上日',
        'utm_detail' => '詳細',
        //t_apo_led_hist
        //'item_cd' => '商品',
        'pay_method' => '支払方法',
        'pay_period' => '支払期間',
        'construction_cost' => '工事代金',
        'maker_nm' => 'メーカー',
        'rental_rank' => 'ランク',
        'item_nm' => '商品',
        'amount' => '数量',
        'rental_amount' => 'レンタル料金',
        'calculated_date' => '計上日',
        // master callname
        'call_name_nm' => '呼称名',
        'parent_call_name_cd' => '親呼称',
        //master companyapokin
        'delete_result' => '削除区分',
        //t_apo_consult_info
        'has_consult_cust' => '直コン有無',
        'consult_confirm_dt' => '直コン顧客確認日',
        'cust_confirm_pic_id' => '顧客確認者',
        'ntt_apokin' => 'NTT-com APO禁依頼',
        'ntt_apokin_sr_confirm' => 'NTT-com APO禁 SR確認',
        'ntt_apokin_cust_acknowledge' => 'NTT-com APO禁 顧客認識',
        'consult_detail' => '対応詳細',
        //t_apo_pm_hist
        'pm_flg' => '販促',
        'pm_app_flg' => '販促使用申請有無',
        'pm_approver_div_cd' => '販促利用了承者-事業部',
        'pm_approver_dept_cd' => '販促利用了承者-所属部',
        'pm_approver_pic_id' => '販促利用了承者-担当者',
        'otameshi_no' => 'おとくライン明細キャンペーン-個数',
        'otameshi_amount' => 'おとくライン明細キャンペーン-金額',
        'cashback_no' => 'キャッシュバックキャンペーン-個数',
        'cashback_amount' => 'キャッシュバックキャンペーン-金額',
        'construction_no' => '工事費負担キャンペーン-個数',
        'construction_amount' => '工事費負担キャンペーン-金額',
        'introduce_no' => '紹介キャンペーン-個数',
        'introduce_amount' => '紹介キャンペーン-金額',
        'mono_no' => 'MONO-個数',
        'mono_amount' => 'MONO-金額',
        'sheria3000_no' => 'シェリア3000-個数',
        'sheria3000_amount' => 'シェリア3000-金額',
        'sheria5000_no' => 'シェリア5000-個数',
        'sheria5000_amount' => 'シェリア5000-金額',
        'others_no' => '販促費その他-個数',
        'others_amount' => '販促費その他-金額',
        'm_list_compname_apokin_type' => '会社名APO禁登録',
        // apo_mobile_hist
        'garasapo_new' => '新規',
        'garasapo_add' => '買増',
        'garasapo_single' => '単独',
        'head_discount_num' => '店頭割引-数',
        'head_discount_amount' => '店頭割引-金額',
        'direct_decision' => '即決',
        //t_apo_sub_item_hist
        'fukushozai_sub_sale_item_cd' => '商材選択',
        'fukushozai_sales_result' => '営業結果',
        'fukushozai_calculated_dt' => '計上日',
        //t_apo_appli_change
        'app_zip_cd' => '郵便番号',
        'app_pref_cd' => '都道府県コード',
        'app_city_nm' => '市区町村',
        'app_address_3rd' => '住所3',
        'app_company_tel' => '連絡先',
        'app_corp_type_cd' => '法人区分コード',
        'application_company_nm' => '申込み会社名',
        'application_pic_nm' => '申込担当者名',
        'app_sex_cd' => '性別コード',
        'app_position_cd' => '役職コード',
        'app_call_name_cd' => '呼称コード',
        // garasapo_plan
        'plan_cd' => 'プランコード',
        'plan_nm' => 'プラン名',
        'price' => '単価',
        // led sale item
        'l_item_nm' => '大項目',
        'supplier_nm' => '仕入先',
        'maker_nm' => 'メーカー',
        'item_cd' => '商品CD',
        'item_nm' => '商品名',
        'item_formal_nm' => '正式品名',
        'required_select_flg' => '選択必須有無',
        'rental_period' => 'レンタル期間',
        'rental_rank' => 'レンタルランク',
        'rental_amount' => 'レンタル料',
        'standard_price' => '標準販売価格',
        'wholesale_price' => '卸値',
        'rough_price' => '粗利単価',
        'note' => '補足事項',
        'model_no' => '型番',
        //haruene sale item
        'haruene_plan_cd' => 'プラン',
        'contact_dept_cd' => '部署',
        'contact_div_cd' => '事業部',
        'gross_profit' => '総粗利',
        //im_hp_hist
        'hp_hist_id' => 'HP関連ID',
        'hp_order_detail_id' => 'HP受注詳細ID',
        //t_pm_application
        'status' => 'ステータス',
        'mail' => 'Mail',
        //t_pm_account
        'account_nm' => '口座名義',
        'account_nm_kana' => '口座名義カナ',
        'bank_account' => '口座番号',
        'account_type_cd' => '口座タイプ',
        //t_pm_order
        'campaignList' => 'キャンペーン',
        'order_cd' => '申込番号',
        'amount_sum' => 'キャンペーン合計',
        't_pm_detail' => '詳細',
        //t_pm_detail
        'categoryList' => '区分',
        'detail_category_cd' => '詳細区分',
        // if_base
        'if_sf_seq' => 'SEQ',
        'send_company_nm' => '送付会社名',
        'send_email' => '送付アドレス',
        'trade_in_type_cd' => '下取り',
        'terminal_charge_flg' => '端末代負担',
        //t_call_search_template
        'template_id' => 'テンプレートID',
        'template_nm' => 'テンプレート名',
        'query' => 'クエリ',
        //m_oa_order_item
        'maker_nm' => 'メーカー',
        'sale_item_nm' => '商品名',
        'model_nm' => '型式名',
        'model_cd' => '型式',
        //m_imhp_sale_item
        'imhp_sale_item_cd' => '商材コード',
        'imhp_sale_item_nm' => '商材名',
        'imhp_parent_sale_item_cd' => '親商材コード',
        'imhp_sale_item_type_cd' => '商材タイプ',
        //haruene_hist
        'sale_method' => '販売手法',
        'decision_flg' => '即決フラグ',
        'kan_id' => '加入権ID',
        'apl_corp_nm' => '顧客名',
        'apl_telno' => '電話番号',

        'ctr_birth' => '契約者生年月日（西暦）',
        'chrgp_birth' => '担当者生年月日（西暦）',
        'address_cd' => '住所元',
        // t_oss_mainte_report
        'serial_no' => 'シリアルNO',
        'counter1' => 'カウンター1',
        'counter2' => 'カウンター2',
        'counter2' => 'カウンター3',
        // t_oss_mainte_task
        'sign_img_url' => '顧客サインURL',
        // OA 発注情報
        'business_txt' => '業種',
        'counter_unit_price_monoblack' => 'カウンター単価モノクロ―￥',
        'counter_unit_price_color' => 'カウンター単価カラー￥',
        'counter_unit_price_monocolor' => 'カウンター単価モノカラー￥',
        'email_yn' => 'e-mail設定有無',
        'reg_address' => '登録住所',
        'head_tel' => '本社電話番号',
        // TApoOtokulineSurvey
        'num_of_emp_cd' => '従業員人数',
        'plan_of_change_cd' => '移転予定',
        'plan_of_increasement_cd' => '電話回線の増設',
        'electric_billing_source_cd' => '電気の請求元',
        'electric_comp_cd' => 'お使いの電力会社',
        'electric_contract_type_cd' => '電力の契約種別',
        'electric_contract_type_other' => '電力の契約種別＿その他',
        'electric_billing_amount_cd' => '請求金額',
        'electric_num_of_light_cd' => '蛍光灯の本数',
        'electric_led_changed_cd' => 'LEDに変更済み',
        'mobile_carrier_cd' => '携帯電話のキャリア',
        'mobile_carrier_other' => '携帯電話のキャリア＿その他',
        'mobile_billing_amount_cd' => '携帯電話の料金',
        'aircon_num_of_year_cd' => 'エアコンの年位',
        'aircon_plan_of_change_cd' => 'エアコン導入の予定',
        'camera_usage_cd' => '防犯カメラ使用',
        'ibanking_usage_cd' => 'インターネットバンキング使用',
        'hp_cd' => 'HP有無',
        'gas_comp_cd' => 'ガス会社',
        'sale_support_sys_cd' => '営業支援型コールシステム',
        // TApoHarueneSurvey
        'phone_num_of_line_cd' => '固定電話回線数',
        'phone_carrier_cd' => '固定電話のキャリア',
        'phone_billing_amount_cd' => '固定電話の請求金額',
        'internet_register_address_cd' => 'インターネットのお使い住所',
        'phone_provider_contact_cd' => 'プロバイダの契約はされてます',
        //t_apo_im_hp_order_detail
        'contract_type_cd' => '契約種別',
        'sales_item_cd' => '販売商品',
        'plan_cd' => 'プラン',
        'contract_period' => '契約期間',
        'initial_cost' => '初期費用',
        'order_amount' => '受注金額',
        'outsourcing_cost' => '外注原価',
        'purchase_company' => '仕入会社',
        'target_domain' => '対象ドメイン',
        'www_yn' => 'www有無',
        'new_or_movein' => '新規／転入',
        'raw_profit' => '粗利',
        'number' => '数',
        'payment_deadline_dt' => '支払期日',
        'payment_method_cd' => '支払方法',
        'free_period' => '無料期間',
        'division_time' => '分割回数',
        'payment_method2_cd' => '支払い方法　2回目以降',
        'next_claim_amount' => '次回請求額',
        'release_dt' => '納品予定日',
        'payment_deadline_dt' => '支払期日',
        'next_mtg_dt' => '次回打合わせ日',
        'collection_plan_dt' => '素材回収予定日',
        'dl_req_plan_dt' => 'DL依頼予定日',
        'publish_dt' => '掲載（対策）開始予定日',
        'payment_dt' => '支払期間',
        'sales_account_dept_cd' => '営業計上部門',
        'is_visit_flg' => '訪問有無',
        'visit_category_cd' => '訪問区分',
        'support_detail' => '対応内容',
        'im_apo_type_cd' => '販売チャンネル',
        'sale_channel_detail_cd' => '詳細',
        'im_apo_type_cd2' => '販売チャンネル2',
        'sale_channel_detail2_cd' => '詳細2',
        'customer_category_cd' => '顧客区分',
        'sale_item_cust_category_cd' => '商品顧客区分',
        'progress_cd' => '進捗',
        'status_cd' => 'ステータス',
        'defect_doc_cd' => '不備書類',
        'defect_detail' => '不備内容',
        'note' => '備考',
        'solved_dt' => '消去日',
        'lead_time' => 'LT',
        // t_apo_appli_change
        'represents_birthday' => '生年月日'
        
    ],
];