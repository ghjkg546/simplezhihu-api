<?php

namespace frontend\modules\api\controllers;

use general\models\ZhihuArticle;
use general\models\ZhihuMember;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Default controller for the `User` module
 */
class ArticleController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public $layout = false;
    public $enableCsrfValidation = false;

    public function actionLogin()
    {
        return '{
	"message": "",
	"timestamp": 1585359563227,
	"result": {
		"id": "b686F33e-F996-bcD0-c950-D6b1BCDBB04C",
		"name": "Kevin Thompson",
		"username": "admin",
		"password": "",
		"avatar": "https://gw.alipayobjects.com/zos/rmsportal/jZUIxmJycoymBprLOUbT.png",
		"status": 1,
		"telephone": "",
		"lastLoginIp": "27.154.74.117",
		"lastLoginTime": 1534837621348,
		"creatorId": "admin",
		"createTime": 1497160610259,
		"deleted": 0,
		"roleId": "admin",
		"lang": "zh-CN",
		"token": "4291d7da9005377ec9aec4a71ea837f"
	},
	"code": 0,
	"_status": 200,
	"_headers": {
		"Custom-Header": "d7Eb7eF1-C062-5467-4c8F-66f778e7AB97"
	}
}';

        return Json::encode([
                'code' => 0,
                'result' => ['token' => '4291d7da9005377ec9aec4a71ea837f', 'status' => 1]]
        );
    }


    public function actionDetail()
    {
        $article = ZhihuArticle::findOne(1);
        return Json::encode([
                'code' => 0,
                'result' => ['data' => $article]
            ]
        );
    }

    public function action2stepCode()
    {
        return '{"message":"","timestamp":1585361429856,"result":{"stepCode":1},"code":0}';
        return Json::encode([
                'code' => 200,
                'result' => ['stepCode' => 2]]
        );
    }


    public function actionList()
    {
        $page = \Yii::$app->request->get('pageNo');
        $page_size = \Yii::$app->request->get('pageSize', 10);
        $has_vote = \Yii::$app->request->get('has_vote');
        $keyword = \Yii::$app->request->get('keyword');
        $starttime = \Yii::$app->request->get('starttime');
        $endtime = \Yii::$app->request->get('endtime');
        if (!empty($endtime)) {
            $endtime = strtotime($endtime) + 26 * 3600;
        }
        if (!empty($starttime)) {
            $starttime = strtotime($starttime);
        }
        $bikes_query = ZhihuArticle::find();
        if (!empty($has_vote)) {
            $vote = $has_vote == 1 ? 1 : 0;
            $bikes_query->where(['has_vote' => $vote]);
        }
        $bikes_query->andFilterWhere(['like', 'content', $keyword]);
        $bikes_query->andFilterWhere(['>=', 'create_time', $starttime]);
        $bikes_query->andFilterWhere(['<', 'create_time', $endtime]);
        $copy = clone $bikes_query;
        $bikes = $bikes_query->limit($page_size)->offset(($page - 1) * $page_size)->asArray()->all();
        foreach ($bikes as $k=>$v){
            $bikes[$k]['cover_img'] = !empty($bikes[$k]['cover_img'])?\Yii::$app->request->getHostInfo().$bikes[$k]['cover_img']:'';
        }
        $total_count = $copy->count();

        $total_page = ceil($total_count / $page_size);
        return Json::encode([
                'code' => 0,
                'result' => ['data' => $bikes, 'pageSize' => 10, 'pageNo' => intval($page), 'totalCount' => intval($total_count), 'totalPage' => intval($total_page)]
            ]
        );
    }

}
