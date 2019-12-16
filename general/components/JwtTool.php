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

    public static function getUserId(){
        $header = Yii::$app->request->headers;
        $token = $header->get('token');
        if (!empty($token)) {
            $data = JwtTool::parseToken($token);
            if (!empty($data)) {
                $uid = $data->getClaim('uid');
                return $uid;
            }
            return null;
        }
        return null;
    }

    private static $_instance;

    public static function getInstance() {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

    /**
     * 数组转换为字符串类型
     * @param mixed $param
     * @return string
     */
    public static function ConveToString($param) {
        if (!empty($param)) {
            if (is_array($param)) {
                return implode(",", $param);
            } else {
                return $param;
            }
        }
        return "";
    }

    /**
     * 字符串转换为数组类型
     * @param string $param
     * @return array
     */
    public static function ConveToArray($param) {
        if (!empty($param)) {
            if (!is_array($param)) {
                return explode(",", $param);
            } else {
                return $param;
            }
        }
        return array();
    }

    /**
     * 转换成带双引号的字符串
     * @param array $param
     * @return string
     */
    public static function ConveToQuatationString($param) {
        if (empty($param)) {
            return '';
        }

        if (is_array($param)) {
            return implode(',', array_map(function ($str) {
                        return sprintf("'%s'", $str);
                    }, $param));
        }

        return $param;
    }

    /**
     * 转换成带双引号的数组
     * @param type $param
     * @return type
     */
    public static function ConveToQuatationArray($param) {
        if (!empty($param)) {
            if (is_array($param)) {
                return array_map(function ($str) {
                    return sprintf("'%s'", $str);
                }, $param);
            }
        }
        return $param;
    }

    /**
     * 转换多维数组合并成
     * @param type $param
     * @return type
     */
    public static function ConveToArrayMerging($param) {
        if (!empty($param) && is_array($param)) {
            //一维数组
            if (count($param) == count($param, 1)) {
                return call_user_func_array('array_merge', $param);
            }
        }
        return $param;
    }

    /**
     * 转换为Json类型
     * @param array $data 数据
     * @param int $state 状态 1：成功 0：失败
     * @param string $text 提示文字
     * @return string
     */
    public static function ConveToJson($data, $state = 1, $text = "") {
        $result = array(
            'state' => $state,
            'text' => $text,
            'data' => $data
        );
        return Json::encode($result);
    }

    /**
     * 转换为XML类型
     * @param array $data 数据
     * @param type $rootElement XML根
     * @param type $replaceSpacesByUnderScoresInKeyNames
     * @param type $xmlEncoding XML编码
     * @param type $xmlVersion XML版本
     * @param array $domProperties
     * @return type
     */
    public static function ConveToXml(array $data, $rootElement = '', $replaceSpacesByUnderScoresInKeyNames = true, $xmlEncoding = null, $xmlVersion = '1.0', array $domProperties = []
    ) {
        return ArrayToXml::convert($data, $rootElement, $replaceSpacesByUnderScoresInKeyNames, $xmlEncoding, $xmlVersion, $domProperties);
    }

    /**
     * 转换Null为空字符串
     * @param array|string $param
     * @return array|string
     */
    public static function CovertToEmptyString($param) {
        if (is_array($param)) {
            foreach ($param as $k => $v) {
                $param[$k] = $v === null ? '' : $v;
            }
            return $param;
        } else {
            return $param === null ? '' : $param;
        }
    }

    /**
     * 转换XML为数组
     * @param type $xml
     * @return type
     */
    public static function CovertXmlToArray($xml) {
        libxml_disable_entity_loader(true);        //禁止引用外部xml实体
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }

    /**
     * 转换为数字数组
     * @param type $xml
     * @return type
     */
    public static function CovertToIntToArray($param) {
        if (is_array($param)) {
            foreach ($param as $k => $v) {
                $param[$k] = intval($v);
            }
            return $param;
        }
        return $param;
    }

    /**
     * 转换为数值索引
     * @param type $param
     * @return type
     */
    public static function CovertNumericIndex($param) {
        $i = 0;
        foreach ($param as $key => $value) {
            $result[$i] = $value;
            $i++;
        }
        return $result;
    }

    /**
     *  过滤空格
     * @param mixed $ids 数据集
     * @return mixed
     */
    public static function FilterSpace($ids) {
        if (!empty($ids)) {
            if (!is_array($ids)) {
                return trim($ids);
            } else {
                return array_map(array(__CLASS__, "FilterSpace"), $ids);
            }
        }
        return null;
    }

    /*
     * 过滤双引号 单引号
     * @param $data 数据集
     * @return array|mixed|null
     */

    public static function FilterQuotation($data) {
        if (!empty($data)) {
            if (!is_array($data)) {
                $data = str_replace('\'', '', $data);
                $data = str_replace('"', '', $data);
                return $data;
            } else {
                return array_map(array(__CLASS__, "FilterQuotation"), $data);
            }
        }
        return null;
    }

    /**
     * 过滤数组NULL为空字符
     * @param type $arr 数据集
     * @return type
     */
    public static function FilterArrayNull($param) {
        foreach ($param as $k => $v) {
            if (is_null($v)) {
                $param [$k] = '';
            }
            if (is_array($v)) {
                $param[$k] = self::FilterArrayNull($v);
            }
        }
        return $param;
    }

    /**
     * 过滤数组指定KEY
     * @param type $param 数据集
     * @param type $keys 过滤的KEY数据集
     * @return type
     */
    public function FilterArrayKey($param, $keys) {
        if (!empty($keys)) {
            foreach ($keys as $key) {
                unset($param[$key]);
            }
        }
        return $param;
    }

    /**
     * 获取中文字符长度
     * @param type $string 字符串
     * @return type
     */
    public static function getChineseStrLen($string = "") {
        $match = array();
        preg_match_all("/./us", $string, $match); // 将字符串分解为单元
        if (!empty($match)) {
            return count($match[0]);     // 返回单元个数
        }
        return 0;
    }

    /**
     * 获取微秒时间戳
     * @return type
     */
    public static function getMicroSecond() {
        return (string) microtime(true);
    }

    /**
     * 获取随机字符
     * @param type $length 字符长度
     * @param type $is_number 是否纯数字
     * @return string
     */
    public static function getRandomCharacter($length = 6, $is_number = false, $is_string = false) {
// 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        if ($is_number) {
            $chars = '0123456789';
        }
        if ($is_string) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $password;
    }

    /**
     * 生成 .TXT 文件
     * @param type $file_name 文件名
     * @param type $content 内容
     */
    public static function generateTxtFile($file_name, $content) {
//-- 中文名处理
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $filename = $file_name . '.txt';
        $encoded_filename = str_replace("+", "%20", urlencode($filename));
        echo $content;
        header("Content-Type: application/octet-stream");
        if (preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Disposition:  attachment; filename="' . $encoded_filename . '"');
        } elseif (preg_match("/Firefox/", $_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Disposition: attachment; filename*="utf8' . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
    }

    /**
     * 时间转换
     * @param type $secs 秒数
     * @return string
     */
    public static function secsToStr($secs) {
        $r = '';
        if ($secs >= 86400) {
            $days = floor($secs / 86400);
            $secs = $secs % 86400;
            $r = $days . '天';
        }
        if ($secs >= 3600) {
            $hours = floor($secs / 3600);
            $secs = $secs % 3600;
            $r .= $hours . '小时';
        }
        if ($secs >= 60) {
            $minutes = floor($secs / 60);
            $secs = $secs % 60;
            $r .= $minutes . '分钟';
            if ($secs > 0) {
                $r .= $secs . '秒';
            }
        }
        return $r;
    }

    /**
     * 计算偏移
     * @param type $start_date
     * @param type $offset
     * @return int
     */
    public function getendday($start_date = null, $offset = 0) {
        $start_date ? null : $start_date = time();
        $date = $start_date;
//即时到账
        if ($offset > 0) {
            for ($i = 0; $i < $offset; $i++) {
                $date += 86400;
            }
        }
        return $date;
    }

    /**
     * 获取已经过了多久
     * PHP时间转换
     * 刚刚、几分钟前、几小时前
     * 今天昨天前天几天前
     * @param  string $targetTime 时间戳
     * @return string
     */
    public static function get_last_time($targetTime) {
// 今天最大时间
        $todayLast = strtotime(date('Y-m-d 23:59:59'));
//$agoTimeTrue = time() - $targetTime;
        $agoTime = $todayLast - $targetTime;
        $agoDay = floor($agoTime / 86400);

        /* if ($agoTimeTrue < 60) {
          $result = '刚刚';
          } elseif ($agoTimeTrue < 3600) {
          $result = (ceil($agoTimeTrue / 60)) . '分钟前';
          } elseif ($agoTimeTrue < 3600 * 12) {
          $result = (ceil($agoTimeTrue / 3600)) . '小时前';
          } elseif */
        if ($agoDay == 0) {
            $result = '今天 ' . date('H:i', $targetTime);
        } elseif ($agoDay == 1) {
            $result = '昨天 ' . date('H:i', $targetTime);
        } elseif ($agoDay == 2) {
            $result = '前天 ' . date('H:i', $targetTime);
        } elseif ($agoDay > 2 && $agoDay <= 30) {
            $result = $agoDay . '天前 ' . date('H:i', $targetTime);
        } else {
            $format = date('Y') != date('Y', $targetTime) ? "m-d H:i" : "m-d H:i";
            $result = date($format, $targetTime);
        }
        return $result;
    }

    /**
     * 检查Json转换是否成功
     * @param string $param 需要转换Json的字符串
     * @return bool  True:转换成功,False:转换失败
     */
    public static function checkJsonConvert($param) {
        json_decode($param);
        return json_last_error() == JSON_ERROR_NONE;
    }

    /**
     * 对比版本号
     * @param string $version1 新版本号
     * @param string $version2 当前版本号
     * @return boolean true:需要更新 false：不需要更新
     */
    public static function compareVersion($version1, $version2) {
        $v1 = explode('.', $version1);
        $v2 = explode('.', $version2);

        $len = count($v1) > count($v2) ? count($v1) : count($v2);

        for ($i = 0; $i < $len; $i++) {
            $n1 = !empty($v1[$i]) ? $v1[$i] : 0;
            $n2 = !empty($v2[$i]) ? $v2[$i] : 0;

            if ($n1 > $n2) {
                return true;
            } else if ($n1 < $n2) {
                return false;
            }
        }
        return false;
    }

    /**
     * 转义 MySQL 中的通配符
     *
     * @author zhengmingyang
     * @param string $content
     * @param array|null $escape
     * @return string
     */
    public static function escapeMySQLWildcards($content, $escape = null) {
        $content = strval($content);
        $escape = is_array($escape) ? $escape : ['%' => '\%', '_' => '\_', '\\' => '\\\\'];

        return strtr($content, $escape);
    }

    /**
     * 转换字符串为整型
     * @param $data
     * @return array|int
     */
    public static function transformStrToInt($data) {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = intval($v);
            }
        } else {
            $data = (int) $data;
        }

        return $data;
    }

    /**
     * 获取所有Get和Post参数
     * @param null $name 指定参数名
     * @return array
     */
    public static function getRequestParams($name = null) {
        $request = \Yii::$app->request;
        $data = ArrayHelper::merge($request->getBodyParams(), $request->getQueryParams());
        return isset($data[$name]) ? $data[$name] : $data;
    }

    /**
     * 截取小数点（非四舍五入法）
     * @param $number
     * @param int $accuracy 精确
     * @return float
     */
    public static function interceptDecimalPoint($number, $accuracy = 2) {
//-- 补充小数位
        $covering = explode('.', $number);
        if (!isset($covering[1]) || strlen($covering[1]) < 2) {
            $number = sprintf("%1\$.2f", $number);
        }

        $pos = stripos($number, '.');
        return floatval(substr($number, 0, $pos + ($accuracy + 1)));
    }

//-- 编码转换
    public static function utf8ToGbk($name) {
        return iconv('utf-8', 'gb18030', $name);
    }

    /**
     * 加密字符
     * @param type $string 需加密的字符串
     * @param type $start_position 加密字符起始位置
     * @param type $original_num 保留原字符串位数
     * @return type
     */
    public static function encryptedCharacter($string, $start_position, $original_num) {
        if (empty($string)) {
            return $string;
        }
        $length = mb_strlen($string, 'utf8') - $start_position - $original_num;
        $str = str_repeat("*", $length); //替换字符数量
        return substr_replace($string, $str, $start_position, $length);
    }

    /**
     *  获取开始或结束时间，用于请求参数时间转换
     * @param $time
     * @param string $type
     *               START   获取开始时间，没有则默认为0
     *               END     获取结束时间，没有则默认为当前时间
     * @return int
     */
    public static function getTime($time, $type = 'START') {
        $time = intval(trim($time));
        switch ($type) {
            case 'START' :
                $time = $time ? $time : 0;
                break;
            case 'END' :
                $time = $time ? $time : time();
                break;
            default :
                throw new \RuntimeException("unknown type: {$type}");
        }
        return $time;
    }

}
