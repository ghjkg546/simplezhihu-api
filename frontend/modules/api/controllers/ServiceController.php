<?php

namespace frontend\modules\api\controllers;

use backend\models\ZhihuAnswer;
use general\models\Bike;
use general\models\Member;
use general\models\ZhihuQuestion;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `User` module
 */
class ServiceController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public $layout=false;
    public $enableCsrfValidation = false;

    public function actionIndex(){
        $page= \Yii::$app->request->get('pageNo');
        $page_size=\Yii::$app->request->get('pageSize');
        $has_vote=\Yii::$app->request->get('has_vote');
        $keyword=\Yii::$app->request->get('keyword');
        $starttime=\Yii::$app->request->get('starttime');
        $endtime=\Yii::$app->request->get('endtime');
        if(!empty($endtime)){
            $endtime= strtotime($endtime)+26*3600;
        }
        if(!empty($starttime)){
            $starttime = strtotime($starttime);
        }
        $bikes_query= ZhihuAnswer::find();
        if(!empty($has_vote)){
            $vote = $has_vote == 1? 1:0;
            $bikes_query->where(['has_vote'=>$vote]);
        }
        $bikes_query->andFilterWhere(['like','content',$keyword]);
        $bikes_query->andFilterWhere(['>=','create_time',$starttime]);
        $bikes_query->andFilterWhere(['<','create_time',$endtime]);
        $copy = clone $bikes_query;
         $bikes = $bikes_query->limit($page_size)->offset(($page - 1) * $page_size)->asArray()->all();
        foreach ($bikes as $k=>$v){
            $bikes[$k]['create_time'] = date('Y-m-d H:i',$v['create_time']);
        }
        $total_count= $copy->count();

        $total_page = ceil($total_count/$page_size);
        return Json::encode([
                'code'=>0,
                'result'=>['data'=>$bikes,'pageSize'=>10,'pageNo'=>intval($page),'totalCount'=>intval($total_count),'totalPage'=>intval($total_page)]
            ]
        );
        return '{"message":"","timestamp":1585363150913,"result":{"pageSize":10,"pageNo":1,"totalCount":100,"totalPage":10,"data":[{"key":1,"id":1,"no":"No 1","description":"这是一段描述","callNo":993,"status":0,"updatedAt":"1980-10-01 06:16:51","editable":false},{"key":2,"id":2,"no":"No 2","description":"这是一段描述","callNo":38,"status":2,"updatedAt":"2006-12-08 12:37:32","editable":false},{"key":3,"id":3,"no":"No 3","description":"这是一段描述","callNo":356,"status":0,"updatedAt":"2003-01-25 11:15:02","editable":false},{"key":4,"id":4,"no":"No 4","description":"这是一段描述","callNo":401,"status":3,"updatedAt":"2019-06-19 08:25:33","editable":false},{"key":5,"id":5,"no":"No 5","description":"这是一段描述","callNo":702,"status":3,"updatedAt":"1991-11-23 22:38:09","editable":false},{"key":6,"id":6,"no":"No 6","description":"这是一段描述","callNo":907,"status":2,"updatedAt":"1996-06-23 15:38:55","editable":false},{"key":7,"id":7,"no":"No 7","description":"这是一段描述","callNo":525,"status":1,"updatedAt":"1997-08-14 08:30:27","editable":false},{"key":8,"id":8,"no":"No 8","description":"这是一段描述","callNo":174,"status":3,"updatedAt":"1988-04-23 17:35:59","editable":false},{"key":9,"id":9,"no":"No 9","description":"这是一段描述","callNo":33,"status":1,"updatedAt":"2009-07-23 21:12:18","editable":false},{"key":10,"id":10,"no":"No 10","description":"这是一段描述","callNo":744,"status":1,"updatedAt":"1989-04-24 00:23:33","editable":false}]},"code":0}';
    }

    public function actionQuestionList(){
        $page= \Yii::$app->request->get('pageNo');
        $page_size=\Yii::$app->request->get('pageSize');
        $has_vote=\Yii::$app->request->get('has_vote');
        $keyword=\Yii::$app->request->get('keyword');
        $starttime=\Yii::$app->request->get('starttime');
        $endtime=\Yii::$app->request->get('endtime');
        if(!empty($endtime)){
            $endtime= strtotime($endtime)+26*3600;
        }
        if(!empty($starttime)){
            $starttime = strtotime($starttime);
        }
        $bikes_query= ZhihuQuestion::find();
        if(!empty($has_vote)){
            $vote = $has_vote == 1? 1:0;
            $bikes_query->where(['has_vote'=>$vote]);
        }
        $bikes_query->andFilterWhere(['like','content',$keyword]);
        $bikes_query->andFilterWhere(['>=','create_time',$starttime]);
        $bikes_query->andFilterWhere(['<','create_time',$endtime]);
        $copy = clone $bikes_query;
        $bikes = $bikes_query->limit($page_size)->offset(($page - 1) * $page_size)->asArray()->all();
        $authors = Member::find()->select(['username'])->indexBy('id')->column();
        foreach ($bikes as $k=>$v){
            $bikes[$k]['author_name'] = $authors[$v['author_id']];
            $bikes[$k]['create_time'] = date('Y-m-d H:i',$v['create_time']);
        }
        $total_count= $copy->count();

        $total_page = ceil($total_count/$page_size);
        return Json::encode([
                'code'=>0,
                'result'=>['data'=>$bikes,'pageSize'=>10,'pageNo'=>intval($page),'totalCount'=>intval($total_count),'totalPage'=>intval($total_page)]
            ]
        );
    }

    public function actionSave(){
        $post = file_get_contents('php://input');
        $post = Json::decode($post);
        ZhihuAnswer::updateAll(['content'=>$post['content']],['id'=>$post['id']]);
        return Json::encode(['code'=>0]);
    }

    public function actionAudit(){
        $post = file_get_contents('php://input');
        $post = Json::decode($post);
        ZhihuAnswer::updateAll(['audit_status'=>1],['id'=>$post['id']]);
        return Json::encode(['code'=>0]);
    }

    public function actionUpload(){
        $param['file_instance'] = UploadedFile::getInstanceByName('file');
        $file_instance = $param['file_instance'];
        if (empty($file_instance)) {
            return ['code' => 0, 'text' => '请上传图片'];
        }
        if ($file_instance->size > 200 * 1024) {
            return ['code' => 0, 'text' => '图片大小最大为200kb'];
        }
        if (empty(@getimagesize($file_instance->tempName))) {
            return ['code' => 0, 'text' => '请上传正确的图片格式'];
        }
        $save_path = 'uploadfile/store_logo';
        if (!is_dir($save_path)) {
            mkdir($save_path, 07777, true);
        }
        $ext = $file_instance->getExtension();
        $uniname = uniqid();
        $full_path = $save_path . "/" . $uniname . "." . $ext;
        $res = $file_instance->saveAs($full_path);
        if (empty($res)) {
            return ['code' => 0, 'text' => '上传失败'];
        }
        return Json::encode(['code'=>0,'data' => ['url' => '/' . $full_path]]);
    }


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
            'code'=>0,
            'result'=>['token'=>'4291d7da9005377ec9aec4a71ea837f','status'=>1]]
        );
    }

    public function actionInfo(){
        return '{
	"message": "",
	"timestamp": 1585362439763,
	"result": {
		"id": "4291d7da9005377ec9aec4a71ea837f",
		"name": "天野远子",
		"username": "admin",
		"password": "",
		"avatar": "/avatar2.jpg",
		"status": 1,
		"telephone": "",
		"lastLoginIp": "27.154.74.117",
		"lastLoginTime": 1534837621348,
		"creatorId": "admin",
		"createTime": 1497160610259,
		"merchantCode": "TLif2btpzg079h15bk",
		"deleted": 0,
		"roleId": "admin",
		"role": {
			"id": "admin",
			"name": "管理员",
			"describe": "拥有所有权限",
			"status": 1,
			"creatorId": "system",
			"createTime": 1497160610259,
			"deleted": 0,
			"permissions": [{
				"roleId": "admin",
				"permissionId": "dashboard",
				"permissionName": "仪表盘",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"query\",\"defaultCheck\":false,\"describe\":\"查询\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "query",
					"describe": "查询",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "exception",
				"permissionName": "异常页面权限",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"query\",\"defaultCheck\":false,\"describe\":\"查询\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "query",
					"describe": "查询",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "result",
				"permissionName": "结果权限",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"query\",\"defaultCheck\":false,\"describe\":\"查询\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "query",
					"describe": "查询",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "profile",
				"permissionName": "详细页权限",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"query\",\"defaultCheck\":false,\"describe\":\"查询\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "query",
					"describe": "查询",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "table",
				"permissionName": "表格权限",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"import\",\"defaultCheck\":false,\"describe\":\"导入\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "import",
					"describe": "导入",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "form",
				"permissionName": "表单权限",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"query\",\"defaultCheck\":false,\"describe\":\"查询\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "query",
					"describe": "查询",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "order",
				"permissionName": "订单管理",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"query\",\"defaultCheck\":false,\"describe\":\"查询\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "query",
					"describe": "查询",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "permission",
				"permissionName": "权限管理",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "role",
				"permissionName": "角色管理",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "table",
				"permissionName": "桌子管理",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"query\",\"defaultCheck\":false,\"describe\":\"查询\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "query",
					"describe": "查询",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "user",
				"permissionName": "用户管理",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"import\",\"defaultCheck\":false,\"describe\":\"导入\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"},{\"action\":\"export\",\"defaultCheck\":false,\"describe\":\"导出\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "import",
					"describe": "导入",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}, {
					"action": "export",
					"describe": "导出",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}, {
				"roleId": "admin",
				"permissionId": "support",
				"permissionName": "超级模块",
				"actions": "[{\"action\":\"add\",\"defaultCheck\":false,\"describe\":\"新增\"},{\"action\":\"import\",\"defaultCheck\":false,\"describe\":\"导入\"},{\"action\":\"get\",\"defaultCheck\":false,\"describe\":\"详情\"},{\"action\":\"update\",\"defaultCheck\":false,\"describe\":\"修改\"},{\"action\":\"delete\",\"defaultCheck\":false,\"describe\":\"删除\"},{\"action\":\"export\",\"defaultCheck\":false,\"describe\":\"导出\"}]",
				"actionEntitySet": [{
					"action": "add",
					"describe": "新增",
					"defaultCheck": false
				}, {
					"action": "import",
					"describe": "导入",
					"defaultCheck": false
				}, {
					"action": "get",
					"describe": "详情",
					"defaultCheck": false
				}, {
					"action": "update",
					"describe": "修改",
					"defaultCheck": false
				}, {
					"action": "delete",
					"describe": "删除",
					"defaultCheck": false
				}, {
					"action": "export",
					"describe": "导出",
					"defaultCheck": false
				}],
				"actionList": null,
				"dataAccess": null
			}]
		}
	},
	"code": 0
}';
    }

    public function action2stepCode(){
        return '{"message":"","timestamp":1585361429856,"result":{"stepCode":1},"code":0}';
        return Json::encode([
                'code'=>200,
                'result'=>['stepCode'=>2]]
        );
    }

    public function actionLogout(){
        return '{"message":"[测试接口] 注销成功","timestamp":1585361501144,"result":{},"code":0}';
    }

}
