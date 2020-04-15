<?php
header("Content-type:text/html;charset=utf-8");
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

$client = ClientBuilder::create()->build();

$database = new \Medoo\Medoo((new Config())->link);




$params = [
    'index' => 'product_new1',
    'type' => 'product_type',
];




//$params['body']['size'] = 1;
//$params['body']['query']['match']['general_name'] = '0d014870218f380bd8f1dcd400b76b98';
//$params['body']['query']['constant_score']['filter']['range']['stock']['gt'] = 400;
//
//$params['body']['query']['constant_score']['filter']['range']['stock']['lt'] = 460;
//$params['body']['sort']['stock']['order'] = 'desc';
$params['body']['query']['bool'] = [
    'should'=>[['wildcard']['produce_unit'] = '*康卓实业*',['wildcard']['produce_unit'] = '*kcxy*']
];
print_r($params);exit;
//$params['body']['query']['bool']['should']['wildcard']['first_pinyin'] = '*kcxy*';
//print_r($params);exit;
//$params['body']['query']['constant_score']['filter']['range']['stock']['lt'] = 600;
//$params['body']['query']['constant_score']['filter']['id'] = '0d014870218f380bd8f1dcd400b76b98';
$res = $client->search($params);
print_r($res);exit;