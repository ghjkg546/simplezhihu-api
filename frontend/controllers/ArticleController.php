<?php
namespace frontend\controllers;

use general\models\ArticleComment;
use general\models\Bike;
use general\models\ZhihuComment;
use general\models\FollowRelation;
use general\models\Member;
use general\models\News;
use general\models\Cases;
use general\models\CaseCate;
use general\models\RidingRecord;
use general\models\Category;
use general\models\VoteMember;
use general\models\ZhihuAnswer;
use general\models\ZhihuArticle;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * 攻略文章控制器
 */
class ArticleController extends Controller
{


    /**
     * Displays homepage.
     *
     * @return string
     */
    public $layout = false;
    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function actionIndex()
    {

    }


    /**
     * 文章列表
     * @return string
     */
    public function actionList()
    {
        $data = file_get_contents('php://input');
        $data = Json::decode($data);
        $article_query = ZhihuArticle::find()
            ->where(['question_id' => 0]);
        $clone = clone $article_query;
        $articles = $article_query->asArray()->all();

        $authors = Member::find()->select(['username'])->indexBy('id')->column();
        foreach ($articles as $k => $v) {
            $articles[$k]['author'] = $authors[$v['author_id']];
            $articles[$k]['up_count'] = empty($v['up_count']) ? 0 : $v['up_count'];
            $articles[$k]['timestamp'] = time();
            $articles[$k]['platforms'] = ['aa'];
            $articles[$k]['status'] = 'deleted';
            $articles[$k]['cover_img'] = !empty($p[$k]['cover_img']) ? Yii::$app->request->getHostInfo() . $p[$k]['cover_img'] : '';
        }
        $data['code'] = 1;
        $data['data']['items'] = $articles;
        $data['data']['total'] = $clone->count();
        return Json::encode($data);
    }

    /**
     * 文章详情
     * @return string
     */
    public function actionDetail()
    {

        $data = file_get_contents('php://input');
        $data = Json::decode($data);
        $detail = ZhihuArticle::findOne($data['id'])->toArray();

        $author = Member::findOne($detail['author_id'])->toArray();
        $detail['author'] = $author;
        $detail['create_time'] = date('Y-m-d', $detail['create_time']);
        $detail['cover_img'] = !empty($detail['cover_img']) ? Yii::$app->request->getHostInfo() . $detail['cover_img'] : '';
        $detail['comments'] = ArticleComment::find()->where(['article_id' => $data['id']])->asArray()->all();
        $data['code'] = 1;
        $data['data'] = $detail;
        return Json::encode($data);
    }

    /**
     * 评论文章
     * @return string
     */
    public function actionLeaveArticleComment()
    {
        $data = file_get_contents('php://input');
        $data = Json::decode($data);
        $comment = new ArticleComment();
        $comment->author_id = 1;
        $comment->create_time = time();
        $comment->content = $data['content'];
        $comment->article_id = $data['id'];
        if (!$comment->save()) {
            $res['text'] = $comment->getErrors();
            return Json::encode($res);
        }
        $res = ArticleComment::find()
            ->with('author')
            ->where(['article_id' => $data['id']])->asArray()->all();
        foreach ($res as $k => $v) {
            $res[$k]['create_time'] = date('H:i', $res[$k]['create_time']);
        }
        return Json::encode(['code' => 1, 'data' => $res]);
    }


}
