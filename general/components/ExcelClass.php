<?php

/**
 * Created by Abel
 * Author: Abel
 * Date: 2016/4/28
 * Time: 16:49
 */

namespace general\components;

use Support\File\FileHelper;
use yii\web\UploadedFile;

require LIBRARY_PATH . DIRECTORY_SEPARATOR . "Classes" . DIRECTORY_SEPARATOR . "PHPExcel" . DIRECTORY_SEPARATOR . "IOFactory.php";
require LIBRARY_PATH . DIRECTORY_SEPARATOR . "Classes" . DIRECTORY_SEPARATOR . "PHPExcel.php";
require LIBRARY_PATH . DIRECTORY_SEPARATOR . "Classes" . DIRECTORY_SEPARATOR . "PHPExcel" . DIRECTORY_SEPARATOR . "Writer" . DIRECTORY_SEPARATOR . "Excel2007.php";
require LIBRARY_PATH . DIRECTORY_SEPARATOR . "Classes" . DIRECTORY_SEPARATOR . "PHPExcel" . DIRECTORY_SEPARATOR . "PHPExcelReadFilter.php";

class ExcelClass {

    public static $_string = "workSheet";

    public static function PHPExcel() {
        return new \PHPExcel();
    }

    /**
     * @abstract：读取多工作表excel
     * @return mixed|string
     */
    public static function getExcel() {
        $tmp = func_get_args();
        $filename = $tmp[0];

        if (! FileHelper::assertFileType($filename, ['xls', 'xlsx'])) {
            @unlink($filename);
            return '上传文件格式错误 必须是excel文档';
        }
        if (!file_exists($filename)) {
            return "文件不存在!";
        }

        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        $cacheSettings = array();
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $PHPExcel = \PHPExcel_IOFactory::load($filename);
        $count = $PHPExcel->getSheetCount();
        $inData = array();

        for ($i = 0; $i < $count; $i++) {
            $inData[$i] = $PHPExcel->getSheet($i)->toArray();
        }
        $excelData = self::del_empty($inData); //删除空白工作表信息
        return $excelData;
    }

    /**
     * @abstract：删除空sheet
     * @return mixed
     */
    public static function del_empty() {
        $tmp = func_get_args();
        foreach ($tmp[0] as $key => $val) {
            if (count($val) == 1) {
                //删除空sheet
                if (count($val[0][0]) == 0)
                    unset($tmp[0][$key]);
            }else {
                //非空 那么 删除空行
                foreach ($val as $k => $v) {
                    $t = array_unique($v);
                    if (count($t) == 1 && !$t[0]) {
                        unset($tmp[0][$key][$k]);
                    }
                }
            }
        }
        return $tmp[0];
    }

    /**
     *  文件导出
     * @param array $data 数据集
     * @param integer $type 输出类型: 1.文件流 2.本地
     * @param string $file_name
     * @return string
     */
    public static function outputExcel(array $data, $type = 1, $file_name = '') {
        //新建
        $resultPHPExcel = new \PHPExcel();
        $length = count($data[0]);
        $flip = self::field($data[0]);
        $num = 1;
        foreach ($data as $key => $v) {
            for ($i = 0; $i < $length; $i++) {
                $resultPHPExcel->getActiveSheet()->setCellValueExplicit(self::column($i) . $num, $v[$flip[$i]], \PHPExcel_Cell_DataType::TYPE_STRING);
                //$resultPHPExcel->getActiveSheet()->setCellValue(self::column($i) . $num, $v[$flip[$i]]);
            }
            $num++;
        }
        if ($type == 1) {
            self::Perform($resultPHPExcel);
            die;
        } else {
            return self::SaveLocal($resultPHPExcel, $file_name);
        }
    }

    public static function getExcelObject() {
        $resultPHPExcel = new \PHPExcel();
        return $resultPHPExcel;
    }

    /**
     * 批量文件导出
     * @param type $data 数据集
     * @param type $type 输出类型: 1.文件流 2.本地
     */
    public static function outputBatchExcel($resultPHPExcel, $data, $num) {
        //新建
        if (!$resultPHPExcel instanceof \PHPExcel) {
            $resultPHPExcel = new \PHPExcel();
        }
        $length = count($data[0]);
        $flip = self::field($data[0]);
        foreach ($data as $key => $v) {
            for ($i = 0; $i < $length; $i++) {
                $resultPHPExcel->getActiveSheet()->setCellValueExplicit(self::column($i) . $num, $v[$flip[$i]], \PHPExcel_Cell_DataType::TYPE_STRING);
            }
            $num++;
        }
        return $resultPHPExcel;
    }

    /**
     * @abstract：
     * @return array
     */
    public static function field() {
        $tmp = func_get_args();
        $array = array();
        foreach ($tmp[0] as $key => $val) {
            array_push($array, $key);
        }
        return $array;
    }

