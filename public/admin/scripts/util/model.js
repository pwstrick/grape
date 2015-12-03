define(['comUtil', 'dialogView'], function(comUtil, dialog) {
	var modelUtil = {
		comPost: function(url, btn, input, fn) {//post通用函数
			if(_.isEmpty(url) || _.isUndefined(url)) {
				return false;
			}
			comUtil.showAjaxLoading(btn);
			//设置CSRF风险控制参数
			if(_.isUndefined(input['init_token'])) {
				input['init_token'] = $('#init_token').val();
			}
			return $.post(url, input, function(json) {
				comUtil.showJsonPrompt(json, fn)(comUtil);
			});
		},
		delPost: function($btn, fn) {//通用删除操作，包括带提示的操作
			if($btn.attr('data-type') != 'del') {
				return false;
			}
			var _this = this;
			var okFn = function() {
				//遍历自定义属性
				var params = $btn.data();
				_this.comPost($btn.data('href'), $btn, params, fn);
			};
			var prompt = $btn.data('prompt');
			if(prompt == undefined) {
				prompt = '您确定要删除吗？';
			}
			dialog.confirm(prompt, okFn);
			return false;
		},
		listDelPost: function($container, dialogView) {//列表页面删除操作，包括带提示的操作
			var _this = this;
			$container.delegate('a[data-type=del]', 'click', function() {
				var $this = $(this);
				_this.delPost($this, function(json) {
					if(comUtil.isJsonSuccess(json)) {
						var $tr = $this.parent().parent();
						var $tbody = $tr.parent();
						var reload = $this.data('reload');
						if($tbody.children().length == 0 || reload === true) {
							//当只有一条数据或设置了刷新  直接刷新当前页面
							location.reload();
						}else {
							$tr.remove();
						}
					}
					if(dialogView != undefined)//dialog插件
						dialogView.alert(json.msg);
					else
						alert(json.msg);//粗暴的弹出框
				});
				//return false; 因为有一种情况是只要弹出提示然后做跳转所以注释掉了
			});
		},
		detailDelPost: function($container, dialogView) {//详情页面删除操作
			var _this = this;
			$container.delegate('a[data-type=del]', 'click', function() {
				_this.delPost($(this), function(json) {
					if(comUtil.isJsonSuccess(json)) {
						history.back(-1);
					}
					if(dialogView != undefined)//dialog插件
						dialogView.alert(json.msg);
					else
						alert(json.msg);//粗暴的弹出框
				});
				return false;
			});
		},
		smsSend: function($container, dialogView, isAlert, other_attrs) {//短信发送
			var _this = this;
			$container.delegate('a[data-type=sms]', 'click', function() {
				_this.btnSmsSend($(this), dialogView, isAlert, other_attrs);
				return false;
			});
		},
		btnSmsSend: function($this, dialogView, isAlert, other_attrs) {//短信发送事件
			var _this = this;
			
			var okFn = function() {
				var input = {
						'mobile':$this.data('mobile'), 
						'category':$this.data('category')
				};
				//其他属性的加入
				if(other_attrs !== undefined && $.isArray(other_attrs)) {
						$.map(other_attrs, function(key) {
							input[key] = $this.data(key);
						});
				}
				var fn = function(json) {
					if(dialogView != undefined)//dialog插件
						dialogView.alert(json.msg);
					else
						alert(json.msg);//粗暴的弹出框
				};
				_this.comPost($this.attr('href'), $this, input, fn);
			};
			if(isAlert === true) {//需要弹出提示
				dialog.confirm('您确定要发送吗？', okFn);
			}else {
				okFn();
			}
		}
	};
	return modelUtil;
});
