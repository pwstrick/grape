<?php
/**
 * 常量定义
 * @author: pwstrick
 */
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('LIB_PATH', ROOT_PATH . '/library');
define('LIB_THIRD_PATH', LIB_PATH . '/third');//第三方类库目录
define('LIB_XHPROF_PATH', LIB_THIRD_PATH . '/xhprof');//性能测试工具
define('SCRIPT_PATH', 'scripts');//开发使用scripts，部署使用optimize
define('UPLOAD_PATH', ROOT_PATH.'/upload');//上传路径
define('UPLOAD_HTTP', 'http://upload.grape.net');

/**
 * API客户端密钥
 */
define('API_IOS_KEY', 'DF_A98=D^&7$^%*9CNAhj0UO!!LM11');
define('API_ANDROID_KEY', 'KU98&)dsf8%@kji89dfadJK-800i122');
define('API_UNKNOW_KEY', 'jfdsfd798hj+@(*kb66578-223q670933');

/**
 * 微信配置
 */
define('WEIXIN_APPID', 'xx');//TODO
define('WEIXIN_SECRET', 'xx');//TODO
define('WEIXIN_TOKEN', 'xx');//TODO
define('WEIXIN_NOTIFY_URL', 'http://xx');//TODO
define('WEIXIN_MCHID', 'xx');//TODO
define('WEIXIN_KEY', 'xx');//TODO

header("Content-Type:text/html; charset=utf-8");   
require_once(ROOT_PATH . '/initphp/initphp.php'); //导入配置文件-必须载入
require_once(ROOT_PATH . '/conf/comm.conf.php'); //公用配置
