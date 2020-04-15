<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/5
 * Time: 9:47
 */
phpinfo();
require 'vendor/autoload.php';
require_once 'Medoo.php';
require_once 'Config.php';

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->build();

$database = new \Medoo\Medoo((new Config())->link);

$lists =  $database->select("yii_product_basic_131", "*", ['LIMIT'=>[0,5000]

]);
set_time_limit(0);

$params = [];


foreach ($lists as $row) {

    $params['body'][] = [
        'index' => [
            '_index' => 'product_new1',
            '_type'  => 'product_type',
        ]
    ];


    $params['body'][] = [
        'id' => $row['_id'],
        'general_name' => $row['general_name'],
        'first_pinyin' => $row['first_pinyin'],
        'product_name' => $row['product_name'],
        'produce_unit' => $row['produce_unit']
    ];



}
$a=$client->bulk($params);
print_r($a);exit;


foreach ($lists as $row) {

    $params['body'][] = [
        'index' => [
            '_index' => 'product_own',
            '_type'  => 'product_own_type',
        ]
    ];


    $params['body'][] = [
        'id' => $row['_id'],
        'product_id' => $row['product_id'],
        'stock' => intval($row['stock']),
        'price' => floatval($row['price'])
    ];



}
//print_r($params);exit;
$a=$client->bulk($params);
print_r($a);
echo 'success';exit;

$params = [
    'index' => 'product_index',
    'type' => 'product_type',
];

$params['body']['query']['match']['general_name'] = '石榴健';
$res = $client->search($params);
print_r($res);exit;