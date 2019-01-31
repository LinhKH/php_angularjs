<?php

return [
    'system_error' => 'システムエラー。管理者に連絡してください',
    'exception_msg' => [
        200 => 'E_OK',
        400 => 'E_BAD_REQUEST',
        401 => 'E_UNAUTHORIZED',
        402 => 'E_PAYMENT_REQUIRED',
        403 => 'E_FORBIDDEN',
        404 => 'E_NOT_FOUND',
        405 => 'E_METHOD_NOT_ALLOWED',
        500 => 'E_INTERNAL_SERVER_ERROR',
        501 => 'E_NOT_IMPLEMENTED',
        502 => 'E_BAD_GATEWAY',
        503 => 'E_SERVICE_UNAVAILABLE',
        504 => 'E_GATEWAY_TIMEOUT',
        505 => 'E_HTTP_VERSION_NOT_SUPPORTED',
        3000 => 'E_VALIDATION_ERROR_FIELD',
        4000 => 'E_ELASTIC_ERROR_CONNECT',
        4001 => 'E_ELASTIC_ERROR_PARAM',
        5000 => 'E_APP_ERROR_PERMISSION',
        5001 => 'E_APP_ERROR_LOGIN',
        5002 => 'E_APP_ERROR_COMMON',
        5003 => 'アクセス権がありません。',
        6000 => 'ファイルを入力してください。',
        9000 => 'システムエラー。管理者に連絡してください',
        'M0001E' => ':labelを選択してください。',
        'M0002E' => ':labelを入力してください。',
        'M0003E' => '同じテンプレート名が既にあります。別のテンプレートを指定してください。',
        'M0004E' => '必須項目を入力してください。',
        'M0005E' => '電話番号と照会電話番号が一致しません。',
        'M0006E' => 'ECC判定情報未入力です。',
        'M0007E' => '同じ名前のレイアウトが既に存在します。別名を指定してください。',
        'M0008E' => '投下対象のリストであるにも関わらず、投下先が未設定のリストデータが存在するため処理を実行できません。投下しない場合も、「投下しない」（投下フラグ=0）を明示的に設定してください。',
        'M0009E' => '新規リストで投下先が設定されているにもかかわらず、顧客基本への反映が除外となっている。リストデータが存在するため、処理を実行できません。',
        'M0010E' => ':labelの入力が正しくありません。',
        'M0011E' => '同一事業部の見込みデータが登録されているため、登録処理に失敗しました。',
        'M0012E' => 'リスト名が未設定です。',
        'M0013E' => 'ログインIDが存在しません。管理者にお問い合わせください。',
        'M0014E' => 'パスワードに誤りがあります。パスワードが忘れた方は「パスワードを忘れた場合はこちら」からパスワードをリセットしてください。',
        'M0015E' => 'ユーザーが無効です。管理者にお問い合わせください。',
        'M0016E' => '検索条件を入力してください。',
        'M0017E' => ':labelを正しく入力してください。',
        'M0019E' => 'SQL構文エラー',
        'M0020E' => '自動設定にたいしての一括削除はできません。',
        'M0001I' => '表示中のリストを保存しますか。',
        'M0002I' => '住所分割を一括して実行します。よろしいですか？',
        'M0003I' => 'リスト内の重複判定処理を実行します。よろしいですか？',
        'M0004I' => ':labelを削除します。よろしいですか。',
        'M0005I' => '割り当てられた最後のECC判定です。',
        'M0006I' => '顧客基本との重複判定処理を実行します。よろしいですか？',
        'M0007I' => '保有情報との照合を実行します。よろしいですか？',
        'M0008I' => '住所分割処理が完了しました。必要に応じて住所情報を手動で修正してください。修正されなかった場合は、除外対象となります。',
        'M0009I' => '住所分割処理が完了しました。今回、住所分割エラーはありませんでした。',
        'M0010I' => 'リスト内重複チェックが完了しました。 必要に応じてリスト情報を手動で修正してください。修正されなかった場合は、除外対象となります。',
        'M0011I' => 'リスト内重複チェックが完了しました。今回、重複はありませんでした。',
        'M0012I' => '顧客基本重複チェックが完了しました。必要に応じてリスト情報を手動で修正してください。',
        'M0013I' => '顧客基本重複チェックが完了しました。今回、重複データはありませんでした',
        'M0014I' => '法人区分の更新処理が完了しました。今回、:total件を法人として判定しました',
        'M0015I' => '法人区分の更新処理が完了しました。今回、法人として判定したデータはありませんでした。',
        'M0016I' => '設定した投下先部門に架電リストを投下します。よろしいですか？',
        'M0017I' => "リスト投下が完了しました。投下件数：:total件",
        'M0018I' => '見込をクリアします。よろしいですか？',
        'M0019I' => '保有情報との照合を実行します。よろしいですか？',
        'M0020I' => '保有情報との照合が完了しました。必要に応じてリスト情報を手動で修正してください。',
        'M0021I' => '保有情報との照合が完了しました。今回、該当データはありませんでした。',
        'M0022I' => '現在選択されているリストに対し、指定した条件でクレンジング処理を実行します',
        'M0023I' => 'クレンジング処理が完了しました。',
        'M0024I' => '現在選択されているリストの{リスト名 or リストメモ}を変更します。',
        'M0025I' => '全置換完了しました。',
        'M0028I' => '表示レイアウトを登録／変更しました。',
        'M0029I' => ':labelを削除しました。',
        'M0030I' => 'データを保存しました。',
        'M0031I' => '全置換完了しました。',
        'M0032I' => '該当データはありませんでした。',
        'M0034I' => 'リストの使用が終了されました。',
        'M0035I' => 'リスト名設定完了しました。',
        'M0036I' => ':labelを行います。よろしいですか？',
        'M0037I' => 'アポ禁を承認しました。',
        'M0038I' => 'アポ禁を否認しました。',
        'M0039I' => '承認します。よろしいですか。',
        'M0040I' => '否認します。よろしいですか。',
        'M0041I' => '顧客基本を更新しました。',
        'M0042I' => '投下先自動設定完了しました。',
        'M0043I' => 'リスト配布完了しました。',
        'M0044I' => '見込み情報をクリアしました。',
        'M0045I' => ':limit件以上のデータに対しの処理はできません。検索条件を絞ってください。',
        'L0001D' => ':labelの処理が完了しました。',
        'MSGA0052' => '「発番依頼」できません。回線種別、回線名義、回線名義フリガナの値を確認してください。',
        'MSGA0038' => ':paramは必須項目です。値を入力してください。',
        'NOT_INPUT_ALL' => '未記入の項目がございますので、お手数ですが、ご確認お願いします。'
    ],
    'elastic' => [
        'error' => [
            'connect' => 'エラスティックサーバにアクセスできません。',
            'param' => '無効なparams'
        ],
    ],
    'auth' => [
        'login' => [
            'success' => 'ログインが完了しました。',
            'error' => 'パスワードに誤りがあります。パスワードが忘れた方は「パスワードを忘れた場合はこちら」からパスワードをリセットしてください。',
            'validate' => '必須項目を埋めて、次へお進みください。',
        ],
        'forgot' => [
            'success' => 'お申込時にメールにてお送りしております、お客様控えをご参考にご入力ください。',
            'error' => 'メール送信できません',
        ],
        'reset_password' => [
            'success' => 'パスワードの変更が完了しました。',
            'error' => 'パスワードが変更できません。',
        ]
    ],
    'common_getconfig' => [
        'index' => [
            'not_found' => 'Configが見つかりません',
        ],
    ],
    'list_getconfig' => [
        'index' => [
            'not_found' => 'Configが見つかりません',
        ],
    ],
    'list_mappingtemplate' => [
        'index' => [
            'not_exist' => 'テンプレートを選択してください',
            'success' => 'マッピングテンプレートを保存しました。'
        ],
        'detail' => [
            'not_exist' => 'テンプレートを選択してください'
        ],
        'delete' => []
    ],
    'list_displaylayout' => [
        'index' => [
            'duplicate_name' => '重複表示レイアウト名。',
            'not_exist' => '表示レイアウトが存在していません。',
        ],
        'detail' => [
            'not_exist' => '表示レイアウトが存在していません。'
        ],
        'delete' => [
            'success' => '表示テンプレートを削除しました。'
        ]
    ],
    'list_uploadlist' => [
        'index' => [
            'success' => 'リストをアップロードしました。',
            'file_not_exist' => 'ファイルが存在しません。',
            'mapping_required' => 'マッピングファイルは必要及び値含みます。'
        ],
    ],
    'list_providelist' => [
        'update' => [
            'not_exist' => '提供リストが存在していません。',
            'success' => '更新提供リストが完了しました。'
        ],
        'delete' => [
            'not_exist' => '提供リストが存在していません。',
            'success' => '提供リストの削除が完了しました。'
        ],
    ],
    'list_setlistname' => [
        'index' => [
            'valid_listtype' => 'リストタイプが存在していません。',
            'checking_list_not_exist' => '精査リストが存在していません。',
            'success' => 'リスト名設定が完了しました。'
        ]
    ],
    'list_checkinglist' => [
        'update' => [
            'required' => '変更されたデータがありませんでした。',
            'checking_list_not_exist' => '精査リストが存在していません。',
        ],
        'delete' => [
            'required' => '変更されたデータがありませんでした。',
            'checking_list_not_exist' => '精査リストが存在していません。',
            'success' => 'データを削除しました。'
        ]
    ],
    'list_downloadjudgedfile' => [
        'index' => [
            'success' => '判定ファイルアップロード完了しました。'
        ]
    ],
    'list_uploadjudgedfile' => [
        'index' => [
            'success' => '判定ファイルアップロード完了しました。'
        ]
    ],
    'list_seteccpicdevide' => [
        'index' => [
            'emp_id_not_exist' => '従業員が存在していません。',
            'checking_list_id_not_exist' => '精査リストが存在していません。',
            'success' => 'ECC判定担当設定を完了しました。',
        ]
    ],
    'list_geteccjudge' => [
        'index' => [
            'emp_id_not_exist' => '従業員が存在していません。',
            'message_M0005I' => '割り当てられた最後のECC判定です。'
        ]
    ],
    'list_seteccjudge' => [
        'index' => [
            'emp_id_not_exist' => '従業員が存在していません。',
            'tel_no_not_match' => '問い合わせ電話番号と電話番号が一致しません。',
            'success' => 'ECC判定設定が完了しました。',
        ]
    ],
    'list_templatesearch' => [
        'index' => [
            'duplicate_name' => '同じ名前のテンプレートが既に存在します。別名を指定してください。',
            'not_exist' => '検索テンプレートが存在していません。',
        ],
        'delete' => [
            'not_exist' => '検索テンプレートが存在していません。',
        ],
        'detail' => [
            'not_exist' => '検索テンプレートが存在していません。',
        ],
    ],
    'list_listdest' => [
        'index' => [
            'checking_list_not_exist' => '精査リストが存在していません。',
            'not_exist' => '投下先コードが存在しません。',
            'success' => '投下先リスト設定が完了しました。'
        ],
        'delete' => [
            'checking_list_not_exist' => '精査リストが存在していません。',
            'success' => '投下先リスト削除が完了しました。'
        ]
    ],
    'list_cleansingresult' => [
        'index' => [
            'checking_list_not_exist' => '精査リストが存在していません。',
            'success' => 'クレンジング結果設定が完了しました。'
        ],
        'delete' => [
            'checking_list_not_exist' => '精査リストが存在していません。',
            'success' => 'クレンジング結果削除が完了しました。'
        ]
    ],
    'list_replaceall' => [
        'index' => [
            'checking_list_not_exist' => '精査リストが存在していません。'
        ],
        'value' => [
            'checking_list_not_exist' => '精査リストが存在していません。'
        ]
    ],
    'list_replacelistname' => [
        'index' => [
            'error_update_type' => '精査リストが存在していません。',
            'not_exist' => 'ユーザーリストが存在していません。',
            'check_list_nm' => '仮リスト名が設定されていないデータがあります。'
        ]
    ],
    'list_devideaddress' => [
        'index' => []
    ],
    'list_duplicateinlist' => [
        'index' => []
    ],
    'list_duplicateinclient' => [
        'index' => [
            'id' => '基本顧客が存在しない又は削除されていません。'
        ]
    ],
    'list_companyflag' => [
        'index' => [],
    ],
    'list_comparetel' => [
        'index' => []
    ],
    'list_pushlist' => [
        'index' => [
            'no_item' => '投下項目がありません。',
        ],
    ],
    'list_finishdistribute' => [
        'index' => [
            'no_item' => '投下項目がありません。',
            'success' => '配布が完了しました。'
        ],
    ],
    'list_clientbase' => [
        'detail' => [
            'not_exist' => '顧客が存在していません。',
        ],
        'update' => [
            'id' => '基本顧客が存在しない又は削除されていません。',
            'success' => '更新顧客が完了しました。',
        ],
    ],
    'list_kanyuken' => [
        'index' => [
            'success' => '加入権精査完了しました。'
        ],
        'upload' => [
            'success' => 'アップロード完了しました。:row件がアップロードされました。'
        ]
    ],
    'list_haigyo' => [
        'index' => [
            'cust_id_null' => '顧客基本IDに紐付けされていないデータがあります。確認してください。',
            'success' => '会社廃業完了しました。'
        ]
    ],
    'list_uploadclientbaseline' => [
        'index' => [
            'success' => '判定ファイルアップロード完了しました。'
        ]
    ],
    'list_callreflects' => [
        'index' => [
            'success' => 'コール情報反映完了しました。'
        ]
    ],
    'list_deletenewly' => [
        'index' => [
            'success' => '新設削除完了しました。'
        ]
    ],
    'list_apokin' => [
        'upload' => [
            'error_filestruct' => 'ファイル形式不正です。',
            'vision_success' => 'ビジョンAPO禁登録完了しました。',
            'sb_success' => 'SBAPO禁登録完了しました。'
        ],
    ],
    'list_listuser' => [
        'delete' => [
            'success' => '架電権削除完了しました。'
        ]
    ],
    'list_telflag' => [
        'index' => [
            'success' => '携帯案件精査完了しました。'
        ]
    ],
    'list_prohibitedconpany' => [
        'register' => [
            'error_filestruct' => 'ファイル形式不正です。',
            'call_success' => 'コール会社名APO禁完了しました。',
            'list_success' => 'リスト会社名APO禁完了しました。'
        ]
    ],
    'list_lastpostingupdate' => [
        'index' => [
            'error_filestruct' => 'ファイル形式不正です。',
            'success' => '最終掲載情報更新完了しました。'
        ]
    ],
    'list_importrecover' => [
        'index' => [
            'error_filestruct' => 'ファイル形式不正です。',
            'success' => '回収インポート完了しました。'
        ]
    ],
    'list_clientbase' => [
        'importSbm' => [
            'success' => 'SBMインポート完了しました。'
        ]
    ],
    'list_uploadapokinrelease' => [
        'index' => [
            'success' => 'APO禁開放アップロードを完了しました。'
        ]
    ],
    'list_uploadharueneapokin' => [
        'index' => [
            'success' => 'ハルエネAPO禁アップロードを完了しました。'
        ]
    ],
    'call_apokin' => [
        'insert' => [
            'success' => 'ビジョンAPO禁作成が完了しました。'
        ],
        'update' => [
            'success' => 'ビジョンAPO禁更新が完了しました。'
        ]
    ],
    'call_ichiran' => [
        'cleansing' => [
            'not_exist' => 'ユーザーリストが存在していません。',
        ],
        'replace' => [
            'not_exist' => 'ユーザーリストが存在していません。',
        ],
        'distribution' => [
            'not_exist' => 'ユーザーリストが存在していません。',
        ],
        'upsertTemplateSearch' => [
            'duplicate_name' => '同じ名前のテンプレートが既に存在します。別名を指定してください。',
        ]
    ],
    'call_listuser' => [
        'create' => [
            'success' => 'リストを作成しました。',
        ],
    ],
    'call_contacthist' => [
        'index' => [
            'success' => 'コンタクト履歴作成が完了しました。',
            'r_support_type_cd' => '対応区分を選択してください。',
            'r_ng_type_cd' => 'NG区分を選択してください。',
            'visit_dt' => '訪問日が過去の日付になっています。'
        ],
    ],
    'call_nsgadget' => [
        'upsert' => [
            'not_exist' => 'nsgadget が存在していません。',
            'success' => '完了しました。'
        ]
    ],
    'call_msgadget' => [
        'upsert' => [
            'not_exist' => 'msgadget が存在していません。',
            'success' => '完了しました。'
        ]
    ],
    'call_ossgadget' => [
        'upsert' => [
            'not_exist' => 'ossgadget が存在していません。',
            'success' => '完了しました。'
        ]
    ],
    'call_imgadget' => [],
    'apo_listuser' => [
        'register' => [
            'valid_company_nm' => '紹介顧客が紹介履歴一覧にすでに存在しています。',
            'error_visit_from_to' => '訪問時間FromToを正しく入力してください。',
            'error_recontact_from_to' => '再コンタクト予定時間From-Toを正しく入力してください。',
            'valid_type' => '再コンタクト設定とAPO作成はどちらか一方選択してください。',
            'success' => '紹介を登録しました。'
        ]
    ],
    'apo_introduce' => [
        'register' => [
            'valid_company_nm' => '紹介顧客が紹介履歴一覧にすでに存在しています。',
            'error_visit_from_to' => '訪問時間FromToを正しく入力してください。',
            'error_recontact_from_to' => '再コンタクト予定時間From-Toを正しく入力してください。',
            'valid_type' => '再コンタクト設定とAPO作成はどちらか一方選択してください。',
            'success' => 'APO作成完了しました。'
        ]
    ],
    'apo_visithist' => [
        'changeapplication' => [
            'not_exist' => '訪問履歴が存在していません。',
        ],
        'sendMailSalePicEmp' => [
            'success' => 'メールを送信しました。'
        ]
    ],
    'apo_register' => [
        'accompany' => [
            'success' => '同行を登録しました。'
        ],
        'correspondence' => [
            'success' => '対応を登録しました。',
            'error_visit_from_to' => '訪問時間FromToを正しく入力してください。',
        ],
        'add' => [
            'success' => '追加を登録しました。',
            'error_visit_from_to' => '訪問時間FromToを正しく入力してください。',
            'visit_dt' => '訪問日',
            'apo_content' => 'APO内容'
        ],
        'updatecalculateplandate' => [
            'success' => '計上予定日設定完了です。',
        ],
        'esca' => [
            'success' => 'エスカを登録しました。',
            'error_visit_from_to' => '訪問時間FromToを正しく入力してください。',
        ],
        'recreate' => [
            'success' => '再APOを登録しました。',
            'error_visit_from_to' => '訪問時間FromToを正しく入力してください。',
            'error_copy_pm' => '販促データがエクスポート済みのため、コピーできません。'
        ]
    ],
    'apo_reconfirm' => [
        'index' => [
            'success' => '詰め直しを登録しました。',
            'error_visit_from_to' => '訪問時間FromToを正しく入力してください。',
            'valid_visit_change_type' => '再コンタクト設定と再APO作成はどちらか一方選択してください。',
            'error_recontact_from_to' => '再コンタクト予定時間From-Toを正しく入力してください。',
            'error_call_list_user_id' => 'リスト情報に紐づかないAPOのため、コン再コール設定できません。'
        ]
    ],
    'apo_hearing' => [
        'update' => [
            'extent_info_hist_increase_emp_flg' => '増員予定有無を選択してください。',
            'mobile_hist_sbm_use_flg' => 'SBM利用有無を選択してください。',
            'homepage_hist_homepage_use_flg' => 'ホームページ利用有無を選択してください。',
            'copier_hist_copier_use_flg' => 'コピー機利用有無を選択してください。',
            'businessphone_hist_businessphone_use_flg' => 'ビジネスフォン利用有無を選択してください。',
            'internet_hist_net_use_flg' => 'ネット利用有無',
            'internet_hist_netbank_use_flg' => 'netbank利用有無',
            'domain_hist_domain_use_flg' => 'ドメイン利用有無を選択してください。',
            'led_hist_led_flg' => 'LED有無を選択してください。',
            'utm_hist_net_use_flg' => 'NET利用有無を選択してください。',
            'not_exist' => 'Visit hist is not exist',
            'success' => 'success',
        ],
        'detail' => [
            'visit_hist_not_exist' => '訪問履歴が存在していません。',
            'client_base_not_exist' => '基本顧客が存在していません。',
        ],
    ],
    'apo_otokuline' => [
        'detail' => [
            'not_exist' => '訪問履歴が存在していません。'
        ],
        'update' => [
            'not_exist' => '訪問履歴が存在していません。',
            'success' => ''
        ],
    ],
    'apo_mobile' => [
        'detail' => [
            'not_exist' => '訪問履歴が存在していません。'
        ],
        'update' => [
            'not_exist' => '訪問履歴が存在していません。',
            'success' => ''
        ],
    ],
    'apo_utm' => [
        'detail' => [
            'not_exist' => '訪問履歴が存在していません。'
        ],
        'update' => [
            'not_exist' => '訪問履歴が存在していません。',
            'success' => ''
        ],
    ],
    'apo_fukushozai' => [
        'detail' => [
            'not_exist' => '訪問履歴が存在していません。'
        ],
        'update' => [
            'duplicate_sale' => ' 副商材重複しています。ご確認ください。',
        ],
    ],
    'apo_haruene' => [
        'detail' => [
            'not_exist' => '訪問履歴が存在していません。'
        ]
    ],
    'apo_apokin' => [
        'insert' => [
            'success' => 'APO禁を登録しました。'
        ]
    ],
    'apo_considerlist' => [
        'sendflag' => [
            'not_exist' => '検討が存在していません。'
        ],
    ],
    'apo_searchtemplate' => [
        'index' => [
            'duplicate_name' => '同じ名前のテンプレートが既に存在します。別名を指定してください。',
            'not_exist' => '検索テンプレートが存在していません。',
        ],
    ],
    'apo_exporttemplate' => [
        'index' => [
            'duplicate_name' => '同じ名前のテンプレートが既に存在します。別名を指定してください。',
            'not_exist' => '検索テンプレートが存在していません。',
        ],
    ],
    'apo_export' => [
        'index' => [
            'error_limit_row' => '対象のエクスポートテンプレートでは1000件以上のエクスポートはできません。',
            'error_row_not_found' => '該当のデータがありませんでした。エクスポートしません。',
        ],
    ],
    'apo_imhp' => [
        'sendMailInspectResult' => [
            'email_error' => '送付先メールを正しく入力してください。',
            'success' => 'メールを送信しました。',
        ],
    ],
    'apo_oa'=> [
        'construcFileByCondition' => [
            'no_data' => '該当データがありませんでした。'
        ]
    ],
    'pm_ichiran' => [
        'delete' => [
            'ids_not_exist' => 'リストが存在していません。',
        ],
    ],
    'master_oaorderitem' => [
        'upsert' => [
            'duplicate_model_cd' => '型式は既に存在しています。型式に別の値を設定してください。',
        ],
    ],
    'kakyuken_doc' => [
        'index' => [
            'template_not_exist' => '書類管理が存在していません。',
        ],
        'exportByData' => [
            'template_not_exist' => '書類管理が存在していません。',
        ],
    ],
    'kanyuken_detail' => [
        'exporttainformation' => [
            'data_not_exist' => '該当データがありませんでした。',
        ],
        'exporttainformation2' => [
            'data_not_exist' => '該当データがありませんでした。',
        ],
        'updateOrderInformation' => [
            
        ],
    ],
    'kanyuken_gadgetbfureshito' => [
        'exportCSVBfureshito' => [
            'data_not_exist' => '該当データがありませんでした。',
        ],
    ],
    'kanyuken_ichiran' => [
        'replace' => [
            'data_not_exist' => '該当データがありませんでした。',
        ],
    ],
    'list_uploadlistuser' => [
        'index' => [
            'success' => 'アクティブ状況を更新しました。',
            'data_not_exist' => '該当データがありませんでした。',
        ],
    ]
];
