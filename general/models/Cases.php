<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "cases".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $create_time
 * @property integer $cate_id
 * @property string $img_url
 * @property string $product
 * @property string $industry
 * @property string $client_name
 */
class Cases extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cases';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'create_time'], 'required'],
            [['content'], 'string'],
            [['create_time', 'cate_id'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['img_url'], 'string', 'max' => 200],
            [['product', 'industry'], 'string', 'max' => 50],
            [['client_name'], 'string', 'max' => 100],
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
            'content' => 'Content',
            'create_time' => 'Create Time',
            'cate_id' => 'Cate ID',
            'img_url' => 'Img Url',
            'product' => 'Product',
            'industry' => 'Industry',
            'client_name' => 'Client Name',
        ];
    }
}
