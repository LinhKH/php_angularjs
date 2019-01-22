<?php

use Fuel\Core\Fuel;
use Fuel\Core\Config;
use Fuel\Core\Log;
use Fuel\Core\DB;

class Model_Service_Util
{

    protected static $hasher = null;
    private static $messages = [
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Syntax error, malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
        JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
        5 /* JSON_ERROR_UTF8 */ => 'Invalid UTF-8 sequence',
        6 /* JSON_ERROR_RECURSION */ => 'Recursion detected',
        7 /* JSON_ERROR_INF_OR_NAN */ => 'Inf and NaN cannot be JSON encoded',
        8 /* JSON_ERROR_UNSUPPORTED_TYPE */ => 'Type is not supported',
    ];

    public static function mb_trim($str)
    {
        return preg_replace('/\t+/', ' ', trim(preg_replace('/(^\s+)|(\s+$)/us', '', $str)));
    }

    public static function mb_trimAllSpace($str)
    {
        return preg_replace('/\t+/', '', trim(preg_replace('/(^\s+)|(\s+$)|(\s+)/us', '', str_replace('　', '', $str))));
    }

    public static function _empty($val)
    {
        return ($val === false or $val === null or $val === '' or $val === []);
    }

    public static function gen_code($code = null)
    {
        if ($code) {
            return md5($code . Config::get('auth.salt') . uniqid() . time());
        } else {
            return md5(Config::get('auth.salt') . uniqid() . time());
        }
    }

    public static function hash_password($password)
    {
        is_null(self::$hasher) and self::$hasher = new \PHPSecLib\Crypt_Hash();
        return base64_encode(self::$hasher->pbkdf2($password, Config::get('auth.salt'), Config::get('auth.iterations', 10000), 32));
    }

    public static function get_app_config($name, $option = [])
    {
        $data = [];
        $config = Config::get('app.' . $name);
        foreach ($option as $key) {
            $data[$key] = $config[$key];
        }

        return !empty($option) ? $data : $config;
    }

