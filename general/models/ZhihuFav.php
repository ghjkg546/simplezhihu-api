<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_fav}}".
 *
 * @property integer $id
 * @property integer $answer_id
 * @property string $category_name
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
            [['answer_id'], 'integer'],
            [['category_name'], 'string', 'max' => 500],
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
            'category_name' => 'Category Name',
        ];
    }

    /**
     * @inheritdoc
     * @return ZhihuFavQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ZhihuFavQuery(get_called_class());
    }
}
