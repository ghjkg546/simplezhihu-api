<?php
namespace frontend\controllers;

use general\models\Bike;
use general\models\Member;
use general\models\RepairRecords;
use general\models\RidingRecord;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class BikeController extends Controller
{
    

    /**
     * Displays homepage.
     *
     * @return string
     */
    public $layout=false;
    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
    }

    public function actionIndex()
    {
        $member= Member::findOne(1);
        return Json::encode($member);
    }

    public function actionBikelist(){
        $bikes = Bike::find()->asArray()->all();
        $data['lists'] = $bikes;
        $data['count'] = count($bikes);
        return Json::encode($data);
    }

    public function actionStart(){
        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $bike = Bike::find()->where(['bike_number'=>$data['bike_number']])->one();
        $record=new RidingRecord();
        $record->user_id = 1;
        $record->bike_id = $bike->id;
        $record->start_time = time();
        $record->start_lati = $bike->latitude;
        $record->start_long = $bike->longitude;
        $record->create_time = time();
        $record->update_time = time();
        $record->save(false);
        $data['msg']='开始骑行';
        $data['record_id'] = $record->id;
        return Json::encode($data);
    }

    public function actionEnd(){
        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $record=RidingRecord::findOne($data['record_id']);
        $record->end_time = time();
        $record->end_lati = $data['end_lati'];
        $record->end_long = $data['end_long'];
        $record->total_price = ceil((time() - $record->start_time)/(30*60)) ;
        $record->update_time = time();
        $record->save(false);
        $data['msg']='结束骑行';
        $distance = $this->getDistance($record->start_lati, $record->start_long, $data['end_lati'], $data['end_long']);
        $data['distance'] =$distance;
        return Json::encode($data);
    }

    public function actionRecord(){
        $res = RidingRecord::find()->where(['user_id'=>1])->orderBy('end_time desc')->asArray()->all();
        $data['status']=1;
        foreach ($res as $k=>$v){
            $res[$k]['start_time']= date("Y-m-d H:i:s",$v['start_time']);
            $res[$k]['end_time']= date("Y-m-d H:i:s",$v['end_time']);
            $res[$k]['cost_minute'] = floor(($v['end_time']-$v['start_time'])/60 );
            $res[$k]['cost_second'] = ($v['end_time']-$v['start_time']) % 60;
            $bike = Bike::findOne($v['bike_id']);
            $res[$k]['bike_number'] = $bike['bike_number'];
        }
        $data['data']=$res;
        $data['status']=200;


        return Json::encode($data);
    }

    public function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }
}
