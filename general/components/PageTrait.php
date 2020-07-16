<?php
namespace general\components;

use yii\db\Query;
use yii\helpers\Json;

trait PageTrait
{

    public function page(Query $obj){
        $data = file_get_contents('php://input');
        $data = Json::decode($data);
        $page_size = isset($data['pageSize'])?$data['pageSize']:5;
        $page = isset($data['page'])?$data['page']:1;
        $obj->offset(($page-1)*$page_size)->limit($page_size);
        return $obj;
    }
}