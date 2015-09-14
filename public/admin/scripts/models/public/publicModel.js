define(['backbone', 'underscore', 'constUtil', 'comUtil', 'modelUtil'],
	function(Backbone, _, constUtil, comUtil, modelUtil) {
	var list = Backbone.Model.extend({
		url: '',
		ajaxLogin: comUtil.absUrl(constUtil.ajaxLogin),
		webHome: comUtil.absUrl(constUtil.webHome),
		defaults: {},
		initialize: function() {

	    },
		isSuccess: function(json) {
			return comUtil.isJsonSuccess(json);
		},
		setReloadTimeout: function() {
			return comUtil.setReloadTimeout();
		},
		username: function(object) {
			if(_.isEmpty(object)) {
				return '请输入用户名';
			}
			return false;
		},
		pwd: function(object) {
			if(_.isEmpty(object)) {
				return '请输入密码';
			}
			return false;
		},
		post: function(url, btn, input, fn) {//post通用函数
			return modelUtil.comPost(url, btn, input, fn);
		}
	});
	return list;
});
