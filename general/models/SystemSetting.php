<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "system_setting".
 *
 * @property string $website_name
 * @property string $tel
 * @property string $address
 * @property string $desc
 * @property string $email
 * @property string $working_time
 */
class SystemSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['website_name'], 'string', 'max' => 100],
            [['tel', 'address'], 'string', 'max' => 200],
            [['email', 'working_time'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'website_name' => 'Website Name',
            'tel' => 'Tel',
            'address' => 'Address',
            'desc' => 'Desc',
            'email' => 'Email',
            'working_time' => 'Working Time',
        ];
    }
}
