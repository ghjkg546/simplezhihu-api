<?php

namespace general\components;

use general\models\Member;
use general\models\MemberLogin;
use general\models\MemberPartner;
use general\service\MemberService;
use yii;
use yii\web\IdentityInterface;
use yii\web\User;

/**
 * 用户登录类
 * @author 胡植鹏
 *
 */
class UserMember extends User
{
    /**
     * 登录验证
     * @param string $loginName 登录帐号
     * @param string $password 登录密码
     * @return boolean
     */
    public function validate($loginName, $password)
    {
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

    /* (non-PHPdoc)
        * @see \yii\web\User::login($identity, $duration)
        */
    public function login(IdentityInterface $identity = NULL, $duration = 0, $loginName = NULL)
    {
        if ($identity) {
            $this->identity = $identity;
        } else {
            $className = $this->identityClass;
            $this->identity = $className::findIdentityByAccessToken($loginName);
        }
        //print_r($this->identity);die;
        //$cookies = Yii::$app->request->cookies;
        //$uid = $cookies->getValue('uid',0);
        $uid = $this->identity->member_id;
        $this->checkExpire($uid);
        $cookies = Yii::$app->response->cookies;
        setcookie('uid', $uid, time() + 3600 * 24 * 365, '/');
        $this->checkExpire($uid);
        $cookies->add(new \yii\web\Cookie([
            'name' => 'loginName',
            'value' => $loginName,
            'expire' => time() + 3600 * 24 * 365
        ]));
//         print_r($this->identity);die;
        return parent::login($this->identity, $duration);
    }

    public function getName()
    {
        return $this->identity->member->nickname;
    }

    public function getLoginname()
    {
        return $this->identity->login_name;
    }

    public function getScore()
    {
        return $this->identity->member->total_points - $this->identity->member->froze_points;
    }

    public function getMember()
    {
        return $this->identity->member;
    }

    public function getDiscount()
    {
        if (isset($this->identity->member->profit_discount)) {
            return $this->identity->member->profit_discount;
        }
    }

    /* (non-PHPdoc)
        * @see \yii\web\User::logout($destroySession)
        */
    public function logout($destroySession = true)
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('uid');
        $cookies->remove('loginName');
        $cookies->remove('pid');
        $cookies->remove('sid');
        $cookies->remove('partner_expire');
        return parent::logout($destroySession);

    }

    /* (non-PHPdoc)
        * @see \yii\web\User::getId()
        */
    public function getId()
    {
        $url = Yii::$app->request->getUrl();
        if (strpos($url, '/wx/') !== false || strpos($url, '/pay-weixinsppay') !== false) {
            $header = Yii::$app->request->headers;
            $token = $header->get('token');
            if (!empty($token)) {
                $data = MemberService::parseToken($token);
                if (!empty($data)) {
                    $uid = $data->getClaim('uid');
                    return $uid;
                }
                return null;
            }
            return null;
        }//小程序uid返回

        if (parent::getIsGuest()) {
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
        }
        return parent::getId();
    }

    /*
     * 让视图可以直接获取用户所属sid店铺id
     */
    public function getStoreid()
    {
        $url = Yii::$app->request->getUrl();
        if (strpos($url, '/wx/') !== false || strpos($url, '/pay-weixinsppay') !== false) {
            $header = Yii::$app->request->headers;
            $token = $header->get('token');
            if (!empty($token)) {
                $data = MemberService::parseToken($token, 'sid');
                if (!empty($data)) {
                    $sid = $data;
                    return $sid;
                }
                return null;
            }//return null;
        }//小程序sid返回

        if (parent::getIsGuest()) {
            if (isset($_COOKIE['sid'])) {
                $store_id = $_COOKIE['sid'];
            } else {
                $store_id = Yii::$app->system->info->default_store;
            }
        } else {
            $store_id = $this->identity->member->store_id;
        }
        return $store_id;
    }

    /*
     * 让视图可以直接获取当前店铺id cookies中的
     */
    public function getCookiesstoreid()
    {
        /*$store_id = Yii::$app->system->info->default_store;
        if(isset($_COOKIE['sid'])) {
            $store_id = $_COOKIE['sid'];
        }*/
        if (Yii::$app->user->isGuest) {
            if (isset($_COOKIE['sid']) == '') {
                return 1;
            } else {
                return $_COOKIE['sid'];
            }
        } else {
            $member = Member::find()->where(['id' => Yii::$app->user->id])->one();
            if (isset($_COOKIE['sid'])) {
                $store_id = $member->store_id;
            }
            return $store_id;
        }
    }

    //判断用户是否是新用户
    public function isNewreg()
    {
        //1代表是老用户 2代表是新用户
        $member = Member::find()->where(['id' => $this->id])->one();
        if ($member) {
            if ((time() - $member->regdate) > (86400 * 3)) {
                return 1;
            } else {
                return 2;
            }
        }

    }

    //判断是否为体验期内快过期的超级会员并设置提醒标识
    public function checkExpire($uid)
    {
        $now = time();
        $partner = MemberPartner::find()
//        ->leftJoin(Member::tableName() . " `member`", MemberPartner::tableName() . ".`member_id`=`member`.`id`")
            ->where(['and',
                ['member_id' => $uid],
//            ['is_partner'=>1],
                ['<=', 'start_time', $now],
                ['>', 'end_time', $now],
                ['<>', 'start_time', 0],
                ['<>', 'end_time', 0]
            ])->one();
        if ($partner) {
            $start_time = $partner->start_time;
            $end_time = $partner->end_time;
            $remaining_day = ceil(($end_time - $now) / 3600 / 24);
            if ($remaining_day <= 5) {
                if (!isset($_COOKIE['partner_expire'])) {
                    setcookie("partner_expire", $remaining_day, $end_time, '/');
                    return;
                }

            }
        }
        setcookie("partner_expire", 0, time() - 3600);
    }


}

?>