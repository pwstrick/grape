<?php

class enumHelper {
	//api中不需要登录的
	public static $no_login_api = array(
// 		'index' => array('hello')
	);
	
	//后台不需登录的地址
	public static $no_login_url = array(
		'public' => array('login', 'ajaxlogin'),
		'widget' => array('ueditor')
	);
	
	//后台菜单，可做成动态
	public static $admin_menu = array(
		'index' => array(
			'action' => 'index',
			'icon' => 'home',
			'text' => '控制面板',
		),
		'user' => array(
			'action' => '#',
			'icon' => 'signal',
			'text' => '用户管理',
			'sub' => array(
				array('action'=>'lists', 'icon'=>'th', 'text'=>'用户列表'),
				array('action'=>'add', 'icon'=>'fullscreen', 'text'=>'添加用户')
			)
		)
	);
	
	//后台不需要token验证的请求
	public static $no_token_url = array(
		'widget' => array('ueditor'),
		'user' => array('ajaxtree')
	);
}