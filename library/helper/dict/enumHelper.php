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
	
	//后台不需要token验证的请求
	public static $no_token_url = array(
		'widget' => array('ueditor'),
		'user' => array('ajaxtree')
	);
	
	//后台不需要权限判断的页面
	public static $no_auth_url = array(
		'index' => array('index'),
		'system' => array('admininfo', 'adminpwd'),
		'public' => array('login', 'logout')
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
				array('action'=>'lists', 'text'=>'用户列表'),
				array('action'=>'add', 'text'=>'添加用户')
			)
		),
		'system' => array(
			'action' => '#',
			'icon' => 'cog',
			'text' => '系统配置',
			'sub' => array(
				array('action'=>'adminuser', 'text'=>'用户列表'),
				array('action'=>'module', 'text'=>'模块列表'),
				array('action'=>'grouplist', 'text'=>'分组列表'),
				array('action'=>'groupuser', 'text'=>'分组与用户'),
				array('action'=>'auth', 'text'=>'权限设置')
			)
		)
	);
	
	//作为菜单的功能页面
	public static $action_menu = array(
		0 => '不能设置为菜单',
		1 => '可以设置为菜单'
	);
}