    public static function send_mail($mail_data, $option = null)
    {
        $mailData = base64_encode(serialize($mail_data));
        $opt = base64_encode(serialize($option));
        $oil_path = str_replace('\\', '/', realpath(APPPATH . '/../../'));
        $command = "env FUEL_ENV=" . Fuel::$env . " php $oil_path/oil r tool:send_mail '$mailData' '$opt' > /dev/null &";
        try {
            exec($command);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        return true;
    }

    public static function convertDateTimeFormat($sDateTime, $sFromFormat, $sToFormat = 'Y-m-d H:i:s')
    {
        $sPartem = str_replace(['Y', 'm', 'd', 'H', 'i', 's'], ['0000', '00', '00', '00', '00', '00'], $sFromFormat);
        if ($sDateTime == $sPartem) {
            return null;
        }
        $dr = date_create_from_format($sFromFormat, $sDateTime);
        return $dr ? $dr->format($sToFormat) : '';
    }

    public static function coverArrToString($arrInput, $sKey = 'id')
    {
        $sResult = '';
        foreach ($arrInput as $item) {
            $sResult .= "'" . $item[$sKey] . "',";
        }
        return empty($sResult) ? '0' : substr($sResult, 0, -1);
    }

    public static function extractArr($arrInput, $sKey = 'id')
    {
        $arrResult = [];
        foreach ($arrInput as $item) {
            $arrResult[] = $item[$sKey];
        }
        return $arrResult;
    }

    public static function groupArrBykey($arrInput, $sField = 'id')
    {
        $arrResult = [];
        foreach ($arrInput as $item) {
            $arrResult[$item[$sField]][] = $item;
        }
        return $arrResult;
    }

    public static function reindexArrBykey($arrInput, $sField = 'id')
    {
        $arrResult = [];
        foreach ($arrInput as $item) {
            $arrResult[$item[$sField]] = $item;
        }
        return $arrResult;
    }

    public static function convertToArray($arrInput, $del_key = [])
    {
        $arrResult = [];
        foreach ($arrInput as $item) {
            $newItem = $item->to_array();
            if (!empty($del_key)) {
                foreach ($newItem as $key => $value) {
                    if (in_array($key, $del_key)) {
                        unset($newItem[$key]);
                    }
                }
            }
            $arrResult[] = $newItem;
        }
        return $arrResult;
    }

    public static function json_encode($value)
    {
        // needed to receive 'Invalid UTF-8 sequence' error; PHP bugs #52397, #54109, #63004
        if (function_exists('ini_set')) { // ini_set is disabled on some hosts :-(
            $old = ini_set('display_errors', 0);
        }

        // needed to receive 'recursion detected' error
        set_error_handler(function($severity, $message) {
            restore_error_handler();
            throw new JsonException($message);
        });

        $json = json_encode($value);

        restore_error_handler();
        if (isset($old)) {
            ini_set('display_errors', $old);
        }

        $error = json_last_error();
        if ($error) {
            $message = isset(static::$messages[$error]) ? static::$messages[$error] : 'Unknown error';
            throw new JsonException($message, $error);
        }
        return $json;
    }

    public static function json_decode($json)
    {
        if (!preg_match('##u', $json)) {
            throw new JsonException('Invalid UTF-8 sequence', 5);
        }

        $value = json_decode($json);
        if ($value === NULL && $json !== '' && $json !== 'null') {
            $error = json_last_error();
            $message = isset(static::$messages[$error]) ? static::$messages[$error] : 'Unknown error';
            throw new JsonException($message, $error);
        }
        return $value;
    }

    public static function rrmdir($dir, $root = 0)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if ((time() - @filemtime($dir . DS . $object)) > 3600) {
                        if (filetype($dir . DS . $object) == 'dir') {
                            self::rrmdir($dir . DS . $object);
                        } else {
                            unlink($dir . DS . $object);
                        }
                    }
                }
            }
            reset($objects);
            if (!$root) {
                rmdir($dir);
            }
        }
    }

    public static function serverPost($url, $data, &$responseHeaders = null)
    {
        $query = http_build_query($data, '', '&');
        $stream = array('http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded",
                'content' => $query
        ));

        return self::httpRequest($url, $stream, $responseHeaders);
    }

    public static function convertStringJP($str)
    {
        $newStr = preg_replace_callback(
            '/[\x{ff01}-\x{ff5e}]/u', function($c) {
            // convert UTF-8 sequence to ordinal value
            $code = ((ord($c[0][0]) & 0xf) << 12) | ((ord($c[0][1]) & 0x3f) << 6) | (ord($c[0][2]) & 0x3f);
            return chr($code - 0xffe0);
        }, $str);
        //convert half with to full with kana
        return strtolower(mb_convert_kana($newStr, 'KV', 'UTF-8'));
    }

    public static function convertKataToHira($str){
        $sJenkaku = self::hankakuToJenkaku($str);
        return mb_convert_kana($sJenkaku, 'Hc', 'UTF-8');
    }


    public static function hankakuToJenkaku($str){
        $replace_of = array('ｳﾞ','ｶﾞ','ｷﾞ','ｸﾞ',
                            'ｹﾞ','ｺﾞ','ｻﾞ','ｼﾞ',
                            'ｽﾞ','ｾﾞ','ｿﾞ','ﾀﾞ',
                            'ﾁﾞ','ﾂﾞ','ﾃﾞ','ﾄﾞ',
                            'ﾊﾞ','ﾋﾞ','ﾌﾞ','ﾍﾞ',
                            'ﾎﾞ','ﾊﾟ','ﾋﾟ','ﾌﾟ','ﾍﾟ','ﾎﾟ');
        $replace_by = array('ヴ','ガ','ギ','グ',
                            'ゲ','ゴ','ザ','ジ',
                            'ズ','ゼ','ゾ','ダ',
                            'ヂ','ヅ','デ','ド',
                            'バ','ビ','ブ','ベ',
                            'ボ','パ','ピ','プ','ペ','ポ');
        $_result = str_replace($replace_of, $replace_by, $str);
       
        $replace_of = array('ｱ','ｲ','ｳ','ｴ','ｵ',
                            'ｶ','ｷ','ｸ','ｹ','ｺ',
                            'ｻ','ｼ','ｽ','ｾ','ｿ',
                            'ﾀ','ﾁ','ﾂ','ﾃ','ﾄ',
                            'ﾅ','ﾆ','ﾇ','ﾈ','ﾉ',
                            'ﾊ','ﾋ','ﾌ','ﾍ','ﾎ',
                            'ﾏ','ﾐ','ﾑ','ﾒ','ﾓ',
                            'ﾔ','ﾕ','ﾖ','ﾗ','ﾘ',
                            'ﾙ','ﾚ','ﾛ','ﾜ','ｦ',
                            'ﾝ','ｧ','ｨ','ｩ','ｪ',
                            'ｫ','ヵ','ヶ','ｬ','ｭ',
                            'ｮ','ｯ','､','｡','ｰ',
                            '｢','｣','ﾞ','ﾟ');
        $replace_by = array('ア','イ','ウ','エ','オ',
                            'カ','キ','ク','ケ','コ',
                            'サ','シ','ス','セ','ソ',
                            'タ','チ','ツ','テ','ト',
                            'ナ','ニ','ヌ','ネ','ノ',
                            'ハ','ヒ','フ','ヘ','ホ',
                            'マ','ミ','ム','メ','モ',
                            'ヤ','ユ','ヨ','ラ','リ',
                            'ル','レ','ロ','ワ','ヲ',
                            'ン','ァ','ィ','ゥ','ェ',
                            'ォ','ヶ','ヶ','ャ','ュ',
                            'ョ','ッ','、','。','ー',
                            '「','」','”','');       
        $_result = str_replace($replace_of, $replace_by, $_result);
        return $_result;
    }

    /**
     * output day of week
     */
    public static function getDayOfWeek($date)
    {
        $dayOfWeek = '';
        if ($date) {
            // $N = Date::forge(strtotime($date))->format('%N');
            $N = date('N', strtotime($date));
            switch ($N) {
                case '0':
                    $dayOfWeek = '日';
                    break;
                case '1':
                    $dayOfWeek = '月';
                    break;
                case '2':
                    $dayOfWeek = '火';
                    break;
                case '3':
                    $dayOfWeek = '水';
                    break;
                case '4':
                    $dayOfWeek = '木';
                    break;
                case '5':
                    $dayOfWeek = '金';
                    break;
                case '6':
                    $dayOfWeek = '土';
                    break;
                default:
                    break;
            }
        }
        return $dayOfWeek;
    }

    public static function format_date($datetime, $type = null)
    {
        $time = strtotime($datetime);
        if ($type == 1) {
            $day = date("w", $time);
            $days = ["日", "月", "火", "水", "木", "金", "土"];
            $ext = '（' . $days[$day] . '）';
        } elseif ($type == 2) {
            $ext = ' ' . date('H:i', $time);
        } else {
            $ext = '';
        }

        return date('Y', $time) . '年' . date('m', $time) . '月' . date('d', $time) . '日' . $ext;
    }

    public static function convertString($txt, $from = 'SJIS,JIS,EUCJP-win,SJIS-win', $to = 'UTF-8')
    {
        $encoding = mb_detect_encoding($txt, "$from,$to");
        return strpos($from, $encoding) !== false ? mb_convert_encoding($txt, $to, $from) : $txt;
    }

    public static function convertStrtoDate($str, $format = 'Y-m-d')
    {
        $res = null;
        if ($str != '' && $str != '0000-00-00' && $str != '0000-00-00 00:00:00') {
            $date = date($format, strtotime(str_replace('/', '-', $str)));
            if (strpos($date, '1970') === false) {
                $res = $date;
            }
        }
        return $res;
    }

    public static function convertValidDateTimeForView($input, $format = 'Y-m-d')
    {
        $res = null;
        if (!empty($input) && $input != '0000-00-00 00:00:00' && strpos($input, '1970') === false) {
            $res = date($format, strtotime($input));
        }
        return $res;
    }

    public static function convertOnlyNum($str)
    {
        return preg_replace('/\D/', '', self::convertStringJP($str));
    }

    public static function convertBlankToNull($argument)
    {
        if (is_array($argument)) {
            foreach ($argument as $k => $item) {
                if (!is_array($item) && $item === '') {
                    $argument[$k] = null;
                }
            }
        } else {
            if ($argument === '') {
                $argument = null;
            }
        }
        return $argument;
    }

    public static function convertFile($source, $temp)
    {
        $sp = fopen($source, 'r');
        $op = fopen($temp, 'w');

        while (!feof($sp)) {
            $buffer = fread($sp, 1048576);  // use a buffer of 1MB
            fwrite($op, self::convertString($buffer));
        }

        // close handles
        fclose($op);
        fclose($sp);

        // make temporary file the new source
        rename($temp, $source);
    }

    /**
     * Generate id by use column on table t_unique_id
     *
     * @param string $column
     * @param integer $lenght
     * @return string
     */
    public static function generateUniqueId($column)
    {
        try {

            DB::start_transaction();
            $sTableName = 't_unique_id';
            $sResult = '';
            if (empty($column)) {
                return $sResult;
            }

            $arrId = \Model_Base_Core::getOne('Model_TUniqueId', ['select' => ['id', $column], 'from_cache' => true]);

            if ($arrId === false) {
                throw new Exception();
            }

            if (is_null($arrId)) {
                $idUniqueTable = \Model_Base_Core::insert('Model_TUniqueId', []);
            } else {
                $idUniqueTable = $arrId['id'];
            }
            $sQueryUpdate = "UPDATE $sTableName SET $column=$column+1 WHERE id = $idUniqueTable";
            if (!Model_Base_Core::query($sQueryUpdate)) {
                throw new Exception();
            }
            $arrIdNew = \Model_Base_Core::getOne('Model_TUniqueId', ['select' => ['id', $column], 'where' => ['id' => $idUniqueTable], 'from_cache' => true]);
            DB::commit_transaction();
            $sResult = isset($arrIdNew[$column]) ? $arrIdNew[$column] : '';
            return $sResult;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            DB::rollback_transaction();
        }
        return false;
    }

    /**
     * Returns a safe filename, for a given platform (OS), by
     * replacing all dangerous characters with an underscore.
     *
     * @param string $dangerousFilename The source filename
     * to be "sanitized"
     * @param string $platform The target OS
     *
     * @return string A safe version of the input
     * filename
     */
    public static function sanitizeFileName($dangerousFilename, $platform = 'Unix')
    {
        if (in_array(strtolower($platform), array('unix', 'linux', 'win'))) {
            // our list of "dangerous characters", add/remove
            // characters if necessary
            $dangerousCharacters = array(" ", '"', "'", "&", "/", "\\", "?", "#", ":", "*", "<", ">", "|");
        } else {
            // no OS matched? return the original filename then...
            return $dangerousFilename;
        }

        // every forbidden character is replace by an underscore
        return str_replace($dangerousCharacters, '_', $dangerousFilename);
    }

    /**
     * Get items has been changed
     *
     * @param array $arrListBase
     * @param array $arrListModifine
     * @param array $arrCols
     * @return array 
     */
    public static function checkUpdate($arrListBase, $arrListEdit, $arrCols, $primaryKey = 'id'){
        $arrResult = [];
        if(!empty($arrListBase) && !empty($arrCols)){

            $arrListBase = self::reindexArrBykey($arrListBase, $primaryKey);
            if(count($arrListEdit) > 0){
                foreach ($arrListEdit as $rowEdit) {
                    if( isset($rowEdit[$primaryKey]) && !empty($arrListBase[$rowEdit[$primaryKey]]) ){
                        $isChange = false;
                        foreach ($arrCols as $col) {
                            if( $rowEdit[$col] != $arrListBase[$rowEdit[$primaryKey]][$col] ){
                                $isChange = true;
                                break;
                            }
                        }
                        if($isChange === false){
                            $arrResult[] = $rowEdit[$primaryKey]; 
                        }
                    }
                    
                }
            }
            
        }
        return $arrResult;
    }


    public static function formatZipCd($zipCd){
        $result = null;
        if($zipCd){
            $zipCd = str_replace('-','',$zipCd);
            if(strlen($zipCd) >3 ){
                $result = substr_replace( $zipCd, '-', 3, 0 );
            }else{
                $result = $zipCd;
            }
        }
        return $result;
    }
}
