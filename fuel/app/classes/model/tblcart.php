<?php

class Model_TblCart extends \Orm\Model
{

    protected static $_table_name = 'tbl_cart';
    protected static $_primary_key = ['cartId'];
    // protected static $_observers = [
    //     'Model_Service_Observer' => [
    //         'events' => ['before_insert', 'before_update'],
    //         'mysql_timestamp' => true,
    //         'overwrite' => true,
    //     ],
    // ];

}
