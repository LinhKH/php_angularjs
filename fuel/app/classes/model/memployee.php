<?php

class Model_MEmployee extends \Orm\Model
{

    protected static $_table_name = 'm_employee';
    protected static $_primary_key = ['id'];
    protected static $_observers = [
        'Model_Service_Observer' => [
            'events' => ['before_insert', 'before_update'],
            'mysql_timestamp' => true,
            'overwrite' => true,
        ],
    ];

}
