<?php

define('_DEFAULT_OFFSET_', '0');
define('_DEFAULT_LIMIT_', '10');
define('_DEFAULT_INSERT_ROW_', '1000');
define('encrypt_key', sha1('QW_!@erP5VIY^$C'));
define('ORDER_FILE_PATH', DOCROOT . 'files' . DS . 'upload'.DS.'order_file_mng');
define('LEASE_MNG_PATH', DOCROOT . 'files' . DS . 'upload'.DS.'lease_mng');
define('UPLOAD_TEMP_PATH', DOCROOT . 'files' . DS . 'upload_temp');
define('DOCUMENT_FILE_PATH', DOCROOT . 'files' . DS . 'upload'.DS.'document');
define('MAX_UPLOAD_SIZE', 25*1024*1024); // Max file upload 10 MB 

