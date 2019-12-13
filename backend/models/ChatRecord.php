<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "yii_general_chat_record".
 *
 * @property integer $id
 * @property string $content
 * @property integer $update_time
 */
class ChatRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii_general_chat_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'update_time' => 'Update Time',
        ];
    }
}
