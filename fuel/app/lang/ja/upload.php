<?php

return array(
	'error_'.\Upload::UPLOAD_ERR_OK						=> 'アップロードファイルが完了しました。',
	'error_'.\Upload::UPLOAD_ERR_INI_SIZE				=> 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
	'error_'.\Upload::UPLOAD_ERR_FORM_SIZE				=> 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
	'error_'.\Upload::UPLOAD_ERR_PARTIAL				=> 'アップロードされたファイルは部分しかアップロードされませんでした。',
	'error_'.\Upload::UPLOAD_ERR_NO_FILE				=> 'ファイルを入力してください。',
	'error_'.\Upload::UPLOAD_ERR_NO_TMP_DIR				=> '一時的に構成されたアップロードフォルダが見つかりません。',
	'error_'.\Upload::UPLOAD_ERR_CANT_WRITE				=> 'ディスクにアップロードされたファイルの書き込みが失敗しました。',
	'error_'.\Upload::UPLOAD_ERR_EXTENSION				=> 'インストールPHP拡張により、アップロードがブロックされました。',
	'error_'.\Upload::UPLOAD_ERR_MAX_SIZE				=> 'アップロードされたファイルは定義された最大サイズを超えました。',
	'error_'.\Upload::UPLOAD_ERR_EXT_BLACKLISTED		=> 'ファイルタイプ不正です。',
	'error_'.\Upload::UPLOAD_ERR_EXT_NOT_WHITELISTED	=> 'ファイルタイプ不正です。',
	'error_'.\Upload::UPLOAD_ERR_TYPE_BLACKLISTED		=> '本ファイルタイプのアップロードファイルが許可されていません。',
	'error_'.\Upload::UPLOAD_ERR_TYPE_NOT_WHITELISTED	=> '本ファイルタイプのアップロードファイルが許可されていません。',
	'error_'.\Upload::UPLOAD_ERR_MIME_BLACKLISTED		=> '本パントマイムタイプのアップロードファイルが許可されていません。',
	'error_'.\Upload::UPLOAD_ERR_MIME_NOT_WHITELISTED	=> '本パントマイムタイプのアップロードファイルが許可されていません。',
	'error_'.\Upload::UPLOAD_ERR_MAX_FILENAME_LENGTH	=> 'アップロードされたファイル名が定義されている最大長を超えました。',
	'error_'.\Upload::UPLOAD_ERR_MOVE_FAILED			=> 'Unable to move the uploaded file to it\'s final destination',
	'error_'.\Upload::UPLOAD_ERR_DUPLICATE_FILE 		=> 'A file with the name of the uploaded file already exists',
	'error_'.\Upload::UPLOAD_ERR_MKDIR_FAILED			=> 'Unable to create the file\'s destination directory',
);
