<?php
namespace general\models;

use yii\web\IdentityInterface;
use general\models\MemberLogin;

/**
 * 用户身份识别器
 * @author 胡植鹏
 *
 */
class UserIdentity extends MemberLogin implements IdentityInterface
{

    public function validateAuthKey($authKey)
    {
        return $authKey == $this->getAuthKey();
    }

    public static function findIdentity($id)
    {
        return static::findOne(['member_id' => $id]);
    }

    public function getAuthKey()
    {
        return '#@twyyx_yii_';
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['login_name' => $token]);
    }

    public static function findIdentityByWxToken($token, $type = null)
    {
        return static::findOne(['wx_token' => $token]);
    }

    public function getId()
    {
        return $this->member_id;
    }
}

?>