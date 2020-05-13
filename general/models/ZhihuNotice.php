<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_notice}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $content
 * @property integer $content_id
 * @property integer $user_id
 * @property integer $create_time
 */
class ZhihuNotice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'content_id', 'user_id', 'create_time'], 'integer'],
            [['content'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'content' => 'Content',
            'content_id' => 'Content ID',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
