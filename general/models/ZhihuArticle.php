<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "zhihu_answer".
 *
 * @property integer $id
 * @property string $content
 * @property integer $create_time
 * @property integer $question_id
 * @property integer $up_count
 * @property integer $author_id
 * @property integer $has_vote
 */
class ZhihuArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zhihu_article';
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
            'question_id' => 'question_id',
            'content' => 'Content',
            'create_time' => 'Create Time',
            'up_count' => 'Up Count',
            'author_id' => 'Author ID',
            'has_vote' => 'Has Vote',
        ];
    }


}
