<?php

/**
 * 不需要登录的操作
 * @author pwstrick
 *
 */
class publicController extends adminController {
	/**
	 * action列表
	 */
	public $initphp_list = array('login', 'ajaxlogin', 'logout');
	
	/**
	 * 登录
	 * @author pwstrick
	 */
	public function login() {
		$this->publicTemplate('登录');
		$this->view->display('public/login');
	}
	
	/**
	 * ajax登录
	 * @author pwstrick
	 */
	public function ajaxlogin() {
		$account = $this->p('name');
		$pwd = $this->p('pwd');
		$pwd = md5($pwd . constHelper::PWD_KEY);
		
		$userModel = InitPHP::getMysqlDao('user', 'mysql/sys');
		$account = $userModel->login($account, $pwd);

		if(empty($account)) {
			$this->ajaxFailureOutput('用户名或密码不正确');
			return;
		}
		
		//保存到session中
		$session = $this->getUtil('session');
		$session->set(constHelper::ADMIN_SESSION, $account);
		
		$this->ajaxSuccessOutput('登录成功');
	}
	
	/**
	 * 退出登录
	 * @author pwstrick
	 */
	public function logout() {
		$session = $this->getUtil('session');
		$session->del(constHelper::ADMIN_SESSION);
		//跳转到登录
		$this->controller->redirect(base_url('public/login'));
	}
}