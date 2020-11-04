<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_article_like}}".
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $user_id
 */
class ZhihuArticleLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_article_like}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'user_id' => 'User ID',
        ];
    }
}
