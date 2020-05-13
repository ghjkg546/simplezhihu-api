<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_question_invite}}".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $invitee_id
 * @property integer $invited_id
 */
class ZhihuQuestionInvite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_question_invite}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'invitee_id', 'invited_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_id' => 'Question ID',
            'invitee_id' => 'Invitee ID',
            'invited_id' => 'Invited ID',
        ];
    }
}
