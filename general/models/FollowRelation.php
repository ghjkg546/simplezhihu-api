<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "follow_relation".
 *
 * @property integer $user_id
 * @property integer $follower_id
 */
class FollowRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'follow_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'follower_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'follower_id' => 'Follower ID',
        ];
    }

    public static function primaryKey() {
        return ['user_id','follower_id'];//自定义主键
    }
}
