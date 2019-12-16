<?php

namespace frontend\models;

use yii;
use yii\web\User;
use yii\web\IdentityInterface;
use backend\models\ManagerRole;
use general\models\Store;
use general\models\DataRestriction;

class ManagerValidator extends User {

    /**
     * 登录验证
     * @param string $loginName 登录帐号
     * @param string $password 登录密码
     * @param type $is_md5_password 是否原始MD5密码
     * @return boolean
     */
    public function validate($loginName, $password, $is_md5_password = false) {
        $className = $this->identityClass;

        //-- 检测是否是预设账户
        if (substr($loginName, 0, 1) == '#') {
            $identity = $className::findIdentityByPresetAccount($loginName);
        } elseif (is_numeric($loginName) && strlen($loginName) == 11) {
            $identity = $className::findIdentityByLoginPhone($loginName);
        } else {
            $identity = $className::findIdentityByAccessToken($loginName);
        }

        if (!$identity) {
            return false;
        }

        $password = $is_md5_password ? $password : md5($password);
        if ($identity->password != $password || !$identity->state) {
            return false;
        }

        $this->identity = $identity;
        return true;
    }

    public function validate_preset_account($preset_account) {
        $className = $this->identityClass;
        $identity = $className::findIdentityByPresetAccount($preset_account);

        if (!$identity) {
            return false;
        }

        $this->identity = $identity;
        return true;
    }

    public function validate_wx_unionid($wx_unionid) {
        $className = $this->identityClass;
        $identity = $className::findIdentityByWxUnionid($wx_unionid);

        if (!$identity) {
            return false;
        }

        $this->identity = $identity;
        return true;
    }

    public function login(IdentityInterface $identity = NULL, $duration = 0) {
        if ($identity) {
            $this->identity = $identity;
        }
        return parent::login($this->identity, $duration);
    }

    public function getMenu() {
        return $this->identity->auth->menu_ids;
    }

    public function getName() {
        return $this->identity->login_name;
    }

    public function getNickname() {
        return $this->identity->nickname;
    }

    public function getPreset_account() {
        return $this->identity->preset_account;
    }

    public function getRolename() {
        $role = ManagerRole::find()->where(["manager_id" => $this->identity->id])->with('roleOne')->one();
        return $role->roleOne->title;
    }

    public function getRole() {
        $role_id = 0;
        if (!empty(Yii::$app->session['role_id'])) {
            $role_id = Yii::$app->session['role_id'];
        } else {
            if (!isset($this->identity)) {
                return DataRestriction::LEVEL_GUEST_MANAGEMENT;
            }
            $role = ManagerRole::find()->where(["manager_id" => $this->identity->id])->with('roleOne')->one();
            $role_id = !empty($role->roleOne->id) ? $role->roleOne->id : DataRestriction::LEVEL_GUEST_MANAGEMENT;

            Yii::$app->session['role_id'] = $role_id;
        }
        return $role_id;
    }

    public function getStore() {
        return $this->identity->store_id;
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

    /**
     * 用户所属店铺实体
     * @return type
     */
    public function getStore_info() {
        $store_info = null;
        if (!empty(Yii::$app->session['store_info'])) {
            $store_info = Yii::$app->session['store_info'];
        } else {
            if ($this->getStore_id() != -1) {
                $store_info = Store::find()->where(['id' => $this->identity->store_id])->one();
            }
            Yii::$app->session['store_info'] = $store_info;
        }
        return $store_info;
    }

}
