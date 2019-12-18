<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_notice}}".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $content
 * @property integer $user_id
 */
class ZhihuNotice extends \yii\db\ActiveRecord
{
    /**
     * 通知类型
     * @var integer 评论与赞同
     */
    const NOTICE_TYPE_COMMENT = 1;

    /**
     * 通知类型
     * @var integer 回答关注问题
     */
    const NOTICE_TYPE_ANSWER = 2;


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
            [['type', 'content', 'user_id'], 'integer'],
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
            'user_id' => 'User ID',
        ];
    }
}
