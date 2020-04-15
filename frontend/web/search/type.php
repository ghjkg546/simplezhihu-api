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

$client = ClientBuilder::create()->build();

try{

    $hosts = [
        '127.0.0.1:9200' // ip和端口
    ];

    $client = Elasticsearch\ClientBuilder::create()
        ->setHosts($hosts)
        ->build();

    $params = [
        'index' => 'product_new1', // 索引名
        'include_type_name' => 'true', // 索引名
        'body' => [
            'settings' => [ // 分片和副本数
                'number_of_shards' => 3, // 分片
                'number_of_replicas' => 0 // 副本，如果只有一台机器，设置为0
            ],
            'mappings' => [ // 映射
                'product_type' => [ // 类型
                    '_source' => [
                        'enabled' => true // 开启即可，否则某些功能不可用
                    ],
                    'properties' => [ // 指定字段的类型或字段属性
                        'general_name' => [ // 字段
                            'type' => 'keyword', // 数据类型
                        ],
                        'first_pinyin' => [ // 字段
                            'type' => 'keyword', // 数据类型
                        ],
                        'produce_unit' => [ // 字段
                            'type' => 'keyword', // 数据类型
                        ],
                    ]
                ]
            ]
        ]
    ];

    $response = $client->indices()->create($params);
    print_r($response);
} catch (Exception $e){
    echo $e->getMessage();exit;
}

