<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_fav}}".
 *
 * @property integer $id
 * @property integer $answer_id
 * @property integer $category_id
 * @property integer $user_id
 */
class ZhihuFav extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_fav}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answer_id', 'category_id', 'user_id'], 'integer'],
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
            'category_id' => 'Category ID',
            'user_id' => 'User ID',
        ];
    }
}
