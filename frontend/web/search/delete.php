<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/5
 * Time: 9:47
 */
require 'vendor/autoload.php';
require_once 'Medoo.php';
require_once 'Config.php';

use Elasticsearch\ClientBuilder;



set_time_limit(0);

$params = [];



$hosts = [
    '127.0.0.1:9200' // ip和端口
];

$client = Elasticsearch\ClientBuilder::create()
    ->setHosts($hosts)
    ->build();

$params = ['index' => 'product'];
$response = $client->indices()->delete($params);
print_r($response);