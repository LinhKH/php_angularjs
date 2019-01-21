<?php

use Fuel\Core\Date;
use Auth\Auth;

class Model_Service_Observer extends \Orm\Observer
{

    protected $_overwrite;
    protected $_mysql_timestamp;

    public function __construct($class)
    {
        $props = $class::observers(get_class($this));
        $this->_mysql_timestamp = isset($props['mysql_timestamp']) ? $props['mysql_timestamp'] : static::$mysql_timestamp;
        $this->_overwrite = isset($props['overwrite']) ? $props['overwrite'] : true;
    }

    public function before_insert(\Orm\Model $model)
    {
        if ($this->_overwrite) {
            // $model->creat_user_id = \Auth::get('emp_id');
            $model->creat_user_id = 'test01';
            // $model->update_user_id = \Auth::get('emp_id');
            $model->update_user_id = 'test01';
            $model->create_time = $this->_mysql_timestamp ? Date::time()->format('mysql') : date('Y-m-d H:i:s', Date::forge()->get_timestamp());
            $model->update_time = $this->_mysql_timestamp ? Date::time()->format('mysql') : date('Y-m-d H:i:s', Date::forge()->get_timestamp());
        }
    }

    public function before_update(\Orm\Model $model)
    { 
        if ($this->_overwrite) {
            // $model->update_user_id = \Auth::get('emp_id');
            $model->update_user_id = 'test01';
            $model->update_time = $this->_mysql_timestamp ? Date::time()->format('mysql') : date('Y-m-d H:i:s', Date::forge()->get_timestamp());
        }
    }

}
