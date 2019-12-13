<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "zhihu_answer".
 *
 * @property integer $id
 * @property string $content
 * @property integer $create_time
 * @property integer $up_count
 * @property integer $author_id
 * @property integer $has_vote
 */
class ZhihuFollowUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zhihu_follow_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'up_count', 'author_id', 'has_vote'], 'integer'],
            [['content'], 'string', 'max' => 500],
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
            'create_time' => 'Create Time',
            'up_count' => 'Up Count',
            'author_id' => 'Author ID',
            'has_vote' => 'Has Vote',
        ];
    }


}
