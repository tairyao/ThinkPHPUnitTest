<?php
/**
 * Description: 框架引导程序
 * @author tairyao
 * Date: 2016/6/15 19:22
 */

//error_reporting(0);

// 记录开始运行时间
$GLOBALS['_beginTime'] = microtime(TRUE);
// 记录内存初始使用
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();

// 类文件后缀
const EXT               =   '.class.php';

// 系统常量定义
preg_match("/(.*)\\/Application\\/(.*)\\/Test\\/bootstrap\\.php/", __FILE__, $match);
define('TP_BASEPATH', $match[1].'/');
define('MODULE_NAME', $match[2]);

defined('THINK_PATH')   or define('THINK_PATH',     TP_BASEPATH.'ThinkPHP/');
defined('APP_PATH')     or define('APP_PATH',       TP_BASEPATH.'Application/');
defined('APP_STATUS')   or define('APP_STATUS',     'config'); // 应用状态 加载对应的配置文件
defined('APP_DEBUG')    or define('APP_DEBUG',      false); // 是否调试模式
defined('MODULE_PATH')  or define('MODULE_PATH',    APP_PATH.MODULE_NAME.'/'); // 模块路径
defined('RUNTIME_PATH') or define('RUNTIME_PATH',   APP_PATH.'Runtime/');   // 系统运行时目录
defined('LIB_PATH')     or define('LIB_PATH',       realpath(THINK_PATH.'Library').'/'); // 系统核心类库目录
defined('CORE_PATH')    or define('CORE_PATH',      LIB_PATH.'Think/'); // Think类库目录
defined('BEHAVIOR_PATH')or define('BEHAVIOR_PATH',  LIB_PATH.'Behavior/'); // 行为类库目录
defined('MODE_PATH')    or define('MODE_PATH',      THINK_PATH.'Mode/'); // 系统应用模式目录
defined('VENDOR_PATH')  or define('VENDOR_PATH',    LIB_PATH.'Vendor/'); // 第三方类库目录
defined('COMMON_PATH')  or define('COMMON_PATH',    APP_PATH.'Common/'); // 应用公共目录
defined('CONF_PATH')    or define('CONF_PATH',      COMMON_PATH.'Conf/'); // 应用配置目录
defined('LANG_PATH')    or define('LANG_PATH',      COMMON_PATH.'Lang/'); // 应用语言目录
defined('HTML_PATH')    or define('HTML_PATH',      APP_PATH.'Html/'); // 应用静态目录
defined('LOG_PATH')     or define('LOG_PATH',       RUNTIME_PATH.'Logs/'); // 应用日志目录
defined('TEMP_PATH')    or define('TEMP_PATH',      RUNTIME_PATH.'Temp/'); // 应用缓存目录
defined('DATA_PATH')    or define('DATA_PATH',      RUNTIME_PATH.'Data/'); // 应用数据目录
defined('CACHE_PATH')   or define('CACHE_PATH',     RUNTIME_PATH.'Cache/'); // 应用模板缓存目录
defined('CONF_EXT')     or define('CONF_EXT',       '.php'); // 配置文件后缀
defined('CONF_PARSE')   or define('CONF_PARSE',     '');    // 配置文件解析方法
defined('ADDON_PATH')   or define('ADDON_PATH',     APP_PATH.'Addon');

define('IS_CGI',(0 === strpos(PHP_SAPI,'cgi') || false !== strpos(PHP_SAPI,'fcgi')) ? 1 : 0 );
define('IS_WIN',strstr(PHP_OS, 'WIN') ? 1 : 0 );
define('IS_CLI',PHP_SAPI=='cli'? 1   :   0);

// 加载普通模式的方法和配置
$mode = include THINK_PATH.'Mode/common.php';
foreach ($mode['core'] as $file){
    if(is_file($file)) {
        include $file;      //包括THINK_PATH.'Common/functions.php'，APP_PATH.'Common/Common/function.php'
    }
}
foreach ($mode['config'] as $key=>$file){
    is_numeric($key)?C(load_config($file)):C($key,load_config($file));
}

// 加载配置文件
file_exists(THINK_PATH.'Conf/convention.php') && C(include THINK_PATH.'Conf/convention.php');
file_exists(APP_PATH.'Common/Conf/config.php') && C(include APP_PATH.'Common/Conf/config.php');
file_exists(MODULE_PATH.'Conf/config.php') && C(include MODULE_PATH.'Conf/config.php');

// 加载模块公共方法
file_exists(MODULE_PATH.'Common/function.php') && include_once MODULE_PATH.'Common/function.php';

spl_autoload_register('my_autoload');


/**
 * 类库自动加载
 * @param string $class 对象类名
 * @return void
 */

function my_autoload($class) {
    if (false !== strpos($class, '\\')) {
        $name = strstr($class, '\\', true);
        if (in_array($name, array('Think', 'Org', 'Behavior', 'Com', 'Vendor')) || is_dir(LIB_PATH . $name)) {
            // Library目录下面的命名空间自动定位
            $path = LIB_PATH;
        } else {
            // 检测自定义命名空间 否则就以模块为命名空间
            $namespace = C('AUTOLOAD_NAMESPACE');
            $path = isset($namespace[$name]) ? dirname($namespace[$name]) . '/' : APP_PATH;
        }
        $filename = $path . str_replace('\\', '/', $class) . EXT;
        if (is_file($filename)) {
            // Win环境下面严格区分大小写
            if (IS_WIN && false === strpos(str_replace('/', '\\', realpath($filename)), $class . EXT)) {
                return;
            }
            include $filename;
        }
    } elseif (!C('APP_USE_NAMESPACE')) {
        // 自动加载的类库层
        foreach (explode(',',C('APP_AUTOLOAD_LAYER')) as $layer) {
            if (substr($class, -strlen($layer)) == $layer) {
                if (require_cache(MODULE_PATH . $layer . '/' . $class . EXT)) {
                    return;
                }
            }
        }
        // 根据自动加载路径设置进行尝试搜索
        foreach (explode(',',C('APP_AUTOLOAD_PATH')) as $path) {
            if (import($path . '.' . $class))
                // 如果加载类成功则返回
                return;
        }
    }
}
