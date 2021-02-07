<?php

namespace frontend\controllers;

use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\web\Controller;
use Yii;

/**
 * 文件上传管理控制器
 * @author 胡植鹏
 *
 */
class FileController extends Controller {

    public $savePath = NULL;
    public $saveUrl = NULL;
    private $_fileInstance;
    public $layout=false;
    public $enableCsrfValidation = false;

    public function init() {
        parent::init();
    }

   

    /**
     * 上传图片
     * @return string
     */
    public function actionUploadImg() {
        $this->savePath='uploadfile';
        $this->_fileInstance = UploadedFile::getInstanceByName('img');
        if (!$this->_fileInstance) {
            return Json::encode(['error' => 1, 'message' => '没有上传任何文件']);
        }
        $fileName = uniqid() . '.' . $this->_fileInstance->extension;
        if (strtolower($this->_fileInstance->extension) == 'php') {
            return Json::encode(['error' => 0, 'message' => '上传失败', 'url' => '']);
        }
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $this->_fileInstance->tempName);
        finfo_close($finfo);
        if (stristr($mimetype, 'text/plain') || stristr($mimetype, 'text/x-php')) {
            if (stristr(file_get_contents($this->_fileInstance->tempName), '?php') !== false) {
                return Json::encode(['error' => 0, 'message' => '上传失败', 'url' => '']);
            }
        }
        $flag = $this->_fileInstance->saveAs($this->savePath . DIRECTORY_SEPARATOR . $fileName);
        
        return Json::encode(['error' => $flag ? 0 : 1, 'message' => $flag ? '' : '上传失败', 'url' => 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->savePath . '/' . $fileName,'id'=>1]);
    }

    /**
     * 上传图片
     * @return string
     */
    public function actionLayuiUpload() {
        if (!$this->_fileInstance) {
            $data['code'] = -1;
            $data['msg'] = '没有上传任何文件';
            return Json::encode($data);
        }
        $fileName = uniqid() . '.' . $this->_fileInstance->extension;
        if (strtolower($this->_fileInstance->extension) == 'php') {
            $data['code'] = -1;
            $data['msg'] = '非法格式';
            return Json::encode($data);
        }
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $this->_fileInstance->tempName);
        finfo_close($finfo);
        if (stristr($mimetype, 'text/plain') || stristr($mimetype, 'text/x-php')) {
            if (stristr(file_get_contents($this->_fileInstance->tempName), '?php') !== false) {
                $data['code'] = -1;
                $data['msg'] = '上传失败';
                $data['data'] = [
                    'src' => $this->saveUrl . '/' . $fileName
                ];
                return Json::encode($data);
            }
        }
        $flag = $this->_fileInstance->saveAs($this->savePath . DIRECTORY_SEPARATOR . $fileName);
        if ($flag) {
            $data['code'] = 0;
            $data['msg'] = '上传成功';
            $data['data'] = [
                'src' => $this->saveUrl . '/' . $fileName
            ];
        } else {
            $data['code'] = -1;
            $data['msg'] = '上传失败';
            $data['data'] = [
                'src' => $this->saveUrl . '/' . $fileName
            ];
        }
        return Json::encode($data);
    }

    /**
     * 图片列表
     * @return string
     */
    public function actionList() {
        $path = \Yii::$app->request->get('path', null);
        $current_path = $this->savePath;
        $current_url = $this->saveUrl;

        $moveup_dir_path = '';
        $current_dir_path = '';
        if ($path) {
            $current_path .= DIRECTORY_SEPARATOR . $path;
            $current_url .= '/' . $path;
            $current_dir_path .= $path;
            $moveup_dir_path .= preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }
        $result = [
            'moveup_dir_path' => $moveup_dir_path,
            'current_dir_path' => $current_dir_path,
            'current_url' => $current_url,
            'total_count' => 0,
            'file_list' => []
        ];
        $handle = opendir($current_path);
        if (!$handle) {
            return Json::encode();
        }

        while (($filename = readdir($handle))) {
            if ($filename == '.' || $filename == '..') {
                continue;
            }
            $dir = [];
            $file = $current_path . DIRECTORY_SEPARATOR . $filename;
            if (is_dir($file)) {
                $dir = [
                    'is_dir' => true,
                    'has_file' => count(scandir($file)) > 2,
                    'filesize' => 0,
                    'is_photo' => false,
                    'filetype' => ''
                ];
            } else {
                $dir = [
                    'is_dir' => false,
                    'has_file' => false,
                    'filesize' => filesize($file),
                    'dir_path' => '',
                    'is_photo' => in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['gif', 'jpg', 'jpeg', 'png', 'bmp']),
                    'filetype' => strtolower(pathinfo($file, PATHINFO_EXTENSION))
                ];
            }
            $dir['filename'] = $filename;
            $dir['datetime'] = date('Y-m-d H:i:s', filemtime($file));
            $result['file_list'][] = $dir;
        }

        $result['total_count'] = count($result['file_list']);

        return Json::encode($result);
    }

}

?>