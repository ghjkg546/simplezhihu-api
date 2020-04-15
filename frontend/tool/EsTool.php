<?php

namespace frontend\tool;
use Elasticsearch\ClientBuilder;

require 'vendor/autoload.php';

/**
 * ContactForm is the model behind the contact form.
 */
class EsTool
{
    public $client =  null;

    function __construct()
    {
        $this->client = ClientBuilder::create()->build();
    }

    //删除文档
    public function delete_document($index,$type,$id)
    {
        $deleteParams = array();
        $deleteParams['index'] = $index;
        $deleteParams['type'] = $type;
        $deleteParams['id'] = $id;
        $retDelete = $this->client->delete($deleteParams);
        print_r($retDelete);
    }

    //清空表
    public function truncateTable($table){
        $params = ['index' => $table];
        $response = $this->client->indices()->delete($params);
        print_r($response);
    }

    public function searchByName($name){
        $client = ClientBuilder::create()->build();

        $params = [
            'index' => 'product_zhihu1',
            'type' => 'product_type_zhihu',
        ];
        //$params['body']['query']['wildcard']['general_name'] = '*一品康牌钙加维*';
        $json ='{
    "bool" : {
      "must" : [{"wildcard" : { "general_name" : "*'.$name.'*" }}]
    
  }}';

        $params['body']['query']=json_decode($json,1);
        $page= \Yii::$app->request->get('page');
        $page_size=\Yii::$app->request->get('pageSize',10);
        $params['body']['from']=($page - 1) * $page_size;
        $params['size']['size']=$page_size;

        $res = $client->search($params);
        return ['total'=>$res['hits']['total']['value'],'_ids'=>array_column($res['hits']['hits'],'_id')];
    }


}
