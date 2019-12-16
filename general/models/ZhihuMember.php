<?php

namespace general\models;

use Yii;

/**
 * This is the model class for table "{{%zhihu_member}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $money
 * @property string $avatar
 * @property string $brief
 */
class ZhihuMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zhihu_member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['username', 'password', 'money', 'avatar', 'brief'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'money' => 'Money',
            'avatar' => 'Avatar',
            'brief' => 'Brief',
        ];
    }
}
