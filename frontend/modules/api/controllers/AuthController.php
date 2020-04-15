<?php

namespace frontend\modules\api\controllers;

use yii\helpers\Json;
use yii\web\Controller;

/**
 * Default controller for the `User` module
 */
class AuthController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public $layout=false;
    public $enableCsrfValidation = false;
    public function actionLogin()
    {
        return Json::encode([
            'code'=>0,
            'result'=>['token'=>'4291d7da9005377ec9aec4a71ea837f','status'=>1]]
        );
    }

    public function actionInfo(){
        $a = '{
     "id": "4291d7da9005377ec9aec4a71ea837f",
     "name": "天野远子",
     "username": "admin",
     "password": "",
     "avatar": "/avatar2.jpg",
     "status": 1,
     "telephone": "",
     "lastLoginIp": "27.154.74.117",
     "lastLoginTime": 1534837621348,
     "creatorId": "admin",
     "createTime": 1497160610259,
     "merchantCode": "TLif2btpzg079h15bk",
     "deleted": 0,
     "roleId": "admin",
     "role": {}
   }';
        return Json::encode([
                'code'=>200,
                'result'=>Json::decode($a)]
        );
    }

    public function action2stepCode(){
        return Json::encode([
                'code'=>200,
                'result'=>['stepCode'=>2]]
        );
    }

    public function actionLogout(){
        return Json::encode([
                'code'=>200,
                'result'=>[]
            ]
        );
    }

}
