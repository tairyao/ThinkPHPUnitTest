# ThinkPHP单元测试扩展

## 简介
ThinkPHP的单元测试扩展，下载后可直接运行示例。

## 运行环境
* PHP：仅支持5.4，5.5
* PHPUnit >= 4.8
* AspectMock：[最新版](https://github.com/Codeception/AspectMock)

## 目录说明
* vendor：AspectMock依赖包
* Home/Test:单元测试目录
	* phpunit.xml：phpunit全局设置
	* aspectMockAutoload.php：AspectMock的引导程序
	* bootstrap.php：ThinkPHP框架的引导程序
	* ApiControllerTest.php：单元测试例子

## 使用方式
1. 测试单个文件
<pre><code>
[root@localhost Test]# pwd
/home/tairyao/ThinkPHPUnitTest/Application/Home/Test
[root@localhost Test]# phpunit ApiControllerTest.php
PHPUnit 4.8.9 by Sebastian Bergmann and contributors.

.abc

Time: 381 ms, Memory: 56.25Mb

OK (1 test, 1 assertion)
</code></pre>

2. 测试整个目录
<pre><code>
[root@localhost Home]# pwd
/home/tairyao/ThinkPHPUnitTest/Application/Home
[root@localhost Home]# phpunit -c Test/phpunit.xml Test/
PHPUnit 4.8.9 by Sebastian Bergmann and contributors.

.abc

Time: 458 ms, Memory: 56.50Mb

OK (1 test, 1 assertion)
</code></pre>