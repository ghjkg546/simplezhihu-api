<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_comment_like_user}}".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $user_id
 */
class ZhihuCommentLikeUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_comment_like_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment_id' => 'Comment ID',
            'user_id' => 'User ID',
        ];
    }
}
