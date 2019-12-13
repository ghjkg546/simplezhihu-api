<?php
namespace backend\controllers;

use backend\models\Bike;
use backend\models\FollowRelation;
use backend\models\Member;
use backend\models\News;
use backend\models\Cases;
use backend\models\RepairRecords;
use backend\models\wx\WXBizDataCrypt;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class UserController extends Controller
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
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With,X-Token");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
    }


    public function actionCharge(){
        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $member = Member::findOne(1);
        $member->money += $data['money'];
        $member->save();
        echo 1;exit;
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

    public function actionList(){

        $data['status'] =200;
        $users = Member::find()->asArray()->all();
        $data['data']['lists'] = $users;
        $data['data']['total'] = 2;
        return Json::encode($data);
    }

    public function actionAdd(){
        $a=file_get_contents("php://input");
        $b=Json::decode($a);
        $member=new Member();
        $member->username=$b['name'];
        $member->money =$b['money'];
        //$member->
    }

    public function actionEdit(){
        $a=file_get_contents("php://input");
        $b=Json::decode($a);
        $b=$b['data'];
        if(!empty($b)){
            $member= Member::findOne($b['id']);
            $member->username = $b['name'];
            $member->money=$b['money'];
            $member->save(false);
            $data['status'] =200;

            $users = Member::find()->asArray()->all();
            $data['data']['lists'] = $users;
            $data['data']['total'] = count($users);
            return Json::encode($data);
        }

    }

    public function actionDelete(){
        $a=file_get_contents("php://input");
        $b=Json::decode($a);
        Member::deleteAll(['id'=>$b['id']]);
        $data['status'] =200;

        $users = Member::find()->asArray()->all();
        $data['data']['lists'] = $users;
        $data['data']['total'] = count($users);
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
        $data['code']=20000;
        $data['data']='success';
        return Json::encode($data);

        return $this->goHome();
    }


    /**
     * 返回从微信服务器解密到的用户信息
     * @return array|mixed
     */
    public function actionGetuserdata(){
        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $js_code = $data['code'];
        $encryptedData = $data['encryptedData'];
        $iv = $data['iv'];

        $appid = Yii::$app->params['wx']['appid'];
        $secret = Yii::$app->params['wx']['secret'];
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$js_code}&grant_type=authorization_code";
        $response = json_decode(static::curl_get($url), true);
        if (!empty($response['errcode'])){
            print_r($response['errmsg']);exit;
        }
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        $sessionKey = $response['session_key'];
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        if ($errCode != 0) {print_r($errCode);exit;
        }
        return $data;
        $data = json_decode($data, true);
        $data['unionId'] = !empty($data['unionId']) ? $data['unionId'] : '';
        unset($data['watermark']);
        return $data;
    }

    public static function curl_get($url){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;    //返回json对象
    }

    public function actionLogin(){
        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $name = $data['username'];
        $password = $data['password'];
        /*$member = Member::find()->where(['username'=>$name,'password'=>md5($password)])->one();
        if($member){
            $token_data = ['uid' => $member->id, 'loginName' => $name ]; //默认sid];
            $token = static::getToken($token_data);
            $data = ['token' => $token, 'uid' => $member->id, 'login_name' => $name ]; //默认sid];*/
            //return Json::encode(['state'=>1,'text'=>'','data'=>$data]);
       // }
        $data['code']=20000;
        $data['data']['token']='amdin';
        return Json::encode($data);


        return Json::encode(['state'=>0,'text'=>'错误的用户名密码']);



    }

    public function actionInfo(){
        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $name = $data['username'];
        $password = $data['password'];
        /*$member = Member::find()->where(['username'=>$name,'password'=>md5($password)])->one();
        if($member){
            $token_data = ['uid' => $member->id, 'loginName' => $name ]; //默认sid];
            $token = static::getToken($token_data);
            $data = ['token' => $token, 'uid' => $member->id, 'login_name' => $name ]; //默认sid];*/
        //return Json::encode(['state'=>1,'text'=>'','data'=>$data]);
        // }
        $data['code']=20000;
        $data['data']['avatar']='https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $data['data']['name']='xiaojie';
        $data['data']['roles']='admin';
        return Json::encode($data);


        return Json::encode(['state'=>0,'text'=>'错误的用户名密码']);



    }

    public function actionTable(){

        $keyword = Yii::$app->request->get('keyword');
        $limit = Yii::$app->request->get('pagesize',10);
        $page = Yii::$app->request->get('page',1);
        $offset = ($page-1)*$limit;

        $list = News::find()->asArray()
       ->limit($limit)
        ->offset($offset)
        ->andFilterWhere(['like','title',$keyword])
        ->all();
        foreach ($list as $k=>$v){
            $list[$k]['content'] = mb_substr(strip_tags($v['content']),0,50);
            $list[$k]['create_time'] = date('Y-m-d H:i',$v['create_time']);
        }
        $data['code']=20000;
        $data['data']=$list;
        return Json::encode($data);


        return Json::encode(['state'=>0,'text'=>'错误的用户名密码']);

    }

    public function actionCaseList(){

        $keyword = Yii::$app->request->get('keyword');
        $limit = Yii::$app->request->get('pagesize',10);
        $page = Yii::$app->request->get('page',1);
        $offset = ($page-1)*$limit;

        $list = Cases::find()->asArray()
        ->limit($limit)
        ->offset($offset)
        ->andFilterWhere(['like','title',$keyword])
        ->all();
        foreach ($list as $k=>$v){
            $list[$k]['content'] = mb_substr(strip_tags($v['content']),0,50);
            $list[$k]['create_time'] = date('Y-m-d H:i',$v['create_time']);
        }
        $data['code']=20000;
        $data['data']=$list;
        return Json::encode($data);


        return Json::encode(['state'=>0,'text'=>'错误的用户名密码']);

    }

    public function actionDeleteTable(){

        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $id = $data['id'];
        $limit = Yii::$app->request->get('pagesize',10);
        $page = Yii::$app->request->get('page',1);
        $offset = ($page-1)*$limit;
        News::deleteAll(['id'=>$id]);
        
        $list = News::find()->asArray()
        ->limit($limit)
        ->offset($offset)
        ->all();
        foreach ($list as $k=>$v){
            $list[$k]['content'] = mb_substr($v['content'],0,50);
            $list[$k]['create_time'] = date('Y-m-d H:i',$v['create_time']);
        }
        $data['code']=20000;
        $data['data']=$list;
        return Json::encode($data);


        return Json::encode(['state'=>0,'text'=>'错误的用户名密码']);

    }

    public function actionDeleteCase(){

        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $id = $data['id'];
        $limit = Yii::$app->request->get('pagesize',10);
        $page = Yii::$app->request->get('page',1);
        $offset = ($page-1)*$limit;
        Cases::deleteAll(['id'=>$id]);
        
        $list = Cases::find()->asArray()
        ->limit($limit)
        ->offset($offset)
        ->all();
        foreach ($list as $k=>$v){
            $list[$k]['content'] = mb_substr($v['content'],0,50);
            $list[$k]['create_time'] = date('Y-m-d H:i',$v['create_time']);
        }
        $data['code']=20000;
        $data['data']=$list;
        return Json::encode($data);

    }

    public function actionFollowList(){
        $uid = Yii::$app->user->id;
        $res=FollowRelation::find()
            ->select(['m.username','m.id','m.brief','m.avatar'])
            ->from(FollowRelation::tableName().' fr')
            ->innerJoin(Member::tableName().' m', 'm.id=fr.user_id')
            ->where(['follower_id'=>$uid])->asArray()->all();
        $a['state'] = 1;
        $a['data'] = $res;
        return Json::encode($a);
    }


    /**
     * 生成token
     * @param $data
     * @return mixed
     */
    public static function getToken($data)
    {
        $token1 = Yii::$app->jwt->getBuilder()->setIssuer('jztw.com')// Configures the issuer (iss claim)
        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
        //->setNotBefore(time() + 60) // Configures the time before which the token cannot be accepted (nbf claim)
        ->setExpiration(time() + 3600*3); // 过期时间
        foreach ($data as $k => $v) {
            $token1->set($k, $v);
        }
        $token = $token1->getToken();
        return (string)$token;
    }
}
