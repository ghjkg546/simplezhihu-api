<?php

namespace backend\component;

use backend\models\Member;
use yii;
use yii\web\User;
use yii\web\IdentityInterface;

/**
 * 用户登录类
 * @author 胡植鹏
 *
 */
class UserMember extends User {

    /**
     * 登录验证
     * @param type $loginName 登录帐号
     * @param type $password 登录密码
     * @return boolean
     */
    public function validate($loginName, $password) {
        $className = $this->identityClass;
        $identity = $className::findIdentityByAccessToken($loginName);

        if (!$identity) {
            return false;
        }

        if ($identity->password != md5($password)) {
            return false;
        }

        if ($identity->member->state == 0) {//用户被禁用
            return false;
        }

        $this->identity = $identity;
        return true;
    }

    public function login(IdentityInterface $identity = NULL, $duration = 0, $loginName = NULL) {
        if ($identity) {
            $this->identity = $identity;
        } else {
            $className = $this->identityClass;
            $this->identity = $className::findIdentityByAccessToken($loginName);
        }

        return parent::login($this->identity, $duration);
    }

    public function getName() {
        return $this->identity->member->nickname;
    }

    public function getLoginname() {
        return $this->identity->login_name;
    }

    public function getMember() {
        return $this->identity->member;
    }

    public function getId() {
        $header = Yii::$app->request->headers;
        $token = $header->get('token');
        if (!empty($token)) {
            $data = Member::parseToken($token);
            if (!empty($data)) {
                $uid = $data->getClaim('uid');
                return $uid;
            }
            return null;
        }
        return null;


        /*if (parent::getIsGuest()) {
            $cookies = Yii::$app->request->cookies;
            $uid = $cookies->getValue('uid', 0);
            if (!$uid) {
                if (isset($_COOKIE['uid'])) {
                    $uid = $_COOKIE['uid'];
                }
            }
            $name = $cookies->getValue('loginName', null);
            if ($uid && $name) {
                $login = MemberLogin::find()->where(['member_id' => $uid, 'login_name' => $name])->one();
                if ($login) {
                    Yii::$app->user->login(null, 0, $name);
                }
            }
        }*/
        return parent::getId();
    }

    public function getRole() {
        if (Yii::$app->user->isGuest) {
            return DataRestriction::LEVEL_GUEST_MANAGEMENT;
        }
        return DataRestriction::LEVEL_APP_MANAGEMENT;
    }

    /**
     * 用户所属店铺
     * @return type
     */
    public function getStore_id() {
        return Yii::$app->session->get('store_id', -1);
    }

    /**
     * 用户所属父店铺ID
     * @return type
     */
    public function getParent_store_id() {
        return Yii::$app->session->get('parent_store_id', -1);
    }

}
