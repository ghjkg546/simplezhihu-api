<?php

namespace general\repositories;

use general\components\JwtTool;
use general\models\FollowRelation;
use general\models\Member;
use general\models\ZhihuAnswer;
use general\models\ZhihuAnswerLike;
use general\models\ZhihuAnswerThank;
use general\models\ZhihuComment;
use general\models\ZhihuCommentLikeUser;
use general\models\ZhihuFav;
use general\models\ZhihuFavCategory;

/**
 *
 */
class CommentRepository
{

    public function write($data){
        $answer_id = $data['id'];
        $commnet = new ZhihuComment();
        $commnet->author_id = 1;
        $commnet->create_time = time();
        $commnet->vote_count = 0;
        $commnet->content = $data['content'];
        $commnet->answer_id = $answer_id;
        if (!$commnet->save()) {
            $res['text'] = $commnet->getErrors();
            return $res;
        }
        $res = ZhihuComment::find()
            ->with('author')
            ->where(['answer_id' => $answer_id])->asArray()->all();
        foreach ($res as $k => $v) {
            $res[$k]['create_time'] = date('m-d H:i', $res[$k]['create_time']);
        }
        return $res;
    }

    public function like($data){
        $answer_id = $data['id'];
        $comment_id = $data['comment_id'];
        $answer = ZhihuComment::findOne($comment_id);

        $user_id = JwtTool::getUserId();
        $liked = ZhihuCommentLikeUser::findOne(['user_id' => $user_id, 'comment_id' => $comment_id]);
        $comment = ZhihuComment::findOne($comment_id);

        if (empty($liked)) {

            $comment_like = new ZhihuCommentLikeUser();
            $comment_like->user_id = $user_id;
            $comment_like->comment_id = $comment_id;
            $comment_like->save();
            $comment->vote_count += 1;
        } else {
            $comment->vote_count -= 1;
            ZhihuCommentLikeUser::deleteAll(['user_id' => $user_id, 'comment_id' => $comment_id]);
        }
        $comment->save();
        $res = ZhihuComment::find()
            ->with('author')
            ->where(['answer_id' => $answer_id])->asArray()->all();
        $liked_ids = ZhihuCommentLikeUser::find()->select(['comment_id'])->where(['comment_id'=>array_column($res,'id')])->column();
        foreach ($res as $k => $v) {
            $res[$k]['create_time'] = date('m-d H:i', $res[$k]['create_time']);
            $res[$k]['liked'] = in_array($v['id'],$liked_ids)?1:0;
        }
        return $res;
    }
}
