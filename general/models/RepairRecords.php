<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "repair_records".
 *
 * @property integer $id
 * @property string $title
 * @property string $pic_url
 * @property string $bike_number
 * @property string $remarks
 */
class RepairRecords extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'repair_records';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'pic_url', 'bike_number'], 'string', 'max' => 200],
            [['remarks'], 'string', 'max' => 255],
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
            'img_url' => 'Img Url',
            'bike_number' => 'Bike Number',
            'remarks' => 'Remarks',
        ];
    }
}