    /**
     * @abstract：
     * @return mixed
     */
    public static function column() {
        $tmp = func_get_args();
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str_str = str_split($str);
        $first = floor($tmp[0] / 26) - 1;
        $second = $tmp[0] % 26;
        if ($tmp[0] < 26)
            $string = $str_str[$tmp[0]];
        else
            $string = $str_str[$first] . $str_str[$second];
        return $string;
    }


    /**
     * @abstract：执行导出excel
     */
    public static function Perform() {
        $tmp = func_get_args();
        $outputFileName = date("Y-m-d") . '.xls'; //设置导出文件名
//        $xlsWriter = new \PHPExcel_Writer_Excel2007($tmp[0]);
        $xlsWriter = new \PHPExcel_Writer_Excel5($tmp[0]);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outputFileName . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $xlsWriter->save("php://output");
    }

    public static function SaveLocal($resultPHPExcel, $file_name = '') {
        $tmp = func_get_args();
        if (empty($file_name)) {
            $file_name = str_replace('.', '', Tool::getMicroSecond()) . mt_rand(11, 99);
        }
        $dest = '/uploadfile/excel/' . $file_name . '.xls';
        $dest_path = ROOT_PATH . $dest; //设定目录
        //保存文件
        $objWriter = \PHPExcel_IOFactory::createWriter($tmp[0], 'Excel5');
        $objWriter->save($dest_path);

        if (is_file($dest_path)) {
            return $dest;
        }
        return "";
    }

    /**
     * @abstract 上传excel。  参数一：input[type="file"]的name ；参数二：保存的目标路径（结尾不带/）
     * @return array 返回信息和文件名
     */
    public static function uploadExcel() {
        $tmp = func_get_args();
        $upload_name = $tmp[0]; //上传表单的name
        $dir = $tmp[1]; //保存的 路径
        $prefix = isset($tmp[2]) ? $tmp[2] : ''; //文件名前缀
        $upload_file = UploadedFile::getInstanceByName($upload_name); //UploadedFile对象
        if (!$upload_file) {
            return ['error' => 1, 'msg' => '请选择文件', 'name' => ''];
        }
        if (!($upload_file->extension == 'xls' || $upload_file->extension == 'xlsx')) {
            @unlink($upload_file->tempName);
            return ['error' => 1, 'msg' => '上传文件格式错误 必须是excel文档', 'name' => ''];
        }
        $name = $prefix . uniqid() . '.' . $upload_file->extension;
        $status = $upload_file->saveAs($dir . '/' . $name, true);
        if ($status) {
            @unlink($upload_file->tempName);
            return ['error' => 0, 'msg' => '上传文件excel成功', 'name' => $name];
        } else {
            @unlink($upload_file->tempName);
            @unlink($dir . '/' . $name);
            return ['error' => 1, 'msg' => '保存失败', 'name' => ''];
        }
    }


    /**
     * @abstract 读取excel转换成数组（单页/效率高）
     * @param type $excelFile 文件路径
     * @param type $startRow 开始读取的行数
     * @param type $endRow 结束读取的行数
     * @return type
     */
    public static function readExcel($excelFile, $startRow = 1, $endRow = 100) {
        $excelType = \PHPExcel_IOFactory::identify($excelFile);
        $excelReader = \PHPExcel_IOFactory::createReader($excelType);

        if (strtoupper($excelType) == 'CSV') {
            $excelReader->setInputEncoding('GBK');
        }

        if ($startRow && $endRow) {
            $excelFilter = new \PHPExcelReadFilter();
            $excelFilter->startRow = $startRow;
            $excelFilter->endRow = $endRow;
            $excelReader->setReadFilter($excelFilter);
        }
        $phpexcel = $excelReader->load($excelFile);

        $activeSheet = $phpexcel->getActiveSheet();

        $highestColumn = $activeSheet->getHighestColumn(); //最后列数所对应的字母，例如第1行就是A

        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn); //总列数
        $highestColumnIndex = $highestColumnIndex > 100 ? 100 : $highestColumnIndex;//最多只读100列
        $realColumnIndex = 0;//有内容的格子列数
        for ($col = 0; $col < $highestColumnIndex; $col++) {
            $row_value = $activeSheet->getCellByColumnAndRow($col, $startRow)->getValue();
            if (!empty($row_value)) {
                $realColumnIndex += 1;
            }
        }
        $highestColumnIndex = $realColumnIndex;
        $data = array();
        for ($row = $startRow; $row <= $endRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $data[$row][] = (string) $activeSheet->getCellByColumnAndRow($col, $row)->getValue();
            }
            if (implode($data[$row], '') == '') {
                unset($data[$row]);
            }
        }
        return $data;
    }

}
