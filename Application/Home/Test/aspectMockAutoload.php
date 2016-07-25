<?php
/**
 * Description: 加载AspectMock测试框架
 * @author tairyao
 * Date: 2016/6/29 20:44
 */

preg_match("/(.*)\\/Application\\/.*/", __DIR__, $match);
$tpPath = $match[1];
include $tpPath.'/vendor/autoload.php';     // composer autoload

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
//    'includePaths' => [__DIR__],
]);

//这里要用绝对路径，否则在不同目录下执行phpunit，获取的相对路径都不同
$kernel->loadFile(__DIR__.'/bootstrap.php');