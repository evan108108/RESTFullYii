<?php
// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../../../../../vendor/yiisoft/yii/framework/yiit.php';
$config=dirname(__FILE__).'/testConfig.php';

defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yiit);

Yii::createWebApplication($config);
Yii::app()->setControllerPath(dirname(__FILE__).'/MockObjs/controllers');
