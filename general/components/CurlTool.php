<?php

namespace general\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;

class CurlTool extends Component {

    /**
     * 发送POST请求
     * @param string $url URL
     * @param array $params 参数数据
     * @return string
     */
    public static function post($url, $params = array(), $time_out = 600, $useCert = []) {
        $result = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, $time_out); //设置curl允许执行的最长秒数
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //在发起连接前等待的时间，如果设置为0，则不等待。

        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if ($useCert) {
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $useCert['apiclient_cert']);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $useCert['apiclient_key']);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        if (curl_errno($ch)) {
            curl_close($ch);
            return $result;
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 发送GET请求
     * @param string $url URL
     * @param array $params 参数数据
     * @return string
     */
    public static function get($url, $params = []) {
        $result = "";
        $ch = curl_init();
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, 600); //设置curl允许执行的最长秒数
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //在发起连接前等待的时间，如果设置为0，则不等待。

        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (curl_errno($ch)) {
            curl_close($ch);
            return $result;
        }
        $return = curl_exec($ch);

        curl_close($ch);
        return $return;
    }

    /**
     * 接口专用POST请求
     * @param type $url URL
     * @param type $param 参数数据
     * @return type
     */
    public static function InterfacePost($url, $param = "") {
        $result['state'] = 1; //成功状态
        $result['msg'] = '成功'; //说明
        $result['http_code'] = 200; //HTTP状态码
        $result['data'] = array(); //响应数据

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, 600); //设置curl允许执行的最长秒数
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //在发起连接前等待的时间，如果设置为0，则不等待。

        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param, '', '&'));

        if (curl_errno($ch)) {
            $result['state'] = 0;
            $result['msg'] = curl_error($ch);
            curl_close($ch);
            return $result;
        }
        $result['data'] = curl_exec($ch);
        $result['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $result;
    }

    /**
     * 接口专用GET请求
     * @param type $url URL
     * @param type $params 参数数据
     * @return int
     */
    public static function InterfaceGet($url, $param = "") {
        $result['state'] = 1; //成功状态
        $result['msg'] = '成功'; //说明
        $result['http_code'] = 200; //HTTP状态码
        $result['data'] = array(); //响应数据
        $url .= !empty($param) ? '?' . http_build_query($param) : '';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, 600); //设置curl允许执行的最长秒数
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //在发起连接前等待的时间，如果设置为0，则不等待。

        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //单位 秒
        curl_setopt($ch, CURLOPT_HEADER, 0);

        if (curl_errno($ch)) {
            $result['state'] = 0;
            $result['msg'] = curl_error($ch);
            curl_close($ch);
            return $result;
        }

        $result['data'] = curl_exec($ch);
        $result['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $result;
    }

}
