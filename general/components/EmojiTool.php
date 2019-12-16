<?php

namespace general\components;


/**
 * emoji工具类
 * Class EmojiTool
 * @package general\components
 */
class EmojiTool
{

    /**
     * 转义emoji
     * @param $str
     * @return string
     */
    public static function emoji_encode($str) {
        if(!is_string($str)){
            return $str;
        }

        if(!$str || $str =='undefined'){
            return '';
        }

        $text = json_encode($str);

        $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function ($str){
            return addslashes($str[0]);
        },$text);
        return json_decode($text);
    }

    /**
     * 过滤掉emoji表情
     * @param $str
     * @return mixed
     */
    public static function filterEmoji($str)
    {
        $str = static::emoji_encode($str);
        $str = preg_replace_callback('/(\\\u[ed][0-9a-f]{3})/i', function (array $match) {
            return '';
        }, $str);
        return $str;
    }

}
