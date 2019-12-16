<?php

namespace general\models;

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

    /**
     * 生成用户 Token
     * @param $manager
     * @param string $uuid
     * @param string $device
     * @return array|bool
     * @throws \Exception
     */
    public function generateAppTokenInfo($manager, $uuid = '', $device = MessageService::INSTANT_PUSH_DEVICE_TYPE_WX_BUSINESS) {
        if (!is_object($manager)) {
            $manager = Manager::find()->select(['login_name', 'id'])->where(['ops_wx_openid' => $manager])->one();
        }
        if ($manager) {
            $time = time();
            $token_ide = $device . '_' . $manager->id;
            $set_time = empty(Yii::$app->params['TokenTimeOut']) ? 86400 : Yii::$app->params['TokenTimeOut'];
            if (empty($uuid)) {
                $uuid = md5($time . $manager->login_phone);
            }
            $token_array = [
                'uuid' => $uuid,
                'uid' => $manager->id,
                'ip' => EquipmentTool::getClientIP(),
                'time' => $time,
                'store_id' => $manager->store_id,
                'device' => $device
            ];
            $token = md5(json_encode($token_array));
            $token_array['token'] = $token;
            //--保存缓存，每次请求都会带token获取信息
            $token_service = new TokenService();
            //-- 获取token，不存在返回null
            $token_tmp = $token_service->getToken($token_ide);
            $token_service->delToken($token_ide); //--清理token

            Yii::$app->cache->add($token, $token_array, $set_time);
            $token_service->setRedisToken($token_ide, $token); //--重设token
            MemcacheLockTool::releaseLock(MessageService::INSTANT_PUSH_DEVICE_TYPE_WX_BUSINESS . '_' . $manager->id);
            return $token_array;
        } else {
            return false;
        }
    }
}
