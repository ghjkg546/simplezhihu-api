<?php
namespace frontend\controllers;

use general\components\JwtTool;
use general\models\ZhihuFavCategory;
use general\models\ZhihuQuestion;
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
    public $layout = false;
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $post = file_get_contents('php://input');
        $post = Json::decode($post);
        switch ($post['type']) {
            case 'recent':
                $questions = ZhihuQuestionViewLog::find()->from(ZhihuQuestionViewLog::tableName() . ' log')
                    ->select(['question.*', 'log.view_time'])->innerJoin(ZhihuQuestion::tableName() . ' question', 'log.question_id=question.id')
                    ->orderBy('log.view_time desc')
                    ->where(['user_id' => JwtTool::getUserId()])
                    ->asArray()->all();
                foreach ($questions as $k => $v) {
                    $questions[$k]['content'] = mb_substr(strip_tags($v['content']), 0, 50);

                }
                return Json::encode($questions);
                break;

            case 'fav':
                $data = ZhihuFav::find()->select(['cate_count' => 'count(*)', 'category_id'])
                    ->where(['user_id' => JwtTool::getUserId()])
                    ->groupBy('category_id')->asArray()->all();
                $category = ZhihuFavCategory::find()->select(['category_name'])->indexBy('id')->column();
                foreach ($data as $k => $v) {
                    $data[$k]['category_name'] = isset($category[$v['category_id']]) ? $category[$v['category_id']] : '';
                }
                return Json::encode($data);
                break;
        }

    }

    /**
     * 收藏列表
     * @return string
     */
    public function actionList()
    {
        $post = file_get_contents('php://input');
        $post = Json::decode($post);
        $answer_ids = ZhihuFav::find()->where(['category_id' => $post['category_id']])->select(['answer_id'])->column();
        $qu = ZhihuAnswer::find()
            ->asArray()
            ->with('vote_member');
        $uid = 1;
        $ids = FollowRelation::find()->select(['user_id'])->where(['follower_id' => $uid])->column();
        $qu->andWhere(['author_id' => $ids]);
        $p = $qu->all();
        $member = Member::find()->indexBy('id')->asArray()->all();
        foreach ($p as $k => $v) {
            if (!empty($v['vote_member'])) {
                foreach ($v['vote_member'] as $k1 => $v1) {
                    $p[$k]['vote_member'][$k1]['name'] = $member[$v1['member_id']]['username'];
                }
            }
        }
        foreach ($p as $k => $v) {
            $p[$k]['voter'] = !empty($v['vote_member'][0]['name']) ? $v['vote_member'][0]['name'] : '还没人';
        }
        return Json::encode($p);
    }


    /**
     * 添加到收藏
     * @return string
     */
    public function actionAdd()
    {
        $post = file_get_contents('php://input');
        $post = Json::decode($post);
        $fav = new ZhihuFav();
        $fav->answer_id = $post['answer_id'];
        $fav->user_id = JwtTool::getUserId();
        $fav->category_id = $post['cate_id'];
        $fav->save();
        $answers_per_cate = ZhihuFav::find()->select(['answer_count' => 'count(*)', 'category_id'])
            ->where(['user_id' => JwtTool::getUserId()])
            ->groupBy('category_id')->indexBy('category_id')->column();
        $fav = ZhihuFavCategory::find()->asArray()->all();;
        foreach ($fav as $k => $v) {
            $fav[$k]['answer_count'] = isset($answers_per_cate[$v['id']]) ? $answers_per_cate[$v['id']] : 0;
        }
        $result['fav'] = $fav;
        return Json::encode(['state' => 1, 'fav' => $fav]);
    }

    /**
     * 添加到收藏
     * @return string
     */
    public function actionRemove()
    {
        $post = file_get_contents('php://input');
        $post = Json::decode($post);
        $count = ZhihuFav::deleteAll(['answer_id' => $post['answer_id'], 'user_id' => JwtTool::getUserId()]);
        $answers_per_cate = ZhihuFav::find()->select(['answer_count' => 'count(*)', 'category_id'])
            ->where(['user_id' => JwtTool::getUserId()])
            ->groupBy('category_id')->indexBy('category_id')->column();
        $fav = ZhihuFavCategory::find()->asArray()->all();;
        foreach ($fav as $k => $v) {
            $fav[$k]['answer_count'] = isset($answers_per_cate[$v['id']]) ? $answers_per_cate[$v['id']] : 0;
        }
        $result['fav'] = $fav;
        return Json::encode(['state' => $count > 0 ? 1 : 0, 'fav' => $fav]);
    }

}
