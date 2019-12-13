<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "yii_general_red_packet_list".
 *
 * @property integer $id
 * @property integer $create_time
 */
class RedPacketList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii_general_red_packet_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_time' => 'Create Time',
        ];
    }
}
