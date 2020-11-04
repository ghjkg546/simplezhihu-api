<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_article}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $question_id
 * @property string $content
 * @property integer $create_time
 * @property integer $up_count
 * @property integer $author_id
 * @property integer $has_vote
 * @property integer $audit_status
 * @property string $cover_img
 */
class ZhihuArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'create_time', 'up_count', 'author_id', 'has_vote', 'audit_status'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['cover_img'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'question_id' => 'Question ID',
            'content' => 'Content',
            'create_time' => 'Create Time',
            'up_count' => 'Up Count',
            'author_id' => 'Author ID',
            'has_vote' => 'Has Vote',
            'audit_status' => 'Audit Status',
            'cover_img' => 'Cover Img',
        ];
    }
}
