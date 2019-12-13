<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "yii_general_red_packet".
 *
 * @property integer $id
 * @property string $amount
 * @property integer $member_id
 * @property integer $packet_id
 */
class RedPacket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii_general_red_packet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount'], 'number'],
            [['member_id', 'packet_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amount' => 'Amount',
            'member_id' => 'Member ID',
            'packet_id' => 'Packet ID',
        ];
    }
}
