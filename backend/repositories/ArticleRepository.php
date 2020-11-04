<?php

namespace backend\repositories;

use general\components\JwtTool;
use general\models\FollowRelation;
use general\models\Member;
use general\models\ZhihuAnswer;
use general\models\ZhihuArticle;
use general\models\ZhihuComment;
use general\models\ZhihuMember;
use general\models\ZhihuQuestion;
use general\models\ZhihuQuestionFollow;
use Yii;
use yii\helpers\ArrayHelper;

/**
 *
 */
class ArticleRepository
{

    /**
     * 问题类型
     * @var int 关注
     */
    const QUESTION_TYPE_FOLLOW = 0;

    /**
     * 问题类型
     * @var int 关注
     */
    const QUESTION_TYPE_RECOMMEND = 1;

    /**
     * 问题类型
     * @var int 关注
     */
    const QUESTION_TYPE_LIST = 2;

    private $question_invite;

    function __construct()
    {
        $this->question_invite = new InviteRepository();
    }

    public function findAll($data)
    {
        $qu = ZhihuArticle::find()
            ->asArray();
        //$type = $data['type'];
        $type=1;
        if ($type == 1) {
            $qu->orderBy('up_count desc');
        } elseif ($type == static::QUESTION_TYPE_FOLLOW) {
            $uid = JwtTool::getUserId();
            $ids = FollowRelation::find()->select(['user_id'])->where(['follower_id' => $uid])->column();
            $qu->andWhere(['author_id' => $ids]);
        }
        $keyword = ArrayHelper::getValue($data, 'keyword', null);
        $qu->andFilterWhere(['like', 'content', $keyword]);
        $query = clone $qu;
        $count = $query->count();
        $page_size = 5;
        if(!empty($data['page'])){
            $qu->offset(($data['page']-1)*$page_size)->limit($page_size);
        }

        $questions = $qu->all();
        $authors = ZhihuMember::find()->select('username')->indexBy('id')->column();
        foreach ($questions as $k => $v) {
            $questions[$k]['question_content'] = mb_substr($v['content'], 0, 80);
            $questions[$k]['author_name'] = isset($authors[$v['author_id']])?$authors[$v['author_id']]:'';
            //$questions[$k]['follow_count'] = isset($follow_counts[$v['id']]) ? $follow_counts[$v['id']] : 0;
            $questions[$k]['create_time'] = date('Y-m-d h:i:s', $v['create_time']);
        }
        return ['data'=>$questions,'count'=>$count];
    }

    public function one($data)
    {
        $authors = ZhihuMember::find()->asArray()->all();
        foreach ($authors as $k=>$v){
            $authors[$k]['id'] = intval($v['id']);
        }

        $result['authors'] = $authors;
        if(empty($data['id'])){
            return $result;
        }
        $question = ZhihuArticle::findOne($data['id']);
        $answers = ZhihuAnswer::find()
            ->asArray()
            ->where(['question_id' => $data['id']])
            ->all();
        $authors = Member::find()->select(['username'])->indexBy('id')->column();
        foreach ($answers as $k => $v) {
            $answers[$k]['author_name'] = $authors[$v['author_id']];
            $answers[$k]['up_count'] = empty($v['up_count']) ? 0 : $v['up_count'];
        }
        $result['question'] = $question;
        $result['answers'] = $answers;
        $result['follow'] = ZhihuQuestionFollow::find()->where(['question_id' => $data['id'], 'user_id' => JwtTool::getUserId()])->one() ? 1 : 0;
        $follow_count = ZhihuQuestionFollow::find()->where(['question_id' => $data['id']])->count();
        $result['follow_count'] = !empty($follow_count) ? $follow_count : 0;
        $comment_count = ZhihuComment::find()->where(['answer_id' => array_column($answers, 'id')])->count();
        $result['comment_count'] = !empty($comment_count) ? $comment_count : 0;

        $result['invite_member'] = $this->question_invite->memberList($data['id']);
        return $result;
    }


}
