<?php

namespace console\controllers\script;

use Yii;
use yii\console\Controller;
use GatewayWorker\Register;
use Workerman\Worker;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;

class IndexController extends Controller
{
    public $_mongodbdb;


    /**
     * Displays homepage.
     *
     * @return string
     */
    public $enableCsrfValidation = false;


    public function actionIndex()
    {
        //初始化各个GatewayWorker
        //初始化register
        new Register('text://0.0.0.0:1238');

        //初始化 bussinessWorker 进程
        $worker = new BusinessWorker();
        $worker->name = 'WebIMBusinessWorker';
        $worker->count = 4;
        $worker->registerAddress = '127.0.0.1:1238';

        //设置处理业务的类,此处制定Events的命名空间
        $worker->eventHandler = '\app\push\controller\Events';

        // 初始化 gateway 进程
        $gateway = new Gateway("websocket://0.0.0.0:8282");
        $gateway->name = 'WebIMGateway';
        $gateway->count = 4;
        $gateway->lanIp = '127.0.0.1';
        $gateway->startPort = 2900;
        $gateway->registerAddress = '127.0.0.1:1238';

        //运行所有Worker;
        Worker::runAll();
    }


}