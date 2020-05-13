<?php
namespace frontend\controllers;

use general\components\JwtTool;
use general\components\Tool;
use general\models\ZhihuFavCategory;
use general\models\ZhihuNotice;
use general\models\ZhihuQuestion;
use general\models\FollowRelation;
use general\models\Member;
use general\models\ZhihuAnswer;
use general\models\ZhihuFav;
use general\models\ZhihuQuestionInvite;
use general\models\ZhihuQuestionViewLog;
use general\repositories\InviteRepository;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Site controller
 */
class NoticeController extends Controller
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
                    ->where(['user_id'=>JwtTool::getUserId()])
                    ->asArray()->all();
                foreach ($questions as $k=>$v){
                    $questions[$k]['content'] = mb_substr(strip_tags( $v['content']),0,50);

                }
                return Json::encode($questions);
                break;

            case 'fav':
                $data= ZhihuFav::find()->select(['cate_count' => 'count(*)','category_name'])
                    ->where(['user_id'=>JwtTool::getUserId()])
                    ->groupBy('category_name')->asArray()->all();
                return Json::encode($data);
                break;
        }

    }

    /**
     * 通知列表
     * @return string
     */
    public function actionList()
    {
        $data = ZhihuNotice::find()->where(['user_id'=>JwtTool::getUserId()])->orderBy('create_time desc')->asArray()->all();
        foreach ($data as $k=>$v){
            if($v['type'] == 1){
                $data[$k]['title'] = '赞同与感谢';
            } else {
                $data[$k]['title'] = '有人回答了问题';
            }
            $data[$k]['create_time'] = Tool::get_last_time($v['create_time']);

        }
        return Json::encode(['state'=>1,'data'=>$data]);
    }

    /**
     * 邀请回答
     * @return string
     */
    public function actionInviteAnswer()
    {
        $data = file_get_contents('php://input');
        $data = Json::decode($data);
        $notice=  new ZhihuNotice();
        $notice->content_id = $data['content_id'];
        $notice->content = '有人邀请你回答';
        $notice->user_id = $data['user_id'];
        $notice->create_time = time();
        $notice->type = 3;
        if($notice->save()){
            $invite = new ZhihuQuestionInvite();
            $invite->question_id = $data['content_id'];
            $invite->invited_id = JwtTool::getUserId();
            $invite->invitee_id = $data['user_id'];
            $invite->save();
        }
        $invite_member= (new InviteRepository())->memberList($data['content_id']);
        return Json::encode(['code' => 1,'data'=>$invite_member]);
    }


}
