<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "bike".
 *
 * @property integer $id
 * @property string $bike_number
 * @property string $latitude
 * @property string $longitude
 */
class Bike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bike';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bike_number', 'latitude', 'longitude'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bike_number' => 'Bike Number',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }
}
