<?php

use Fuel\Core\Log;
use Fuel\Core\Config;

class Model_Base_Agency
{
    
    public static function send_mail($arrParams)
    {
        try{

            // get mail master
            $arrQueryMail = [
                'select' => ['id','mail_address','type'],
                'where' => [['del_flg','=',0]]
            ];
            $arrMails = Model_Base_Core::getAll('Model_MMailAcc', $arrQueryMail);
            
            $arrTo = [];
            $arrCc = [];
            $arrBcc = [];

            foreach ($arrMails as $row) {
                switch ($row['type']) {
                    case '1':
                        $arrTo[] = $row['mail_address'];
                        break;
                    case '2':
                        $arrCc[] = $row['mail_address'];
                        break;
                    case '3':
                        $arrBcc[] = $row['mail_address'];
                        break;
                    default:
                        # code...
                        break;
                }
            }

            // setup subject
            $mailSubject = '【審査情報'.$arrParams['update_type'].'通知】{company}_{agency}_{date}';
            $currentDate = date('Y-m-d H:i:s', \Date::forge()->get_timestamp());
            $mailSubject = str_replace(['{company}','{agency}','{date}'],[$arrParams['company_name'],$arrParams['agency'],$currentDate] ,$mailSubject);
            $arrParams['detail_url'] = \Uri::create('management/detail/:id',['id' => $arrParams['order_id']]);
            // setup content
            $arrDataSendMail = [
                'to' => $arrTo,
                'cc' => $arrCc,
                'bcc' => $arrBcc,
                'subject' => $mailSubject,
                'body' => $arrParams,
                'view' => 'update_order'
            ];
            
            if( Model_Service_Util::send_mail($arrDataSendMail) === false){
                throw new Exception();
            }
            return true;
        }
        catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
        
    }
    
     public static function send_mail_add_comment($arrParams)
    {
        try{

            // get mail master
            $arrQueryMail = [
                'select' => ['id','mail_address','type'],
                'where' => [['del_flg','=',0]]
            ];
            $arrMails = Model_Base_Core::getAll('Model_MMailAcc', $arrQueryMail);
            
            $arrTo = [];
            $arrCc = [];
            $arrBcc = [];

            foreach ($arrMails as $row) {
                switch ($row['type']) {
                    case '1':
                        $arrTo[] = $row['mail_address'];
                        break;
                    case '2':
                        $arrCc[] = $row['mail_address'];
                        break;
                    case '3':
                        $arrBcc[] = $row['mail_address'];
                        break;
                    default:
                        # code...
                        break;
                }
            }

            // setup subject
            $mailSubject = '【コメント追加通知】{company}_{agency}_{date}';
            $currentDate = date('Y-m-d H:i:s', \Date::forge()->get_timestamp());
            $mailSubject = str_replace(['{company}','{agency}','{date}'],[$arrParams['company_name'],$arrParams['agency'],$currentDate] ,$mailSubject);
            $arrParams['detail_url'] = \Uri::create('management/detail/:id',['id' => $arrParams['order_id']]);
            // setup content
            $arrDataSendMail = [
                'to' => $arrTo,
                'cc' => $arrCc,
                'bcc' => $arrBcc,
                'subject' => $mailSubject,
                'body' => $arrParams,
                'view' => 'add_comment'
            ];
            
            if( Model_Service_Util::send_mail($arrDataSendMail) === false){
                throw new Exception();
            }
            return true;
        }
        catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
        
    }
}