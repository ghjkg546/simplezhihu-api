<?php

/**
 * 模块路由表
 * @author 胡植鹏
 */
return [
    'gii' => [
        'class' => 'yii\gii\Module',
        'allowedIPs' => [
            '127.0.0.1', '::1', '192.168.2.*', 'localhost'
        ]
    ],
    //开发工具模块
    'developtool' => [
        'class' => 'frontend\module\developtool\components\DevelopToolModule',
        'id' => '开发工具模块',
        'basePath' => APPLICATION_PATH . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'developtool',
        'defaultRoute' => 'basic',
        'controllerNamespace' => 'frontend\module\developtool\controllers',
    ],
    'api' => [
        'class' => 'frontend\module\api\components\ApiModule',
        'id' => 'api接口',
        'basePath' => APPLICATION_PATH . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'api',
        'defaultRoute' => 'home',
        'controllerNamespace' => 'frontend\module\api\controllers',
    ],
];
