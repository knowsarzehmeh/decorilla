<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../../../../Downloads/yii-1.1.13.e9e4a0 2/framework/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

Yii::createWebApplication($config);
