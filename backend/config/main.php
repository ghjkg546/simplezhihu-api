<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => [/*'log'*/],
    'modules' => [],
    'components' => [
        /*'request' => [
            'csrfParam' => '_csrf-backend',
        ],*/
        'user' => [
            'class' => 'backend\component\UserMember',
            'identityClass' => 'common\models\User',
            //'enableAutoLogin' => true,
            'loginUrl'=>null,
            //'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        /*'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],*/
        /*'user' => [
            'class' => 'backend\components\ManagerValidator',
            'identityClass' => 'backend\models\ManagerIdentity',
            'loginUrl' => ['/home/login'],
        ],*/
        /*'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],*/
        'jwt' => [
            'class' => 'sizeg\jwt\Jwt',
            'key' => 'MIICxjBABgkqhkiG9w0BBQ0wMzAbBgkqhkiG9w0BBQwwDgQIZpjowaA6CAsCAggA
                        MBQGCCqGSIb3DQMHBAi+j+GsVncTMASCAoDac7KAIUkk+pMjowu6yP7KkX7hLnld
                        7KuNho2o2VAoA1KfN6XvJBxwK9359Mynzxll/e97hVOr+WNIr47UPELPRU3kXhsb
                        s1N1q3cF2LUylcu6G9g9Pjkry/3JGIkV/VH4lXqMZLEfFpd5tCUsPE+G6ZNdN0wX
                        OwAF0pjjuJRYiqMlTGq2b3WwWBF5+knIeQfilJ2nqlaPtwcZVjNyH1uUdC/eJXG8
                        jpe2b3bCzDUWHwpLD2Rd4gaOefaLZf5Y1UvVAF/jgSX7Y3zyV2eAvcvhTAdrak9S
                        OpKDRFleE/7X/PWzGvTbb1ZkW4SMj6FdhB+eqTmkvJ4WzVF9SstGvfNX8iNWLHfm
                        Yk/7gkPjqi+fArm4nfuKPWC7HPOTHGMXxzKek3KzCRQadBffe2GV4wN54QQsQw51',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

    ],
    'params' => $params,
];
