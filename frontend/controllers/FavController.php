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
 * 收藏控制器
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
     * 收藏答案列表
     * @return string
     */
    public function actionList()
    {
        $post = file_get_contents('php://input');
        $post = Json::decode($post);
        $answer_ids = ZhihuFav::find()->where(['category_id' => $post['category_id']])->select(['answer_id'])->column();
        $query = ZhihuAnswer::find()
            ->asArray();
        $query->andWhere(['id' => $answer_ids]);

        $answers = $query->all();
        $question_titles = ZhihuQuestion::find()->select('title')
            ->where(['id' => array_unique(array_column($answers, 'question_id'))])->indexBy('id')->column();
        foreach ($answers as $k => $v) {
            $answers[$k]['question_title'] = $question_titles[$v['question_id']];
            $answers[$k]['content'] = mb_substr(strip_tags($v['content']), 0, 100);
        }
        return Json::encode($answers);
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
        return Json::encode(['code' => 1, 'fav' => $fav]);
    }

    /**
     * 添加收藏文件夹
     * @return string
     */
    public function actionAddNewFolder()
    {
        $post = file_get_contents('php://input');
        $post = Json::decode($post);
        $fav_cate = new ZhihuFavCategory();
        $user_id = JwtTool::getUserId();
        if(empty($user_id)){
            return Json::encode(['code' => 0, 'msg' => '请先登录']);
        }
        $fav_cate->user_id = $user_id;
        $fav_cate->category_name = $post['category_name'];
        $fav_cate->save();
        $answers_per_cate = ZhihuFav::find()->select(['answer_count' => 'count(*)', 'category_id'])
            ->where(['user_id' => JwtTool::getUserId()])
            ->groupBy('category_id')->indexBy('category_id')->column();
        $fav = ZhihuFavCategory::find()->asArray()->all();;
        foreach ($fav as $k => $v) {
            $fav[$k]['answer_count'] = isset($answers_per_cate[$v['id']]) ? $answers_per_cate[$v['id']] : 0;
        }
        $result['fav'] = $fav;
        return Json::encode(['code' => 1, 'fav' => $fav]);
    }

    /**
     * 取消收藏
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
        $fav = ZhihuFavCategory::find()->asArray()->where(['user_id' => JwtTool::getUserId()])->all();;
        foreach ($fav as $k => $v) {
            $fav[$k]['answer_count'] = isset($answers_per_cate[$v['id']]) ? $answers_per_cate[$v['id']] : 0;
        }
        $result['fav'] = $fav;
        return Json::encode(['code' => $count > 0 ? 1 : 0, 'fav' => $fav]);
    }

}
