<?php

class indexController extends apiController {

	/**
	 * action列表
	 */
	public $initphp_list = array('hello');
	
	public function index() {
		return $this->controller->ajax_return(200, "SUCCESS", array("uid" => 10), 'json');
	}
	
	public function hello() {
//  		$userSearch = InitPHP::getSearchDao('member');
// 		$userSearch->search();
		$this->output(1, 'hello成功', array('id'=>uniqid()));
	}
}