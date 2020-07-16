<?php

namespace backend\repositories;

use general\components\JwtTool;
use general\models\FollowRelation;
use general\models\Member;
use general\models\ZhihuAnswer;
use general\models\ZhihuAnswerLike;
use general\models\ZhihuAnswerThank;
use general\models\ZhihuFav;
use general\models\ZhihuFavCategory;
use general\models\ZhihuMember;
use general\models\ZhihuQuestionInvite;

/**
 *
 */
class InviteRepository
{

    public function memberList($question_id){
        $members = ZhihuMember::find()->limit(5)->asArray()->all();
        $my_invite_ids = ZhihuQuestionInvite::find()->where(['invited_id'=>JwtTool::getUserId(),'question_id'=>$question_id])->select(['invitee_id'])->column();
        foreach ($members as $k=>$v){

            $members[$k]['invited'] = in_array($v['id'],$my_invite_ids)?true:false;
            $members[$k]['invite_text'] = in_array($v['id'],$my_invite_ids)?'已邀请':'邀请';
        }
        return $members;
    }
}
