<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_answer_follow}}".
 *
 * @property integer $id
 * @property integer $answer_id
 * @property integer $user_id
 * @property integer $create_time
 */
class ZhihuAnswerFollow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_answer_follow}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answer_id', 'user_id', 'create_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'answer_id' => 'Answer ID',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
