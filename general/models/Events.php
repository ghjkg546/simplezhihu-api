<?php
namespace general\models;

/**
 * 当客户端发来消息时触发
 * @param int $client_id 连接id
 * @param mixed $data 具体消息
 */
class Events{
    public static function onMessage($client_id, $data){
        $message = json_decode($data, true);
        $message_type = $message['type'];

        switch($message_type) {
            case 'init':
                // uid
                $uid = $message['id'];
                // 设置session
                $_SESSION = [
                    'username' => $message['username'],
                    'avatar'   => isset($message['avatar'])?$message['avatar']:'',
                    'id'       => $uid,
                    'sign'     => $message['sign'],
                    'type'      => $message_type,
                ];
                // 将当前链接与uid绑定
                Gateway::bindUid($client_id, $uid);
                // 通知当前客户端初始化
                $init_message = array(
                    'message_type' => 'init',
                    'id'           => $uid,
                );
                Gateway::sendToClient($client_id, json_encode($init_message));
                return;
                break;
            case 'chatMessage':
                // 聊天消息
                $type = $message['data']['to']['type'];
                $to_id = $message['data']['to']['id'];
                $uid = $_SESSION['id'];

                $chat_message = [
                    'message_type' => 'chatMessage',
                    'data' => [
                        'username' => $_SESSION['username'],
                        'avatar'   => $_SESSION['avatar'],
                        'id'       => $type === 'friend' ? $uid : $to_id,
                        'type'     => $type,
                        'content'  => htmlspecialchars($message['data']['mine']['content']),
                        'timestamp'=> time()*1000,
                    ]
                ];

                return Gateway::sendToUid($to_id, json_encode($chat_message));
                break;
            case 'hide':
            case 'online':
                $status_message = [
                    'message_type' => $message_type,
                    'id'           => $_SESSION['id'],
                ];
                $_SESSION['online'] = $message_type;
                Gateway::sendToAll(json_encode($status_message));
                return;
                break;
            case 'ping':
                return;
            default:
                echo "unknown message $data" . PHP_EOL;
        }
    }
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
    }
    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public static function onClose($client_id){
        $logout_message = [
            'message_type' => 'logout',
            'id'           => $_SESSION['id']
        ];
        Gateway::sendToAll(json_encode($logout_message));
    }
    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public static function onError($client_id, $code, $msg)
    {
        echo "error $code $msg\n";
    }
    /**
     * 每个进程启动
     * @param $worker
     */
    public static function onWorkerStart($worker)
    {
    }
}
