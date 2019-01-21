<?php

use Fuel\Core\DB;
use Fuel\Core\Log;
use Fuel\Core\Config;

class Model_Base_Core
{

    public static function validField($modelName, $field, $val)
    {
        try {
            $result = $modelName::query()->where(['del_flg' => 0, $field => $val]);
            return ($result->count() > 0);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function validFields($modelName, $arrWhere)
    {
        try {
            $result = $modelName::query()->where('del_flg', 0)->where($arrWhere);
            return ($result->count() > 0);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function getRowNum($modelName, $arrWhere)
    {
        try {
            $result = $modelName::query()->where('del_flg', 0)->where($arrWhere);
            return $result->count();
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function getRowNumAll($modelName, $arrWhere = [])
    {
        try {
            $result = $modelName::query();
            if (!empty($arrWhere)) {
                $result->where($arrWhere);
            }
            return $result->count();
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function insert($modelName, $arrData)
    {
        try {
            $new = $modelName::forge($arrData);
            $new->save();
            return $new->id;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function update($modelName, $id, $arrData)
    {
        try {
            $query = $modelName::find($id);
            if ($query != null) {
                $query->set($arrData);
                $query->save();
                return true;
            }
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function delete($modelName, $id)
    {
        try {
            $item = $modelName::find($id);
            if ($item) {
                $item->del_flg = 1;
                $item->save();
            }
            return true;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function deleteByWhere($modelName, $arrWhere)
    {
        try {
            $arrResult = $modelName::find('all', [
                    'where' => $arrWhere
            ]);
            if (!empty($arrResult)) {
                foreach ($arrResult as $item) {
                    $item->del_flg = 1;
                    $item->save();
                }
            }
            return true;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function deleteRow($modelName, $id)
    {
        try {
            $modelName::find($id)->delete();
            return true;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function getOne($modelName, $arrQuery = [], Closure $callBack = null)
    {
        try {
            $query = $modelName::query();
            if (!empty($arrQuery['from_cache'])) {
                $query->from_cache(false);
            }
            if (!empty($arrQuery['select'])) {
                $query->select($arrQuery['select']);
            }
            if (!empty($arrQuery['where'])) {
                $query->where($arrQuery['where']);
            }
            if (!empty($arrQuery['order_by'])) {
                $query->order_by($arrQuery['order_by']);
            }
            if (!empty($arrQuery['offset'])) {
                $query->offset($arrQuery['offset']);
            }
            if (!is_null($callBack)) {
                $query = $callBack($query);
            }
            $data = $query->get_one();
            return empty($data) ? null : $data->to_array();
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function getAll($modelName, $arrQuery = [], Closure $callBack = null)
    {
        try {
            $query = $modelName::query();
            if (!empty($arrQuery['from_cache'])) {
                $query->from_cache(false);
            }
            if (!empty($arrQuery['select'])) {
                $query->select($arrQuery['select']);
            }
            if (!empty($arrQuery['where'])) {
                $query->where($arrQuery['where']);
            }
            if (!empty($arrQuery['order_by'])) {
                $query->order_by($arrQuery['order_by']);
            }
            if (!empty($arrQuery['limit'])) {
                $query->limit($arrQuery['limit']);
            }
            if (!empty($arrQuery['offset'])) {
                $query->offset($arrQuery['offset']);
            }
            if (!is_null($callBack)) {
                $query = $callBack($query);
            }
            return is_object($query) ? Model_Service_Util::convertToArray($query->get()) : $query;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function findAll($modelName, $arrQuery = [])
    {
        try {
            $query = $modelName::find('all', [
                    'select' => empty($arrQuery['select']) ? [] : $arrQuery['select'],
                    'where' => empty($arrQuery['where']) ? [] : $arrQuery['where'],
                    'order_by' => empty($arrQuery['order_by']) ? [] : $arrQuery['order_by'],
                    'limit' => empty($arrQuery['limit']) ? _DEFAULT_LIMIT_ : $arrQuery['limit'],
                    'offset' => empty($arrQuery['offset']) ? _DEFAULT_OFFSET_ : $arrQuery['offset'],
            ]);
            return Model_Service_Util::convertToArray($query);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function query($sql)
    {
        try {
            DB::query($sql)->execute();
            return true;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function queryData($sql)
    {
        try {
            $res = DB::query($sql)->execute()->as_array();
            return empty($res) ? [] : $res;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function queryAll($arrQuery)
    {
        try {
            $select = empty($arrQuery['select']) ? '*' : $arrQuery['select'];
            $from = empty($arrQuery['from']) ? 'm_common_mst' : $arrQuery['from'];
            $join = empty($arrQuery['join']) ? '' : $arrQuery['join'];
            $where = empty($arrQuery['where']) ? '1' : $arrQuery['where'];
            $group_by = empty($arrQuery['group_by']) ? '' : 'GROUP BY ' . $arrQuery['group_by'];
            $order_by = empty($arrQuery['order_by']) ? '' : 'ORDER BY ' . $arrQuery['order_by'];
            $limit = empty($arrQuery['limit']) ? '' : 'LIMIT ' . $arrQuery['limit'];
            $sql = "SELECT $select FROM $from $join WHERE $where $group_by $order_by $limit";
            $res = DB::query($sql)->execute()->as_array();
            return empty($res) ? [] : $res;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function insertAll($modelName, $arrData, $columns = [])
    {
        try {
            $data = array_chunk($arrData, _DEFAULT_INSERT_ROW_);
            foreach ($data as $arrData) {
                $query = DB::insert($modelName::table());
                if (!empty($columns)) {
                    $query->columns($columns);
                }
                $query->values($arrData);
                $query->execute();
            }
            return true;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function updateByWhere($modelName, $arrWhere, $arrData)
    {
        try {
            DB::update($modelName::table())
                ->set($arrData)
                ->where($arrWhere)
                ->execute();
            return true;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function updateByWheres($modelName, $arrWhere, $arrData)
    {
        try {
            $query = DB::update($modelName::table())->set($arrData);
            if (isset($arrWhere['where'])) {
                $query->where($arrWhere['where']);
            }
            if (isset($arrWhere['where_open'])) {
                $query->where_open();
                if (isset($arrWhere['where_open']['and_where'])) {
                    $query->where($arrWhere['where_open']['and_where']);
                }
                if (isset($arrWhere['where_open']['or_where'])) {
                    $query->or_where($arrWhere['where_open']['or_where']);
                }
                $query->where_close();
            }

            $query->execute();
            return true;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

    public static function deleteRowByWhere($modelName, $arrWhere)
    {
        try {
            DB::delete($modelName::table())->where($arrWhere)->execute();
            return true;
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
        }
        return false;
    }

  
}
