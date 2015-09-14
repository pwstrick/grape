define([
	'backbone',
	'viewUtil'
], function (Backbone, viewUtil) {
	var menu = Backbone.View.extend({
		el: $('body'),
		initialize: function() {
			viewUtil.model.push(this.model);
			viewUtil.placeholder();
		},
		events: {

		},
		render: function() {

		},
		visibleForgetPage: function() {//显示或隐藏找回密码页面
				$('#to-recover').click(function() {
						$("#loginform").slideUp();
						$("#recoverform").fadeIn();
						viewUtil.clearError();
						return false;
				});
				$('#to-login').click(function() {
						$("#recoverform").hide();
						$("#loginform").fadeIn();
						viewUtil.clearError();
						return false;
				});
		},
		login: function() {//登录操作
			var _model = this.model;
			viewUtil.enterClick('#name,#pwd', '#btnLogin');
			$('#btnLogin').click(function() {
				var fn = function(json) {
					if(_model.isSuccess(json)) //跳转到首页
						location.href = _model.webHome;
					viewUtil.setError(json.msg);
				};
				var name = $.trim($('#name').val());
				if(!viewUtil.isError('username', name)) return false;
				
				var pwd = $.trim($('#pwd').val());
				if(!viewUtil.isError('pwd', pwd)) return false;
				
				//传参数
				_model.post(_model.ajaxLogin, $(this), {name:name, pwd:pwd}, fn);
			});
		},
		revocer: function() {//找回密码
			$('#btnRecover').click(function() {

			});
		}
	});
	return menu;
});
