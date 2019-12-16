<?php

namespace frontend\models;

use yii\web\IdentityInterface;

class ManagerIdentity  implements IdentityInterface {

    public function validateAuthKey($authKey) {
        return $authKey == $this->getAuthKey();
    }

    public static function findIdentity($id) {
        return self::findOne(['id' => $id]);
    }

    public function getAuthKey() {
        return '#@twyyx_yii_';
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return self::findOne(['login_name' => $token]);
    }

    //预设账户
    public static function findIdentityByPresetAccount($token, $type = null) {
        return self::findOne(['preset_account' => $token]);
    }

    //登录手机号
    public static function findIdentityByLoginPhone($token, $type = null) {
        return self::findOne(['login_phone' => $token]);
    }

    //微信Unionid
    public static function findIdentityByWxUnionid($wx_unionid, $type = null) {
        return self::findOne(['login_wx_unionid' => $wx_unionid]);
    }

    public function getId() {
        return $this->id;
    }

}

?>