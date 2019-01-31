<?php

use Fuel\Core\Log;

class Model_Base_OrderMng
{
    /*
     * Use in Controller_Agency_Ichiran
     */
    public static function searchOrderMng($sCondition)
    {
        try {
            $sql = "SELECT o.id, DATE_FORMAT(o.req_dt, '%Y/%m/%d') AS req_dt
                , o.prj_sts_flg, c.disp_value AS prj_sts_flg_nm, o.agency_cd, a.agency_nm
                , CAST(AES_DECRYPT(o.company_name, '" . encrypt_key . "') as char(200) ) as company_name
                , o.inspect_sts, o.note, o.memo
                , DATE_FORMAT(l.accept_recv_dt, '%Y/%m/%d') AS accept_recv_dt, DATE_FORMAT(l.accept_end_dt, '%Y/%m/%d') AS accept_end_dt
                , o.doc_sts_flg, c2.disp_value AS doc_sts_flg_nm, o.warranty_collect_sts_cd, c3.disp_value AS warranty_collect_sts_cd_nm ";
            
            $fromSql = " FROM t_order_mng o LEFT JOIN t_lease_mng l ON (o.id = l.order_mng_id AND l.inspect_approve_flg = '01' AND l.del_flg = 0) "
                    ." LEFT JOIN m_agency a ON (o.agency_cd = a.agency_cd AND a.del_flg =0) "
                    ." LEFT JOIN m_common_mst c ON (o.prj_sts_flg = c.code_value AND c.mst_id ='project_sts' AND c.del_flg = 0)"
                    ." LEFT JOIN m_common_mst c2 ON (o.doc_sts_flg = c2.code_value AND c2.mst_id = 'doc_sts' AND c2.del_flg = 0)"
                    ." LEFT JOIN m_common_mst c3 ON (o.warranty_collect_sts_cd = c3.code_value AND c3.mst_id = 'warranty_collect_sts' AND c3.del_flg = 0)"
                    ." WHERE o.del_flg = 0 ";
            
            $sql .= $fromSql;
            $sql .= \Model_Base_OrderMng::createQuery($sCondition, true);
            $result = \DB::query($sql)->execute()->as_array();
            
            $sqlCountAll = "SELECT count(o.id) AS totalItems ";
            $sqlCountAll .= " FROM t_order_mng o WHERE o.del_flg = 0 ";
            $sqlCountAll .= \Model_Base_OrderMng::createQuery($sCondition, false);
            $resultCountAll = \DB::query($sqlCountAll)->execute()->current();
            
            return ['data'=>$result,'totalItems'=>$resultCountAll['totalItems']
                    , 'pageSize'=>$sCondition['pageSize'], 'currentPage'=>$sCondition['currentPage']];
            
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function createQuery($sCondition, $searchAgain){
        $sql="";
        $sql = self::createWhere($sCondition);

        if($searchAgain) {
            $sql.=" GROUP BY o.id ";
        }
        
        $sql .= " ORDER BY o.id DESC ";
        // 
        if($searchAgain) {
            if(!isset($sCondition['pageSize']) || empty(trim($sCondition['pageSize']))) {
                $sCondition['pageSize'] = 10;
            }
            if(!isset($sCondition['currentPage']) || empty(trim($sCondition['currentPage']))) {
                $sCondition['currentPage'] = 1;
            }
            $from = ($sCondition['currentPage'] - 1) * $sCondition['pageSize'];
            $to = $sCondition['pageSize'];
            $sql.= " LIMIT ".$from.", ".$to." ";
        }
        return $sql;
    }
    
    /*
     * Use in searchOrderMng
     */
    public static function createWhere($sCondition) {
        $sql="";
        if(isset($sCondition['req_dt_from']) && !empty(trim($sCondition['req_dt_from']))) {
            $sql.=" AND DATE(o.req_dt) >= ".DB::escape($sCondition['req_dt_from']);
        }

        if(isset($sCondition['req_dt_to']) && !empty(trim($sCondition['req_dt_to']))) {
            $sql.=" AND DATE(o.req_dt) <= ".DB::escape($sCondition['req_dt_to']);
        }

        if(isset($sCondition['prj_sts_flg']) && !empty(trim($sCondition['prj_sts_flg']))) {
            $sql.=' AND '.\Model_Base_OrderMng::renderCondition('o', 'prj_sts_flg', $sCondition['prj_sts_flg'], 'string');
        }

        if(isset($sCondition['warranty_collect_sts_cd']) && !empty(trim($sCondition['warranty_collect_sts_cd']))) {
            $sql.=' AND '.\Model_Base_OrderMng::renderCondition('o', 'warranty_collect_sts_cd', $sCondition['warranty_collect_sts_cd'], 'string');
        }

        // DATE_FORMAT 
        if(isset($sCondition['target_dt']) && !empty(trim($sCondition['target_dt']))) {
            $sql.=' AND '.\Model_Base_OrderMng::renderCondition('o', 'target_dt', $sCondition['target_dt'], 'monthtime');
        }

        if(isset($sCondition['company_name']) && !empty(trim($sCondition['company_name']))) {
            if($sCondition['company_name']=='*' || $sCondition['company_name']=='＊') {
                $sql.=" AND o.company_name IS NOT NULL AND o.company_name != '' ";
            } else if ($sCondition['company_name']=='=' || $sCondition['company_name']=='＝') {
                $sql.=" AND (o.company_name IS NULL OR o.company_name = '') ";
            } else {
                $sql.=" AND CAST(AES_DECRYPT(o.company_name, '" . encrypt_key . "') as char(200) ) LIKE ".DB::escape('%'.$sCondition['company_name'].'%')." ";
            }
        }
        
        if(isset($sCondition['doc_sts_flg']) && !empty(trim($sCondition['doc_sts_flg']))) {
            $sql.=' AND '.\Model_Base_OrderMng::renderCondition('o', 'doc_sts_flg', $sCondition['doc_sts_flg'], 'string');
        }
        
        if(isset($sCondition['agency_cd']) && !empty(trim($sCondition['agency_cd']))) {
            $sql.=' AND '.\Model_Base_OrderMng::renderCondition('o', 'agency_cd', $sCondition['agency_cd'], 'int');
        }
        
        return $sql;
    }
    
    /**
     * Render query for special search (...,*,=)
     *
     * @param string $tableName
     * @param string $columnName
     * @param string $columnValue
     * @param string $dataType
     * @return string
     */
    public static function renderCondition($tableName, $columnName, $columnValue, $dataType) {
        $sResult = '';
        if (is_array($columnValue)) {
            $value = implode($columnValue, ',');
            $sResult = " $tableName.$columnName in ($value) ";
        } else {
            $columnValue = str_replace("'", "\\'", stripslashes(html_entity_decode(trim($columnValue), ENT_QUOTES)));
            $tmpValue = $columnValue;
            $checkText = strpos($columnValue, '...');
            $endText = strlen($columnValue);
            $columnValue = str_replace('...', '', $columnValue);
            if ($columnValue === '*' || $columnValue === '＊') {
                if ($dataType == 'date') {
                    $sResult = " ($tableName.$columnName is not null)  ";
                } else {
                    $sResult = " ($tableName.$columnName is not null and $tableName.$columnName != '')  ";
                }
            } elseif ($columnValue === '=' || $columnValue === '＝') {
                if ($dataType == 'date') {
                    $sResult = " ($tableName.$columnName is null) ";
                } else {
                    $sResult = " ($tableName.$columnName is null or $tableName.$columnName = '') ";
                }
            } else {
                $checkSpace = mb_strpos(preg_replace('~\s+~u', ' ', str_replace('　', ' ', $columnValue)), ' ');
                if ($checkText !== false && $dataType !== 'string') {
                    if ($checkText === 0) {
                        if ($dataType == 'date') {
                            $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'Y/m/d', 'Y/m/d');
                            $sResult = " DATE_FORMAT($tableName.$columnName, '%Y/%m/%d')  <= '$columnValue' ";
                        } elseif ($dataType == 'time') {
                            $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'H:i', 'H:i');
                            $sResult = " TIME_FORMAT($tableName.$columnName, '%H:%i')  <= '$columnValue' ";
                        } elseif($dataType == 'monthtime') {
                            $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'Y/m', 'Y/m');
                            $sResult = " DATE_FORMAT($tableName.$columnName, '%Y/%m')  <= '$columnValue' ";
                        } else {
                            $sResult = " $tableName.$columnName <= '$columnValue' ";
                        }
                    } elseif ($checkText === $endText - 3) {
                        if ($dataType == 'date') {
                            $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'Y/m/d', 'Y/m/d');
                            $sResult = " DATE_FORMAT($tableName.$columnName, '%Y/%m/%d') >= '$columnValue' ";
                        } elseif ($dataType == 'time') {
                            $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'H:i', 'H:i');
                            $sResult = " TIME_FORMAT($tableName.$columnName, '%H:%i') >= '$columnValue' ";
                        } elseif($dataType == 'monthtime') {
                            $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'Y/m', 'Y/m');
                            $sResult = " DATE_FORMAT($tableName.$columnName, '%Y/%m') >= '$columnValue' ";
                        } else {
                            $sResult = " $tableName.$columnName >= '$columnValue' ";
                        }
                    } else {
                        $val1 = substr($tmpValue, 0, $endText - strpos($tmpValue, '...') - 3);
                        $val2 = substr($tmpValue, strpos($tmpValue, '...') + 3);
                        if ($dataType == 'date') {
                            $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'Y/m/d', 'Y/m/d');
                            $sResult = " DATE_FORMAT($tableName.$columnName, '%Y/%m/%d') between '$val1' and '$val2'  ";
                        } elseif ($dataType == 'time') {
                            $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'H:i', 'H:i');
                            $sResult = " TIME_FORMAT($tableName.$columnName, '%H:%i') between '$val1' and '$val2' ";
                        } elseif ($dataType == 'monthtime') {
                            $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'Y/m', 'Y/m');
                            $sResult = " DATE_FORMAT($tableName.$columnName, '%Y/%m') between '$val1' and '$val2'  ";
                        } else {
                            $sResult = " $tableName.$columnName between '$val1' and '$val2'  ";
                        }
                    }
                } elseif ($checkSpace !== false) {
                    $sResult = '';
                    $arrValue = explode(' ', preg_replace('~\s+~u', ' ', str_replace('　', ' ', $columnValue)));
                    foreach ($arrValue as $value) {
                        $sResult .= " $tableName.$columnName like '%$value%' and";
                    }
                    $sResult = rtrim($sResult, 'and');
                } else if ($dataType === 'string') {
                    $arrLikeEnd = ['company_tel', 'tel_no_num_only'];
                    if (in_array($columnName, $arrLikeEnd)) {
                        $sResult = " $tableName.$columnName like '$columnValue%' ";
                    } else {
                        $sResult = " $tableName.$columnName like '%$columnValue%' ";
                    }
                } else {
                    if ($dataType == 'date') {
                        $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'Y/m/d', 'Y/m/d');
                        $sResult = " DATE_FORMAT($tableName.$columnName, '%Y/%m/%d') = '$columnValue' ";
                    } elseif ($dataType == 'time') {
                        $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'H:i', 'H:i');
                        $sResult = " TIME_FORMAT($tableName.$columnName, '%H:%i') = '$columnValue' ";
                    } elseif($dataType == 'float'){
                        $sResult = " $tableName.$columnName = $columnValue ";
                    } elseif($dataType == 'monthtime') {
                        $columnValue = \Model_Service_Util::convertDateTimeFormat($columnValue, 'Y/m', 'Y/m');
                        $sResult = " DATE_FORMAT($tableName.$columnName, '%Y/%m') = '$columnValue' ";
                    }
                    else {
                        $sResult = " $tableName.$columnName = '$columnValue' ";
                    }
                }
            }
        }
        return $sResult;
    }


    public static function deleteOrder($isCheckAll, $listIds, $arrQuerySearch){
        try{
            $sql = 'UPDATE t_order_mng o 
                        LEFT JOIN t_order_detail b ON o.id = b.order_mng_id 
                        LEFT JOIN t_order_mng_file c ON o.id = b.order_mng_id 
                        LEFT JOIN t_building_detail d ON o.id = d.order_mng_id
                        LEFT JOIN t_lease_mng e ON o.id = e.order_mng_id
                        LEFT JOIN t_work_mng f ON o.id = f.order_mng_id
                    SET o.del_flg = 1, b.del_flg=1, c.del_flg=1, d.del_flg=1, e.del_flg=1, f.del_flg=1 ';  

            if($isCheckAll == 'true'){
                $sql .=' WHERE o.del_flg=0 '.self::createWhere($arrQuerySearch);
            }else{
                $sId = '('.implode(',', $listIds).')';
                if(empty($sId)){
                    $sId = "('')";
                }
                $sql .= ' WHERE o.id IN '.$sId.' ';
            }
            
            if(Model_Base_Core::query($sql) === false){
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
