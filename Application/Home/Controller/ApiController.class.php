<?php
/**
 * Description:
 * @author tairyao
 * Date: 2016/7/15 17:28
 */

namespace Home\Controller;
use Think\Controller;
class ApiController extends Controller {

    public function getAll() {
        $connect = M('', '', 'testDB');
        $sql = "select * from test";
        $result = $connect->query($sql);

        return $result;
    }
}