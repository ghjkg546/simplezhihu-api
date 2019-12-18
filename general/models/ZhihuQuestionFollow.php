<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_question_follow}}".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $user_id
 * @property integer $create_time
 */
class ZhihuQuestionFollow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_question_follow}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'user_id', 'create_time'], 'integer'],
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
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
