<?php

namespace frontend\modules\api\controllers;

use frontend\tool\EsTool;
use general\models\Products;
use general\models\ZhihuFavCategory;
use general\models\ZhihuMember;
use yii\base\Exception;
use yii\helpers\Json;
use yii\web\Controller;
use Elasticsearch\ClientBuilder;

require 'vendor/autoload.php';
/**
 * Default controller for the `User` module
 */
class ProductController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public $layout=false;
    public $enableCsrfValidation = false;


    public function actionIndex(){
        $page= \Yii::$app->request->get('page');
        $page_size=\Yii::$app->request->get('pageSize',10);
        $has_vote=\Yii::$app->request->get('has_vote');
        $keyword=\Yii::$app->request->get('keyword');
        $bikes_query= Products::find();
        if(!empty($keyword)){
            $es =new EsTool();
            $res = $es->searchByName($keyword);
            $total_page = ceil($res['total']/$page_size);
            $bikes = $bikes_query->andWhere(['_id'=>$res['_ids']])->asArray()->all();
            return Json::encode([
                    'code'=>0,
                    'result'=>['data'=>$bikes,'pageSize'=>10,'pageNo'=>intval($page),'totalCount'=>intval($res['total']),'totalPage'=>intval($total_page)]
                ]
            );
            print_r($res);

        }

        $copy = clone $bikes_query;
        $bikes = $bikes_query->limit($page_size)->offset(($page - 1) * $page_size)->asArray()->all();
        $total_count= $copy->count();

        $total_page = ceil($total_count/$page_size);
        return Json::encode([
                'code'=>0,
                'result'=>['data'=>$bikes,'pageSize'=>10,'pageNo'=>intval($page),'totalCount'=>intval($total_count),'totalPage'=>intval($total_page)]
            ]
        );
    }

    public function actionGetCategory(){
        $cates= ZhihuFavCategory::find()->orderBy('parent_id')->asArray()->all();
        $result = [];
        return Json::encode([
                'code'=>0,
                'result'=>['data'=>$this->getTree1($cates)]
            ]
        );
    }

    public function actionAddCategory(){

        $name= \Yii::$app->request->get('category_name');
        $parent_id=\Yii::$app->request->get('parent_id',0);
        $cate = new ZhihuFavCategory();
        $cate->category_name = '新分类';
        $cate->parent_id = $parent_id;
        $cate->save();

        $cates= ZhihuFavCategory::find()->orderBy('parent_id')->asArray()->all();
        $result = [];
        return Json::encode([
                'code'=>0,
                'result'=>['data'=>$this->getTree1($cates),'id'=>(string)$cate->primaryKey]
            ]
        );
    }

    public function actionDelCategory(){

        $id= \Yii::$app->request->get('id');
        ZhihuFavCategory::deleteAll(['id'=>$id]);
        ZhihuFavCategory::deleteAll(['parent_id'=>$id]);

    }

    public function actionSaveCategory(){
        $post = file_get_contents('php://input');
        $post = Json::decode($post);
        $cate = ZhihuFavCategory::findOne(['id'=>$post['id']]);
        $cate->category_name = $post['title'];
        $a=$cate->save();

        $cates= ZhihuFavCategory::find()->orderBy('parent_id')->asArray()->all();
        return Json::encode([
                'code'=>0,
                'result'=>['data'=>$this->getTree1($cates)]
            ]
        );
    }

    function getTree1($data, $parent_id = 0)
    {
        $tree = array();
        foreach ($data as $k => $v) {
            if ($v["parent_id"] == $parent_id) {
                unset($data[$k]);
                if (!empty($data)) {
                    $children = $this->getTree1($data, $v["id"]);
                    if (!empty($children)) {
                        $v["item"] = $children;
                    }
                }
                $tree[] = $v;
            }
        }
        return $tree;
    }


    public function actionIntoEs(){
        $client = ClientBuilder::create()->build();
        $lists= Products::find()->asArray()->all();
        set_time_limit(0);
            foreach ($lists as $row) {

                $params['body'][] = [
                    'index' => [
                        '_index' => 'product_zhihu1',
                        '_type'  => 'product_type_zhihu',
                        '_id' => $row['_id'],
                    ]
                ];

                $params['body'][] = [
                    'general_name' => $row['general_name'],
                    'first_pinyin' => $row['first_pinyin'],
                    'product_name' => $row['product_name'],
                    'produce_unit' => $row['produce_unit']
                ];

            }
            $a=$client->bulk($params);
            print_r($a);
        echo 'success';

    }

    public function actionTunc(){
        $es = new EsTool();
        $es->truncateTable('product_zhihu1');
    }

    public function actionEsQuery(){
        try{
            $es = new EsTool();
            print_r($es->searchByName('板蓝颗'));
        }catch (\Exception $e){
            echo $e->getMessage();
        }

    }

    //删除文档
    public function actionEsDelete()
    {
        $es = new EsTool();
        $es->delete_document('product_zhihu1','product_type_zhihu','3607c57481a8b379f032559e15397c12');
    }
    //更改文档
    public function update_document()
    {
        $updateParams = array();
        $updateParams['index'] = 'myindex';
        $updateParams['type'] = 'mytype';
        $updateParams['id'] = 'my_id';
        $updateParams['body']['doc']['product_name']  = '新商品名';
        $response = $this->client->update($updateParams);

    }
    //查询
    public function search()
    {
        $searchParams['index'] = 'myindex';
        $searchParams['type'] = 'mytype';
        $searchParams['from'] = 0;
        $searchParams['size'] = 100;
        $searchParams['sort'] = array(
            '_score' => array(
                'order' => 'id'
            )
        );
        //相当于sql语句：  select * from hp_product where prodcut_name like '茶'  limit 0,100 order by id desc;
        $searchParams['body']['query']['match']['product_name'] = '茶';
        $retDoc = $this->client->search($searchParams);

        echo '<pre>';
        print_r($retDoc);
    }


    public function actionEsCreateType(){
        $client = ClientBuilder::create()->build();

        try{
            $params = [
                'index' => 'product_zhihu1', // 索引名
                'include_type_name' => 'true', // 索引名
                'body' => [
                    'settings' => [ // 分片和副本数
                        'number_of_shards' => 3, // 分片
                        'number_of_replicas' => 0 // 副本，如果只有一台机器，设置为0
                    ],
                    'mappings' => [ // 映射
                        'product_type_zhihu' => [ // 类型
                            '_source' => [
                                'enabled' => true // 开启即可，否则某些功能不可用
                            ],
                            'properties' => [ // 指定字段的类型或字段属性
                                'product_name' => [ // 字段
                                    'type' => 'keyword', // 数据类型
                                ],
                                'general_name' => [ // 字段
                                    'type' => 'keyword', // 数据类型
                                ],
                                'first_pinyin' => [ // 字段
                                    'type' => 'keyword', // 数据类型
                                ],
                                'produce_unit' => [ // 字段
                                    'type' => 'keyword', // 数据类型
                                ],
                            ]
                        ]
                    ]
                ]
            ];

            $response = $client->indices()->create($params);
            print_r($response);
        } catch (\Exception $e){
            echo $e->getMessage();exit;
        }

    }



    public function actionSave(){
        $post = file_get_contents('php://input');
        $post = Json::decode($post);
        ZhihuMember::updateAll(['username'=>$post['username'],'brief'=>$post['brief']],['id'=>$post['id']]);
        return Json::encode(['code'=>0]);
    }

}
