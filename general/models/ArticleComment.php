<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%article_comment}}".
 *
 * @property integer $id
 * @property string $content
 * @property integer $author_id
 * @property integer $create_time
 * @property integer $vote_count
 * @property integer $article_id
 */
class ArticleComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id', 'create_time', 'vote_count', 'article_id'], 'integer'],
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
            'content' => 'Content',
            'author_id' => 'Author ID',
            'create_time' => 'Create Time',
            'vote_count' => 'Vote Count',
            'article_id' => 'Article ID',
        ];
    }


    public function getAuthor()
    {
        return $this->hasOne(Member::className(), ['id' => 'author_id']);
    }
}
