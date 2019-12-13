<?php
namespace backend\controllers;

use backend\models\Bike;
use backend\models\Comment;
use backend\models\FollowRelation;
use backend\models\Member;
use backend\models\Banner;
use backend\models\CaseCate;
use backend\models\Cases;
use backend\models\News;
use yii\web\UploadedFile;
use backend\models\SystemSetting;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SystemController extends Controller
{
    



    /**
     * Displays homepage.
     *
     * @return string
     */
   public $savePath = NULL;
    public $saveUrl = NULL;
    private $_fileInstance;
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

    

    public function actionDetail()
    {
        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $p=SystemSetting::find()
            ->asArray()
            ->one();
        $res = ['code'=>20000,'text'=>'success','data'=>$p];
        return Json::encode($res);
    }

    public function actionSaveDetail()
    {
        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        $p=SystemSetting::find()->one();
        $p->attributes = $data;
        if(!$p->save()){
            $res = ['code'=>20000,'text'=>$p->getErrors(),'data'=>$p];
        } else {
            $res = ['code'=>20000,'text'=>'success','data'=>$p];
        }
        
        return Json::encode($res);
    }

    

    /**
     * 生成token
     * @param $data
     * @return mixed
     */
    public static function getToken($data)
    {
        $token1 = Yii::$app->jwt->getBuilder()->setIssuer('jztw.com')// Configures the issuer (iss claim)
        ->setIssuedAt(time()); // Configures the time that the token was issue (iat claim)
        //->setNotBefore(time() + 60) // Configures the time before which the token cannot be accepted (nbf claim)
        //->setExpiration(time() + 3600); // 过期时间
        foreach ($data as $k => $v) {
            $token1->set($k, $v);
        }
        $token = $token1->getToken();
        return (string)$token;
    }

    public function actionBannerList()
    {
        $data=  Banner::find()->all();
        
            $res = ['code'=>20000,'text'=>'success','data'=>$data];
        
        
        return Json::encode($res);
    }

    public function actionCaseList()
    {

        $data=  CaseCate::find()->all();
        
            $res = ['code'=>20000,'text'=>'success','data'=>$data];
        
        
        return Json::encode($res);
    }

    public function actionDeleteBanner()
    {
        $data=file_get_contents('php://input');
        $data=Json::decode($data);
        Banner::deleteAll(['id'=>$data['id']]);
        $data=  Banner::find()->all();
        $res = ['code'=>20000,'text'=>'success','data'=>$data];
        return Json::encode($res);
    }

    public function actionUploadImg() {
        $this->savePath='images';
        $this->_fileInstance = UploadedFile::getInstanceByName('img');
        $id = Yii::$app->request->post('id');
        if (!$this->_fileInstance) {
            return Json::encode(['error' => 1, 'message' => '没有上传任何文件']);
        }
        $fileName = uniqid() . '.' . $this->_fileInstance->extension;
        if (strtolower($this->_fileInstance->extension) == 'php') {
            return Json::encode(['error' => 0, 'message' => '上传失败', 'url' => '']);
        }
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $this->_fileInstance->tempName);
        finfo_close($finfo);
        if (stristr($mimetype, 'text/plain') || stristr($mimetype, 'text/x-php')) {
            if (stristr(file_get_contents($this->_fileInstance->tempName), '?php') !== false) {
                return Json::encode(['error' => 0, 'message' => '上传失败', 'url' => '']);
            }
        }
        $flag = $this->_fileInstance->saveAs($this->savePath . DIRECTORY_SEPARATOR . $fileName);
        $banner1 = Banner::findOne($id);
        $banner = !empty($banner1)?$banner1:new Banner();
        $banner->img_url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->savePath . '/' . $fileName;
        $banner->save();
        $data=  Banner::find()->all();
        return Json::encode(['error' => $flag ? 0 : 1, 'message' => $flag ? '' : '上传失败', 'url' => 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->savePath . '/' . $fileName,'id'=>$banner->id,'data'=>$data]);
    }

    public function actionUploadCateImg() {
        $this->savePath='images';
        $this->_fileInstance = UploadedFile::getInstanceByName('img');
        $id = Yii::$app->request->post('id');
        if (!$this->_fileInstance) {
            return Json::encode(['error' => 1, 'message' => '没有上传任何文件']);
        }
        $fileName = uniqid() . '.' . $this->_fileInstance->extension;
        if (strtolower($this->_fileInstance->extension) == 'php') {
            return Json::encode(['error' => 0, 'message' => '上传失败', 'url' => '']);
        }
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $this->_fileInstance->tempName);
        finfo_close($finfo);
        if (stristr($mimetype, 'text/plain') || stristr($mimetype, 'text/x-php')) {
            if (stristr(file_get_contents($this->_fileInstance->tempName), '?php') !== false) {
                return Json::encode(['error' => 0, 'message' => '上传失败', 'url' => '']);
            }
        }
        $flag = $this->_fileInstance->saveAs($this->savePath . DIRECTORY_SEPARATOR . $fileName);
        $cate = CaseCate::findOne($id);
        $id = 0;
        if(!empty($cate)){
            $cate->img_url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->savePath . '/' . $fileName;
            $cate->save();
            $id = $cate->id;
        }
        
        
        
        $data=  CaseCate::find()->all();
        return Json::encode(['error' => $flag ? 0 : 1, 'message' => $flag ? '' : '上传失败', 'url' => 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->savePath . '/' . $fileName,'id'=>$id,'data'=>$data]);
    }

    public function actionSaveCaseCate(){
        if(Yii::$app->request->isPost){
            $data=file_get_contents('php://input');
            $data=Json::decode($data);

            $news = CaseCate::findOne($data['id']);
            $news = !empty($news)?$news:new CaseCate();
            $news->name = $data['name'];

            if(!$news->save()){
                $res = ['state'=>0,'text'=>$news->getErrors()];
                return Json::encode($res);
            };
            $res = ['code'=>20000,'text'=>'保存成功'];
            return Json::encode($res);
        
        }
    }

    public function actionDeleteCaseCate(){
        if(Yii::$app->request->isPost){
            $data=file_get_contents('php://input');
            $data=Json::decode($data);

            $news = CaseCate::deleteAll(['id'=>$data['id']]);
            $list = CaseCate::find()->all();
            $res = ['code'=>20000,'text'=>'删除成功','list'=>$list];
            return Json::encode($res);
        
        }
    }

    public function actionSaveCase(){
        if(Yii::$app->request->isPost){
            $data=file_get_contents('php://input');
            $data=Json::decode($data);

            $news = Cases::findOne($data['id']);

            $news = !empty($news)?$news:new Cases();
            $news->title = $data['title'];
            $news->content = $data['content'];
            $news->create_time = time();
            $news->img_url=$data['img_url'];
            $news->product=$data['product'];
            $news->industry=$data['industry'];

            $news->cate_id = $data['cate_id'];

            if(!$news->save()){
                $res = ['state'=>0,'text'=>$news->getErrors()];
                return Json::encode($res);
            };
            $res = ['code'=>20000,'text'=>'保存成功'];
            return Json::encode($res);
        
        }
    }
    

    


}
