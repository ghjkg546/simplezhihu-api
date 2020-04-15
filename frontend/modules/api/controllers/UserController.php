<?php

namespace frontend\modules\api\controllers;

use general\models\ZhihuMember;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Default controller for the `User` module
 */
class UserController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public $layout=false;
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

    public function actionList(){
        $page= \Yii::$app->request->get('pageNo');
        $page_size=\Yii::$app->request->get('pageSize',10);
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
        $bikes_query= ZhihuMember::find();
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
            $bikes[$k]['avatar'] = '/'.$v['avatar'];
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
        ZhihuMember::updateAll(['username'=>$post['username'],'brief'=>$post['brief']],['id'=>$post['id']]);
        return Json::encode(['code'=>0]);
    }

}
