<?php
use GatewayWorker\Register;
use Workerman\Worker;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');



//(new yii\web\Application($config))->run();
//初始化各个GatewayWorker
//初始化register
$register = new Register('text://0.0.0.0:1238');




//运行所有Worker;
Worker::runAll();
