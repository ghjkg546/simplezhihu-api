<?php

namespace general\components;

use Support\ArrayToXml;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use Yii;

/**
 * @abstract 工具帮助类
 * @date    2017-9-2 8:37:58
 */
class JwtTool {

    private static $_instance;

    public static function getInstance() {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

    /**
     * 生成token
     * @param $data
     * @return mixed
     */
    public static function getToken($data)
    {
        $token1 = Yii::$app->jwt->getBuilder()->setIssuer('jztw.com')// Configures the issuer (iss claim)
        ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
        //->setNotBefore(time() + 60) // Configures the time before which the token cannot be accepted (nbf claim)
        ->setExpiration(time() + 3600 * 3); // 过期时间
        foreach ($data as $k => $v) {
            $token1->set($k, $v);
        }
        $token = $token1->getToken();
        return (string)$token;
    }


    /**
     * 解密token
     * @param $token
     * @param $field 字段，如传入sid,返回店铺id
     * @return mixed
     */
    public static function parseToken($token, $field = 'uid')
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

    }

    /**
     * 获取当前用户ID
     * @return mixed|null
     */
    public static function getUserId(){
        $header = Yii::$app->request->headers;
        $token = $header->get('token');
        if (!empty($token)) {
            $data = JwtTool::parseToken($token,'uid');
            if (!empty($data)) {
                return $data;
            }
            return null;
        }
        return null;
    }


}
