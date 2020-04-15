<?php
/*!
 * Medoo database framework
 * https://medoo.in
 * Version 1.6
 *
 * Copyright 2018, Angel Lai
 * Released under the MIT license
 */

$root = $_SERVER['DOCUMENT_ROOT'];

class Config {

    public $link =  [
        'database_type' => 'mysql',
        'database_name' => 'db_product_1',
        'server' => '192.168.2.167',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
        'port' => 3308
    ];

//    public function __construct(){
//        global $c;
//        $this->link['username'] = $c['db_cfg']['username'];
//        $this->link['password'] = $c['db_cfg']['password'];
//    }


}
