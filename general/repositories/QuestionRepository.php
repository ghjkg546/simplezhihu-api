<?php

namespace general\repositories;

use general\components\JwtTool;
use general\models\FollowRelation;
use general\models\Member;
use general\models\ZhihuAnswer;
use general\models\ZhihuAnswerLike;
use general\models\ZhihuAnswerThank;
use general\models\ZhihuComment;
use general\models\ZhihuFav;
use general\models\ZhihuFavCategory;
use general\models\ZhihuMember;
use general\models\ZhihuNotice;
use general\models\ZhihuQuestion;
use general\models\ZhihuQuestionFollow;
use Yii;
use yii\helpers\ArrayHelper;

/**
 *
 */
class QuestionRepository
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
        $qu = ZhihuQuestion::find()
            ->asArray();
        $type = $data['type'];
        if ($type == 1) {
            $qu->orderBy('up_count desc');
        } elseif ($type == static::QUESTION_TYPE_FOLLOW) {
            $uid = JwtTool::getUserId();
            $ids = FollowRelation::find()->select(['user_id'])->where(['follower_id' => $uid])->column();
            $qu->andWhere(['author_id' => $ids]);
        }
        $page_size = 2;
        if(!empty($data['page'])){
            $qu->offset(($data['page']-1)*$page_size)->limit($page_size);
        }
        $keyword = ArrayHelper::getValue($data, 'keyword', null);
        $qu->andFilterWhere(['like', 'content', $keyword]);
        $count_query = clone $qu;
        $questions = $qu->all();
        $question_ids = array_column($questions, 'id');

        $authors = ZhihuMember::find()->select('username')->indexBy('id')->column();
        $most_popular_answers = ZhihuAnswer::find()->select(['like_count' => 'max(up_count)', 'id', 'question_id', 'up_count', 'content'])
            ->where(['question_id' => $question_ids])
            ->groupBy('question_id')
            ->indexBy('question_id')
            ->asArray()->all();
        $comment_counts = ZhihuComment::find()->select(['comment_count' => 'count(*)', 'answer_id'])
            ->where(['answer_id' => array_column($most_popular_answers, 'id')])
            ->groupBy('answer_id')
            ->indexBy('answer_id')
            ->asArray()->column();
        $follow_counts = ZhihuQuestionFollow::find()->select(['follow_count' => 'count(*)', 'question_id'])
            ->where(['question_id' => $question_ids])
            ->groupBy('question_id')
            ->indexBy('question_id')
            ->asArray()->column();
        foreach ($questions as $k => $v) {
            $questions[$k]['question_content'] = mb_substr($v['content'], 0, 80);
            $content = isset($most_popular_answers[$v['id']]['content']) ? $most_popular_answers[$v['id']]['content'] : '暂无回答';
            $questions[$k]['content'] = mb_substr($content, 0, 80);

            $questions[$k]['author_name'] = $authors[$v['author_id']];
            $questions[$k]['like_count'] = isset($most_popular_answers[$v['id']]['up_count']) ? $most_popular_answers[$v['id']]['up_count'] : 0;
            $questions[$k]['answer_id'] = isset($most_popular_answers[$v['id']]['id']) ? $most_popular_answers[$v['id']]['id'] : 0;
            $questions[$k]['comment_count'] = isset($comment_counts[$questions[$k]['answer_id']]) ? $comment_counts[$questions[$k]['answer_id']] : 0;
            $questions[$k]['follow_count'] = isset($follow_counts[$v['id']]) ? $follow_counts[$v['id']] : 0;
            $questions[$k]['create_time'] = date('Y-m-d h:i:s', $v['create_time']);
        }
        return ['data'=>$questions,'count'=>$count_query->count()];
    }

    public function one($data)
    {
        $question = ZhihuQuestion::findOne($data['id']);
        $answers = ZhihuAnswer::find()
            ->asArray()
            ->where(['question_id' => $data['id']])
            ->all();
        $authors = Member::find()->select(['username'])->indexBy('id')->column();
        $comment_count = ZhihuComment::find()->select(['comment_count' => 'count(*)', 'answer_id'])
            ->where(['answer_id' => array_column($answers, 'id')])
            ->groupBy('answer_id')
            ->indexBy('answer_id')
            ->asArray()->column();
        foreach ($answers as $k => $v) {
            $answers[$k]['comment_count'] = isset($comment_count[$v['id']]) ? $comment_count[$v['id']] : 0;
            $answers[$k]['create_time'] = date('Y-m-d H:i',$v['create_time']);
            $answers[$k]['author_name'] = isset($authors[$v['author_id']])?$authors[$v['author_id']]:'';
            $answers[$k]['up_count'] = empty($v['up_count']) ? 0 : $v['up_count'];
        }
        $result['question'] = $question;
        $result['answers'] = $answers;
        $result['follow'] = ZhihuQuestionFollow::find()->where(['question_id' => $data['id'], 'user_id' => JwtTool::getUserId()])->one() ? 1 : 0;
        $follow_count = ZhihuQuestionFollow::find()->where(['question_id' => $data['id']])->count();
        $result['follow_count'] = !empty($follow_count) ? $follow_count : 0;


        $result['invite_member'] = $this->question_invite->memberList($data['id']);
        return $result;
    }

    public function writeAnswer($data)
    {
        $author_id = JwtTool::getUserId();
        $answer = new ZhihuAnswer();
        $answer->author_id = $author_id;
        $answer->content = $data['answer_content'];
        $answer->create_time = time();
        $answer->question_id = $data['question_id'];
        $answer->save();
        $answers = ZhihuAnswer::find()
            ->asArray()
            ->where(['question_id' => $data['question_id']])
            ->all();

        $followed = ZhihuQuestionFollow::find()->select(['user_id'])->where(['question_id' => $data['question_id']])->column();
        if ($followed) {
            $author_name = ZhihuMember::find()->select(['username'])->where(['id' => $author_id])->scalar();
            //发消息
            foreach ($followed as $v) {
                $notice_list[] = [
                    'type' => ZhihuNotice::NOTICE_TYPE_ANSWER, 'content' => "{$author_name}回答了问题",
                    'content_id' => $data['question_id'], 'user_id' => $v
                ];
            }
            Yii::$app->db->createCommand()
                ->batchInsert(ZhihuNotice::tableName(), array_keys($notice_list[0]), $notice_list)->execute();
        }

        $authors = Member::find()->select(['username'])->indexBy('id')->column();
        foreach ($answers as $k => $v) {
            $answers[$k]['author_name'] = isset($authors[$v['author_id']])?$authors[$v['author_id']]:'';
            $answers[$k]['up_count'] = empty($v['up_count']) ? 0 : $v['up_count'];
        }
        $result['code'] = 1;
        $result['answers'] = $answers;
        return $result;
    }

}
