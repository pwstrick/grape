define(function() {
	window.UEDITOR_HOME_URL = '/scripts/libs/ueditor/';//编辑器脚本文件目录
	/*
	* 测试环境使用
	*/
//	window.UEDITOR_HOME_URL = '/huali_admin/scripts/libs/ueditor/';
	var constUtil = {
		domain : 'http://' + window.location.host + '/',
		domain_admin : 'http://' + window.location.host + '/',
		domain_scripts : 'http://' + window.location.host + '/scripts/',
		/*
		 * 测试环境使用
		 */
//		domain : 'http://' + window.location.host + '/huali_admin/',
//		domain_admin : 'http://' + window.location.host + '/huali_admin/',
//		domain_scripts : 'http://' + window.location.host + '/huali_admin/scripts/',
		//web主页面
		webHome : '',
		//web登录页
		webLogin : 'public/login',
		//ajax操作
		ajaxLogin : 'public/ajaxlogin',//登录
		ueditorUpload: 'widget/ueditor',//ueditor编辑器上传地址
		ajaxHomeOrder: 'ajax/home_order.php',//控制面板ajax获取订单
		ajaxHomeUser: 'ajax/home_user.php',//控制面板ajax获取用户
		ajaxHomeChart: 'ajax/home_chart.php',//控制面板ajax获取图表
		resultSuccess: 1,
		resultFailure: 0,
		rules:['valueNotEquals', 'isTime', 'regexpTo', 'confirmTo', 'required', 'remote', 'email', 'url', 'date', 
			'dateISO','number', 'digits', 'creditcard', 'equalTo', 'maxlength', 'minlength', 
			'rangelength','range','max', 'min'],
		rules_hash:{'valueNotEquals':'value-not-equals', 'isTime':'is-time', 'dateISO':'date-i-s-o',
			'equalTo':'equal-to', 'regexpTo':'regexp-to', 'confirmTo':'confirm-to'}
	};
	return constUtil;
});
