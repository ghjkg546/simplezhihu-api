<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $money
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zhihu_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money'], 'integer'],
            [['username', 'password'], 'string', 'max' => 200],
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
        ];
    }

    /**
     * 解密token
     * @param $token
     * @param $field 字段，如传入sid,返回店铺id
     * @return mixed
     */
    public static function parseToken($token, $field = null)
    {
        try {
            if (!is_string($token)) {
                return 0;//防报错
            }
            $data = explode('.', $token);
            if (count($data) != 3) {
                return 0;//防报错
            }
            $token = Yii::$app->jwt->getParser()->parse((string)$token); // Parses from a string
            if (empty($field)) {
                return $token;
            }
            return $token->getClaim($field);
        } catch (Exception $e) {
            return 0;
        }

        /*echo $token->getClaim('iss'); // will print "http://example.com"
        echo $token->getClaim('uid');*/ // will print "1"
    }
}
