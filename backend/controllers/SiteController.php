<?php
namespace backend\controllers;

use backend\models\Bike;
use backend\models\Member;
use backend\models\RepairRecords;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    

    /**
     * Displays homepage.
     *
     * @return string
     */
    public $layout=false;
    public $enableCsrfValidation = false;


    public function actionIndex()
    {
        $member= Member::findOne(1);
        return Json::encode($member);
    }

    public function actionLogin(){
        echo 'index';exit;
        $model = new LoginForm();
        $data= ['username'=>'xiaojie','password'=>123,'rememberMe'=>1];
        if ($model->load($data) && $model->login()) {
            echo 'log success';
        } else {
            print_r($model->getErrors());
            echo 'log fail';
        }
        exit;

        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        if($data['username']=='xiaojie' && $data['password']==123){
            echo 1;exit;
        }
        echo 0;
    }

    public function actionCharge(){
        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $member = Member::findOne(1);
        $member->money += $data['money'];
        $member->save();
        echo 1;exit;
    }

    public function actionGetpass(){
        $data['data'] = [
            'password'=>mt_rand(1000,9999),
            'number'=>mt_rand(10000,99999)
        ];
        return Json::encode($data);
    }

    public function actionBikepos(){
        $bikes= Bike::find()->asArray()->all();
        foreach ($bikes as $k=>$v){
            unset($bikes[$k]['bike_number']);
            $bikes[$k]['id']=intval($bikes[$k]['id']);
            $bikes[$k]['iconPath']='../images/markers.png';
            $bikes[$k]['width']=45;
            $bikes[$k]['height']=50;
            $bikes[$k]['latitude']=floatval($bikes[$k]['latitude']);
            $bikes[$k]['longitude']=floatval($bikes[$k]['longitude']);
        }
        return Json::encode($bikes);
    }

    public function actionRepairhistory(){
        $data= RepairRecords::find()->where(['member_id'=>1])->select('title')->scalar();
        return Json::encode($data);
    }

    public function actionReturndeposi(){
        Member::updateAll(['deposit'=>0],['id'=>1]);
        echo 1;exit;
    }

    public function actionToken(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
        $data['status'] =200;
        $data['access_token'] = 'asdfx1';
        return Json::encode($data);
    }

    public function actionChat(){
        return $this->render($this->action->id);
    }


    public function actionRepair1()
    {

        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $repaire_record=new RepairRecords();
        $repaire_record->pic_url = implode($data['picUrls'],',');
        $repaire_record->title =implode($data['repairReason'],',');
        $repaire_record->bike_number=$data['bikeNumber'];
        $repaire_record->remarks = $data['desc'];
        if(!$repaire_record->save(false)){
            echo 0;exit;
        }
        $data['msg']='报障成功';
        return Json::encode($data);
    }


    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
