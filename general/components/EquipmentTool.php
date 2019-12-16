<?php

namespace general\components;

use Yii;

/**
 * @abstract 获取访客设备工具类
 * @date    2018-4-28 9:38:35
 */
class EquipmentTool {

    /**
     * 获取访客浏览器类型
     * @return string
     */
    public static function getBrowser() {
        $result = '';
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $br = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/MSIE/i', $br)) {
                $result = 'MSIE';
            } elseif (preg_match('/Firefox/i', $br)) {
                $result = 'Firefox';
            } elseif (preg_match('/Chrome/i', $br)) {
                $result = 'Chrome';
            } elseif (preg_match('/Safari/i', $br)) {
                $result = 'Safari';
            } elseif (preg_match('/Opera/i', $br)) {
                $result = 'Opera';
            } else {
                $result = 'Other';
            }
        }
        return $result;
    }

    /**
     * 获取访客浏览器语言  
     * @return string
     */
    public static function getLang() {
        $result = '';
        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $lang = substr($lang, 0, 5);
            if (preg_match("/zh-cn/i", $lang)) {
                $result = "简体中文";
            } elseif (preg_match("/zh/i", $lang)) {
                $result = "繁体中文";
            } else {
                $result = "English";
            }
        }
        return $result;
    }

    /**
     * 获取客户端操作系统信息
     * @return type
     */
    public static function getOs() {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $result = '';
        if (preg_match('/win/i', $agent) && strpos($agent, '95')) {
            $result = 'Windows 95';
        } else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')) {
            $result = 'Windows ME';
        } else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent)) {
            $result = 'Windows 98';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent)) {
            $result = 'Windows Vista';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent)) {
            $result = 'Windows 7';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent)) {
            $result = 'Windows 8';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent)) {
            $result = 'Windows 10';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)) {
            $result = 'Windows XP';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent)) {
            $result = 'Windows 2000';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)) {
            $result = 'Windows NT';
        } else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent)) {
            $result = 'Windows 32';
        } else if (preg_match('/linux/i', $agent)) {
            $result = 'Linux';
        } else if (preg_match('/unix/i', $agent)) {
            $result = 'Unix';
        } else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)) {
            $result = 'SunOS';
        } else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)) {
            $result = 'IBM OS/2';
        } else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)) {
            $result = 'Macintosh';
        } else if (preg_match('/PowerPC/i', $agent)) {
            $result = 'PowerPC';
        } else if (preg_match('/AIX/i', $agent)) {
            $result = 'AIX';
        } else if (preg_match('/HPUX/i', $agent)) {
            $result = 'HPUX';
        } else if (preg_match('/NetBSD/i', $agent)) {
            $result = 'NetBSD';
        } else if (preg_match('/BSD/i', $agent)) {
            $result = 'BSD';
        } else if (preg_match('/OSF1/i', $agent)) {
            $result = 'OSF1';
        } else if (preg_match('/IRIX/i', $agent)) {
            $result = 'IRIX';
        } else if (preg_match('/FreeBSD/i', $agent)) {
            $result = 'FreeBSD';
        } else if (preg_match('/teleport/i', $agent)) {
            $result = 'teleport';
        } else if (preg_match('/flashget/i', $agent)) {
            $result = 'flashget';
        } else if (preg_match('/webzip/i', $agent)) {
            $result = 'webzip';
        } else if (preg_match('/offline/i', $agent)) {
            $result = 'offline';
        }
        return $result;
    }

    /**
     * 获取客户端IP
     * @return string
     */
    public static function getClientIP() {
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "NULL";
        }
        return $ip;
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
     public static function get_client_ip($type = 0,$adv = false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * 获取HTTP报文头
     * @return type
     */
    public static function getHeaders() {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        $headers['request_param'] = "";
        $post = Yii::$app->request->post();

        $request_param = array_merge_recursive($post, $_GET);
        foreach ($request_param as $k => $v) {
            if (is_array($v))
                $v = json_encode($v);
            if ($request_param) {
                $headers['request_param'] .= '&' . $k . '=' . $v;
            } else {
                $headers['request_param'] = $k . '=' . $v;
            }
        }
        $headers['request_param'] = '【请求参数】:' . $headers['request_param'];
        return $headers;
    }

    /**
     * 检测是否是IE
     * @return type
     */
    public static function checkIE() {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $isIE = strpos($_SERVER['HTTP_USER_AGENT'], "Triden");
            return $isIE;
        }
        return false;
    }

    /**
     * 识别支付客户端
     * @return string
     */
    public static function distinguishPaymentClient() {
        //-- 微信
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return "WEIXIN";
        }
        //-- 支付宝
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
            return "ALIPAY";
        }

        return "";
    }

    /**
     * 获取完整URL
     * @param type $is_full_path 是否全路径返回
     * @return type
     */
    public static function getPageUrl($is_full_path = true) {
        $page_url = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') {
            $page_url .= 's';
        }
        $page_url .= '://';


        if ($is_full_path) {
            $page_url .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        } else {
            $page_url .= $_SERVER["HTTP_HOST"];
        }
        return $page_url;
    }

    /**
     * 获取协议
     */
    public static function getAgreement() {
        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
            return 'https://';
        }
        return'http://';
    }

}
