<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_fav_category}}".
 *
 * @property integer $id
 * @property string $category_name
 * @property integer $user_id
 */
class ZhihuFavCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_fav_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
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
            'category_name' => 'Category Name',
            'user_id' => 'User ID',
        ];
    }
}
