<?php
define('__BAMBOO__',true);
/** 根目录 */
define('__ROOT__',substr(str_replace('\\','/',dirname(__FILE__)),0,-6));

require_once __ROOT__.'/vendor/PHPBamboo/application/application.php';

$application = new application();
$application ->run();
?>