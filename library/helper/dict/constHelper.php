<?php

class constHelper {
	const PWD_KEY = '^%$#1()'; //密码混淆码
	const IMG_ASYN_URL = 'images/loading.gif';//预加载图片路径
	const ADMIN_SESSION = 'admin_user'; //后台用户session名
	
	/**
	 * ajax请求状态码
	 */
	const AJAX_SUCCESS = 1;
	const AJAX_FAILURE = 0;
	const AJAX_REDIRECT = 2;	//未登录跳转
	
	// 1000代表成功的API请求，一般情况下返回的都是1000
	// 1001代表安全认证失败，也就是说__key认证失败
	// 1002代表客户端需要更新，APP收到此code应该引导用户升级
	// 1003代表需要登录，APP收到此code应该引导用户登录
	// 1004代表404，未找到对应的资源
	// 1005代表服务器内部错误
	// 1006代表请求方式不正确，必须为POST请求
	const API_STATUS_SUCCESS = 1000;
	const API_STATUS_AUTH_FAIL = 1001;
	const API_STATUS_NEED_UPGRADE = 1002;
	const API_STATUS_NEED_LOGIN = 1003;
	const API_STATUS_404 = 1004;
	const API_STATUS_500 = 1005;
	const API_STATUS_NEED_POST = 1006;

	//客户端最小版本支持，用于强制更新版本
	const MIN_VERSION_ALLOWED = '1.0';
}