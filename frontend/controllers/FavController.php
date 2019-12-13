<?php
namespace frontend\controllers;

use backend\models\ZhihuQuestion;
use general\models\FollowRelation;
use general\models\Member;
use general\models\ZhihuAnswer;
use general\models\ZhihuFav;
use general\models\ZhihuQuestionViewLog;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Site controller
 */
class FavController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public $layout=false;
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $post=file_get_contents('php://input');
        $post=Json::decode($post);
        switch ($post['type']){
            case 'recent':
                $questions=ZhihuQuestionViewLog::find()->from(ZhihuQuestionViewLog::tableName().' log')
                    ->select(['question.*','log.view_time'])->innerJoin(ZhihuQuestion::tableName().' question','log.question_id=question.id')
                    ->orderBy('log.view_time desc')
                    ->asArray()->all();
                foreach ($questions as $k=>$v){
                    $questions[$k]['content'] = mb_substr(strip_tags( $v['content']),0,50);

                }
                return Json::encode($questions);
                break;

            case 'fav':
                $data= ZhihuFav::find()->select(['cate_count' => 'count(*)','category_name'])->groupBy('category_name')->asArray()->all();
                return Json::encode($data);
                break;
        }

    }

    public function actionList()
    {
        $post=file_get_contents('php://input');
        $post=Json::decode($post);
        $answer_ids = ZhihuFav::find()->where(['category_name'=>$post['category_name']])->select(['answer_id'])->column();
        $qu=ZhihuAnswer::find()
            ->asArray()
            ->with('vote_member');
        $uid =Yii::$app->user->id;
        $uid = 1;
        $ids= FollowRelation::find()->select(['user_id'])->where(['follower_id'=>$uid])->column();
        $qu->andWhere(['author_id'=>$ids]);
        $p=$qu->all();
        $member= Member::find()->indexBy('id')->asArray()->all();
        foreach ($p as $k=>$v){
            if(!empty($v['vote_member'])){
                foreach ($v['vote_member'] as $k1=>$v1){
                    $p[$k]['vote_member'][$k1]['name'] = $member[$v1['member_id']]['username'];
                }
            }
        }
        foreach ($p as $k=>$v){
            $p[$k]['voter'] = !empty($v['vote_member'][0]['name'])? $v['vote_member'][0]['name']:'还没人';
        }
        return Json::encode($p);

    }

}
