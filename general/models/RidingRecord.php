<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "riding_record".
 *
 * @property string $id
 * @property integer $bike_id
 * @property integer $user_id
 * @property integer $end_time
 * @property integer $start_time
 * @property string $total_price
 * @property string $start_long
 * @property string $start_lati
 * @property string $end_long
 * @property string $end_lati
 * @property integer $create_time
 * @property integer $update_time
 */
class RidingRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'riding_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bike_id', 'user_id', 'end_time', 'start_time', 'total_price', 'start_long', 'start_lati', 'end_long', 'end_lati', 'create_time', 'update_time'], 'required'],
            [['bike_id', 'user_id', 'end_time', 'start_time', 'create_time', 'update_time'], 'integer'],
            [['total_price'], 'number'],
            [['start_long', 'start_lati', 'end_long', 'end_lati'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bike_id' => 'Bike ID',
            'user_id' => 'User ID',
            'end_time' => 'End Time',
            'start_time' => 'Start Time',
            'total_price' => 'Total Price',
            'start_long' => 'Start Long',
            'start_lati' => 'Start Lati',
            'end_long' => 'End Long',
            'end_lati' => 'End Lati',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
