<?php

namespace general\repositories;

use general\components\JwtTool;
use general\models\FollowRelation;
use general\models\Member;
use general\models\ZhihuAnswer;
use general\models\ZhihuAnswerLike;
use general\models\ZhihuAnswerThank;
use general\models\ZhihuFav;
use general\models\ZhihuFavCategory;

/**
 *
 */
class AnswerRepository
{

    public function one($data)
    {
        $answer = ZhihuAnswer::find()
            ->asArray()
            ->where(['id' => $data['id']])
            ->one();
        $user_id = JwtTool::getUserId();
        $answer['author'] = Member::findOne($answer['author_id']);

        $answer_count = ZhihuAnswer::find()->where(['question_id' => $answer['question_id']])->count();
        $result['answer_count'] = $answer_count;
        $is_fav = ZhihuFav::find()->where(['answer_id' => $data['id'], 'user_id' => $user_id])->one();
        $result['is_fav'] = !empty($is_fav) ? 1 : 0;
        $result['content'] = $answer;
        $answers_per_cate = ZhihuFav::find()->select(['answer_count' => 'count(*)', 'category_id'])
            ->where(['user_id' => $user_id])
            ->groupBy('category_id')->indexBy('category_id')->column();
        $fav = ZhihuFavCategory::find()->where(['user_id' => JwtTool::getUserId()])->asArray()->all();;
        $followed = FollowRelation::findOne(['user_id' => $answer['author_id'], 'follower_id' => $user_id]);
        $result['follow_text'] = !empty($followed) ? '已关注' : '关注';

        $thank = ZhihuAnswerThank::findOne(['answer_id' => $data['id'], 'user_id' => $user_id]);
        $result['thank_text'] = !empty($thank) ? '已感谢' : '感谢';
        foreach ($fav as $k => $v) {
            $fav[$k]['answer_count'] = isset($answers_per_cate[$v['id']]) ? $answers_per_cate[$v['id']] : 0;
        }
        $result['fav'] = $fav;
        $liked = ZhihuAnswerLike::findOne(['answer_id' => $data['id'], 'user_id' => $user_id]);
        $result['content']['liked'] = !empty($liked) ? 1 : 0;
        return $result;
    }

    public function thank($data)
    {
        $answer_id = $data['answer_id'];
        $user_id = JwtTool::getUserId();
        $followed = ZhihuAnswerThank::findOne(['user_id' => $user_id, 'answer_id' => $answer_id]);
        if (!$followed) {
            $fl = new ZhihuAnswerThank();
            $fl->user_id = $user_id;
            $fl->answer_id = $answer_id;
            $fl->save();
            $p['thank_text'] = '已感谢';
        } else {
            ZhihuAnswerThank::deleteAll(['user_id' => $user_id, 'answer_id' => $answer_id]);
            $p['thank_text'] = '感谢';
        }
        return $p;
    }

    public function like($data){
        $answer_id = $data['id'];
        $answer = ZhihuAnswer::findOne($answer_id);
        $user_id = JwtTool::getUserId();
        $liked = ZhihuAnswerLike::findOne(['answer_id' => $answer_id, 'user_id' => $user_id]);
        $result = ['code'=>0,'data'=>[]];
        if (empty($liked)) {
            $like = new ZhihuAnswerLike();
            $like->answer_id = $answer_id;
            $like->user_id = $user_id;
            $like->save();
            $answer->up_count += 1;
            $result['data']['liked'] = 1;
        } else {
            ZhihuAnswerLike::deleteAll(['answer_id' => $answer_id, 'user_id' => $user_id]);
            $answer->up_count -= 1;
            $result['data']['liked'] = 0;
        }
        $result['data']['up_count'] = (int)$answer->up_count;

        $answer->save();
        return $result;
    }

}
