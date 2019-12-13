<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "vote_member".
 *
 * @property integer $answer_id
 * @property integer $member_id
 * @property integer $create_time
 */
class VoteMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vote_member';
    }

    public static function primaryKey(){
        return array('answer_id', 'member_id');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answer_id', 'member_id', 'create_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'answer_id' => 'Answer ID',
            'member_id' => 'Member ID',
            'create_time' => 'Create Time',
        ];
    }
}
