<?php

namespace frontend\component;

use general\components\ApiResponse;
use general\components\PageTrait;
use yii\web\Controller;

header("Content-type:text/html;charset=utf-8");

class CController extends Controller {

    use ApiResponse;
    use PageTrait;

    /**
     * 禁止post时csrf验证
     * @var bool
     */
    public $enableCsrfValidation = false;

    public function init() {
        parent::init();
    }



}
