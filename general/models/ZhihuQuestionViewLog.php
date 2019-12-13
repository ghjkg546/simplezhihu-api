<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_question_view_log}}".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $view_time
 * @property integer $user_id
 */
class ZhihuQuestionViewLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_question_view_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'view_time', 'user_id'], 'integer'],
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
            'view_time' => 'View Time',
            'user_id' => 'User ID',
        ];
    }
}
