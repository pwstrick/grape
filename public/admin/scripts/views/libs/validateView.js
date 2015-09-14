define([
	'underscore',
	'comUtil',
	'validate',
	'form'
], function (_, comUtil) {
	var layer ={};
	layer.common = function(form_id, rules, messages, form_success) {
		if(messages == undefined) {
			messages = {};
		}
		//默认提交函数判断
		if(!_.isFunction(form_success) ) {
			form_success = function() {};
		}
		var form_container = "#"+form_id;
		var $form_container = $(form_container);
		var btn = $form_container.find(':submit');
		/**
		 * 自定义方法需要在const.js中配置
		 */
		$.validator.addMethod("regexpTo", function(value, element, arg) {//自定义正则
			var re = new RegExp(arg,"g");
			re.lastIndex = 0;
			var result = re.test(value) 
			return result;
    	}, "regexp");
		$.validator.addMethod("valueNotEquals", function(value, element, arg) {//不等于
    		return arg != value;
    	}, "Value must not equal arg.");
		$.validator.addMethod("isTime", function(value, element, arg) {//判断是否是时间
			var a = value.match(/^(\d{1,2})(:)?(\d{1,2})$/);
			if (a == null) { return false;}
			if (a[1]>24 || a[3]>60)
				return false
			return true;
    	}, "Value must time.");
    	$.validator.addMethod("confirmTo", function(value, element, arg) {//密码确认
    		var $target = $('#'+arg);
    		return $target.val() == value;
    	}, "Value must not equal target.");
    	
		var promptError = $form_container.data('promptError') || false;
		$form_container.validate({
			rules:rules,
			messages:messages,
			errorClass: "help-inline",
			errorElement: "span",
			//ignore: 'input[type=hidden]',
			ignore: '',
			errorPlacement: function(error, element) {
				//console.log(element.closest('label').legnth)
				//console.log(error.html())
				//查找到与自己最近的一个错误提示
				var $alert_error = element.closest('.widget-box').nextAll('.form-actions').first();
				if($alert_error.length > 0) {
					$alert_error.find('.alert-error').show().html(error.html());
				}else {
					$form_container.find('.alert-error').show().html(error.html());//显示最后一个的错误信息
				}
				if(promptError) {
					return;
				}
				
				if(element.is(':radio') || element.is(':checkbox')) {//单选框与多选框
					element.closest('label').append(error);
				}else if(element.siblings('.uploadify').length > 0) {//上传插件
					error.insertAfter(element.siblings('.uploadify'));
				}else if(element.hasClass('select2-hidden-accessible')) {//美化过的下拉框
					error.insertAfter(element.next());
				}else {
					var last = element.siblings('input:last');//输入框
					if(last.length > 0)
						error.insertAfter(last);
					else
						error.insertAfter(element);
				}
			},
			highlight:function(element, errorClass, validClass) {
				var elem = $(element);
				elem.parents('.control-group').addClass('error');
				elem.parents('.control-group').removeClass('success');
			},
			unhighlight: function(element, errorClass, validClass) {
				var elem = $(element);
				//排除三个select省级联动的情况
				var select_length = elem.siblings('select').length;
				//显示正确的标记
				if(elem.siblings().attr('aria-describedby') === undefined && select_length <= 1) {
					elem.parents('.control-group').removeClass('error');
					elem.parents('.control-group').addClass('success');
				}
				
				/*
				 * 有一种情况是 部分的验证通过了，部分的没有通过，那么就应该继续显示按钮旁边的错误提示
				 */
				if($('.control-group').hasClass('error') == false) {
					var $alert_error = elem.closest('.widget-box').nextAll('.form-actions').first();
					if($alert_error.length > 0) {
						$alert_error.find('.alert-error').hide().html('');
					}else {
						$form_container.find('.alert-error').hide().html('');//显示最后一个的错误信息
					}
				}
			},
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					beforeSubmit:function() {
						comUtil.showAjaxLoading(btn);
					},
					success: function(responseText) {
						comUtil.showJsonPrompt(responseText, form_success)(comUtil);
					}
				});
			}
		});
	};
	return layer;
});
