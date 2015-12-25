<?php

class apiController extends Controller {
	protected $controller_name;
	protected $action_name;
	protected $module_name;
	private $log_visit_id = '';//与返回值log做关联
	private $member = array();//登录用户信息
	
	public function __construct() {
		parent::__construct();
		
		$this->controller_name = $this->getC();
		$this->action_name = $this->getA();
		$this->module_name = $this->getM();
		
		//访问参数日志记录
		$this->_logVisit();

		//API统一接口为post提交
		if (!$this->controller->is_post()) {
			$this->outputCom(constHelper::API_STATUS_NEED_POST, '必须为POST请求');
		}
		
		/**
		 * 请求参数示例
		 * __ua=Android 4.4.4//MI 3W//26//2.0//865645022129866//////WIFI&
		 * __timestamp=1441854121116&
		 * mobile=13800138000&
		 * __version=2.0&
		 * __device=android&type=2&
		 * __key=67543fd413ce4d281fc93306597acb66
		 */
		$device = $this->p('__device'); //客户端名称
		$timestamp = $this->p('__timestamp'); //客户端时间戳
		$key = $this->p('__key'); //客户端加密指纹
		$session = $this->p('__session'); //客户端SESSION
		$version = substr($this->p('__version'), 0, 5); //客户端版本
		
		//客户端最低版本号要求
		if (strnatcmp($version, constHelper::MIN_VERSION_ALLOWED) < 0) { //客户端需要升级
			$this->outputCom(constHelper::API_STATUS_NEED_UPGRADE, '客户端需要升级');
		}
		
		//检查密钥
		$this->_checkSecretKey($device, $timestamp, $key);
		
		//判断是否需要验证登录
		$needCheck = $this->_checkLogin();
		if($needCheck && empty($session)) {
			$this->outputCom(constHelper::API_STATUS_NEED_LOGIN, '请先登录');
		}
		
		//根据session获取用户信息
		if(!empty($session)){
			$memberModel = InitPHP::getMysqlDao('member');
        	$this->member = $memberModel->getMemberBySession($session);
        }
        
        //session没有获取到相关信息 也要做跳转
        if($needCheck && empty($this->member)) {
        	$this->outputCom(constHelper::API_STATUS_NEED_LOGIN, '请先登录');
        }
	}
	
	/**
	 * 获取当前页码
	 * @return number
	 */
	protected function pageNo() {
		$p = (int)$this->p('p');
		return $p > 0 ? $p : 1;
	}
	
	/**
	 * 获取当前登录用户信息
	 * @param type $key
	 * @return string | array | null
	 */
	protected function m($key = null) {
		if (!$this->member) {
			return null;
		}
		if ($key && is_string($key) && isset($this->member[$key])) {
			return $this->member[$key];
		}
		if ($key == null) {
			return $this->member;
		}
		return null;
	}
	
	/**
	 * 检查密钥，密钥的生成就是时间戳+密钥然后md5加密一下
	 * @return bool true为成功
	 */
	private function _checkSecretKey($device, $timestamp, $key) {
		//提取私有密钥
		$privateKey = '';
		switch (strtolower($device)) {
			case 'ios':
				$privateKey = API_IOS_KEY;
				break;
			case 'android':
				$privateKey = API_ANDROID_KEY;
				break;
			default:
				$privateKey = API_UNKNOW_KEY;
				break;
		}
		
		$keySource = $privateKey . $timestamp;
		//客户端安全认证失效
		if (empty($key) || ($key != md5($keySource))) {
			$this->outputCom(constHelper::API_STATUS_AUTH_FAIL, '安全认证失败');
		}
	}
	
	/**
	 * 检查当前请求是否需要登录
	 * @return boolean true：需要验证
	 */
	private function _checkLogin() {
		$enum = enumHelper::$no_login_api;
		if(!isset($enum[$this->controller_name])) {
			return true;
		}
		return !in_array($this->action_name, $enum[$this->controller_name]);
	}
	
	/**
	 * 记录访问日志
	 * @author pwstrick
	 */
	private function _logVisit() {
		$ua = $this->p('__ua');
		$visitMongo = InitPHP::getMongoDao('visit', 'mongo/bi');
		$sput = array(
			'create_time' => time(),
			'controller_version' => $this->controller_name,
			'controller' => $this->controller_name,
			'action' => $this->action_name,
			'get' => $_GET,//get参数
			'post' => $_POST//post参数
		);
		$visit = $visitMongo->logVisit($sput, $ua);
		
		if(!empty($visit)) {
			$this->log_visit_id = (string)$visit['_id'];
		}
	}
	
	/**
	 * 参数名
	 * @param string $key
	 */
	protected function p($key, $isfilter=true, $type=null) {
		return $this->controller->get_gp($key, $type, $isfilter);
	}
	
	/**
	 * 客户端请求返回参数
	 * @param int code
	 * @param string $message
	 * @param array $data
	 */
	protected function outputCom($code, $message='', $data=array()) {
		$json = array('code'=>$code, 'msg'=>$message, 'data'=>$data);
		
		//将返回参数保存到日志中
		$visitMongo = InitPHP::getMongoDao('visit', 'mongo/bi');
		$visitMongo->updateReturn($json, $this->log_visit_id);
		
		header("Content-type: application/json; charset=utf-8");
		if($this->p('__debug')) {//传递debug参数 显示带换行的格式
			print_r($json);
		}else {
			echo json_encode($json);
		}
		exit();
		//$this->controller->json_return($json);
	}
	
	/**
	 * 请求成功后返回的参数【密钥登录等判断验证通过】
	 * @param int $result
	 * @param string $message
	 * @param array $data
	 */
	protected function output($result, $message='', $data=array()) {
		//这里的result代表的是每个页面中的状态码 obj为额外参数
		$json = array('result'=>$result, 'obj'=>$data);
		$this->outputCom(constHelper::API_STATUS_SUCCESS, $message, $json);
	}
}