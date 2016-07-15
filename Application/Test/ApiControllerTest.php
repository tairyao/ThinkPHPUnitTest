<?php
/**
 * Description: 单元测试类
 * @author tairyao
 * Date: 2016/7/15 17:37
 */

use AspectMock\Test as test;

class ApiControllerTest extends PHPUnit_Framework_TestCase {

    protected $controller;

    protected function setUp() {
        $this->controller = new \Home\Controller\ApiController();
    }

    protected function tearDown() {
        test::clean(); // remove all registered test doubles
    }

    public function testGetAll() {

        //mock一个Model类的替身，设置query方法返回abc
        $model = test::double('\Think\Model', ['query' => 'abc']);
        //无需把替身注入到controller中，AOP机制会在test()方法中自动把Model类替换为替身
        $data = $this->controller->test();
        print_r($data);
        //断言结果为abc
        $this->assertEquals('abc', $data);
        //断言query方法被反射调用了一次
        $model->verifyInvokedOnce('query');
    }

}