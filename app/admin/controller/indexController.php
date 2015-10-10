<?php
/**
 * 后台公共页
 * @author pwstrick
 *
 */
class indexController extends adminController {

	/**
	 * action列表
	 */
	public $initphp_list = array('hello', 'cache', 'mongo', 'rpc', 'helper', 'unittest');
	
	/**
	 * 控制面板
	 * @author pwstrick
	 */
	public function index() {
		$this->mainTemplate(false, '控制面板');
		$this->view->display('index/index');
	}
	
	/**
	 * demo
	 */
// 	public function index() {
// 		echo $this->pageNo();
// 		echo base_url(constHelper::IMG_ASYN_URL);
// // 		$sqlcontrol = InitPHP::getUtils('sqlcontrol');
// // 		$sqlcontrol->start();
// // 		$userModel = InitPHP::getMysqlDao('member');
// // 		//var_dump($userModel);exit();
// // 		print_r($userModel->getMembers());
// // 		echo $userModel->getUserCount() . '<br>';
// // 		print_r($userModel->updateMonther()). '<br>';
// // 		$sqlcontrol->end();
// // 		$this->view->assign('uid', 'pwst');
// // 		$this->view->display('index_hello');
		
// // 		$debug = InitPHP::getUtils('debug');
// // 		$this->hello();
// // 		$debug->dump(array('hello'));
// // 		$debug->mark('hello');
// // 		echo $debug->use_memory('hello');
// 	}
	
	/**
	 * demo
	 */
	public function hello() {
		echo $this->pageNo();
		echo 'hello';
	}
	
	/**
	 * demo
	 */
	public function cache() {
		$userCache = InitPHP::getCacheDao('member');
		print_r($userCache->test());
	}
	
	/**
	 * demo
	 */
	public function mongo() {
		$userMongo = InitPHP::getMongoDao('member');
		//print_r($userMongo->getMembers()) . '<br>';
		//print_r($userMongo->getMemberCount()) . '<br>';
		print_r($userMongo->updateMember());
		var_dump($userMongo->insert(array('subscribe'=>1, 'create_time'=>time())));
	}
	
	/**
	 * demo
	 */
	public function rpc() {
		$ret = InitPHP::getRemoteService("user", "getUser", array(), 'admin');
		print_r($ret);
	}
	
	/**
	 * demo
	 */
	public function unittest() {
		InitPHP::getUtils('unittesting')->run('user');
	}
}