<?php
namespace backend\controllers;

use backend\component\CController;
use general\models\ZhihuAnswer;
use general\models\ZhihuComment;
use general\models\ZhihuMember;
use general\models\ZhihuQuestion;
use backend\repositories\QuestionRepository;
use Yii;
use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * 问题控制器
 */
class AnswerController extends CController
{



    /**
     * Displays homepage.
     *
     * @return string
     */
    public $layout = false;
    public $enableCsrfValidation = false;

    /**
     * 问题资源库
     * @var QuestionRepository
     */
    private $questionRepository;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function __construct($id, Module $module, array $config = [])
    {
        $this->questionRepository = new QuestionRepository();
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(){
        $answers = $this->page(ZhihuAnswer::find())->all();
        return $this->success($answers,ZhihuAnswer::find()->count());
    }


    public function actionDelete(){
        $data = file_get_contents('php://input');
        $data = Json::decode($data);
        ZhihuAnswer::deleteAll(['id'=>$data['id']]);

        return $this->success([],ZhihuAnswer::find()->count());
    }

    /**
     * 评论列表
     * @return mixed
     */
    public function actionGetCommentList(){
        $data = file_get_contents('php://input');
        $data = Json::decode($data);
        $query = ZhihuComment::find()
            ->select(['user.*','comment.*'])
            ->from(ZhihuComment::tableName().' comment')
            ->innerJoin(ZhihuMember::tableName().' user','user.id=comment.author_id')
            ->where(['comment.answer_id'=>$data['id']]);
        $query->andFilterWhere(['like', 'user.username',$data['keyword']]);
        $clone = clone $query;
        $answers = $this->page($query)->asArray()->all();
        foreach ($answers as $k=>$v){
            $answers[$k]['create_time'] = date('Y-m-d',$v['create_time']);
        }
        return $this->success($answers,$clone->count());
    }




}