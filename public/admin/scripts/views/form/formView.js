define([
	'backbone',
	'comUtil',
	'viewUtil',
	'dialogView',
	'select2'
], function (Backbone, comUtil, viewUtil, dialogView) {
	var menu = Backbone.View.extend({
		el: $('body'),
		initialize: function() {
			viewUtil.model.push(this.model);
		},
		events: {},
		render: function() {},
		hidden_iframe: function(btn) {//简单的操作hidden控件的弹出层
			if(!document.getElementById(btn)) {//不存在
				return;
			}
			var $btn = $('#'+btn);
			var $hidden = $btn.siblings(':hidden').eq(0);
			if($hidden.length == 0) {
				var hidden_id = btn+'_hidden';
				var attrs = {'type':'hidden', 'id':hidden_id, 'name':hidden_id};
				var hidden_attrs = comUtil.getValidateAttrs($btn, attrs);
				$hidden = $('<input>').attr(hidden_attrs);
				var value = $btn.data('value');
				if(value != undefined) {
					$hidden.val(value);
				}
				$hidden.insertAfter($btn);
			}
			$btn.click(function() {
				var fn = function(data) {
					//console.log(json);
					var $hidden = $('#'+btn+'_hidden');
					$btn.attr('data-value', data.ids);
					$hidden.val(data.ids);
					$btn.siblings('span').remove();
					//数组类型
					if($.isArray(data.names)) {
						$.each(data.names, function(key, value) {
							$('<span class="btn btn-info mr5">'+value+'</span>').insertBefore($btn);
						});
					}
					//string类型
					if(_.isString(data.names)) {
						
					}
					//console.log(data);
				};
				viewUtil.topiFrame($(this), dialogView, fn);
				return false;
			});
		},
		btn_validate: function($form) {//通用按钮验证
			var _model = this.model;
			//初始化form中错误提示标签
			var $submit = $form.find(':submit');
			var $parents = $submit.parents('.form-actions');
			var $error = $parents.find('.alert-error');
			if($error.length == 0) {
				$parents.append('<div class="alert alert-error mt10 hide" id="prompt"></div>');
			}
			
			/*
			 * 特殊按钮的一些事件绑定
			 */
			$form.delegate(':input[data-ajax-blur=true]', 'blur', function() {
				//绑定需要ajax验证的按钮
				var $this = $(this);
				var txt = $.trim($this.val());
				//空的就不请求
				if(txt.length == 0) {
					return;
				}
				var params = $this.data();
				params['value'] = txt;
				var fn = function(json) {
					
					var $span = $this.siblings('span.ajax-validate');
					if($span.length == 0) {
						$span = $('<span>').addClass('ajax-validate');
					}
					$span.removeClass('error-inline').removeClass('help-inline');
					var css = 'help-inline';
					if(!comUtil.isJsonSuccess(json)) {
						css = 'error-inline';
					}
					$span.addClass(css).css('display', 'inline-block').html(json.msg);
					$span.insertAfter($this);
				};
				_model.post($this.data('ajax'), $this, params, fn);
			});
			
			var success = function(json) {
				if(comUtil.isJsonSuccess(json)) {
					var href = $form.data('href');//如果不是弹出层
					var layer = $form.data('layer');//如果是弹出层
					//如果有href 就做跳转
					if(href !== undefined){
						comUtil.setReloadTimeout(500, href);
					}else if(layer != undefined && layer === true)//关闭弹出层
						dialogView.close({'data':json});
//					else
//						comUtil.setReloadTimeout();//TODO
				}
				//var others = {id:'prompt_'+$form.attr('id'), direction:'next', container:$form.find(':submit')};
				viewUtil.setError(json.msg);
			};
			this.model.com_validate($form, success);
		},
		tree_iframe: function($form, treeId) {//弹出层处理
			var _model = this.model;
			if($form.length == 0) {
				return;
			}
			var topDialog = top.dialog.get(window);//从父级页面传过来的参数
			var value = topDialog.data.value;
			//给UL赋值
			$('#'+treeId).attr('data-value', value);
		},
		btn_close: function($form) {//关闭按钮绑定
			//关闭按钮
			dialogView.close({'btn':$form.find('[name=close]:button')});
		},
		form_btn: function() {//form表单下面的按钮
			var _model = this.model;
			//克隆按钮
			$('form').delegate('[data-type="clone"]', 'click', function() {
				var $this = $(this);
				var target = $this.data('target');
				var targets = target.split(',');
				var $container = $this.closest('.control-group');//包含的div
				var length = $('[data-type="clone_del"]').length;
				var new_names = [];
				var prompt = $this.data('prompt');
				
				var $last;
				$.each(targets, function(key, value) {
					var $target = $('[name="'+value+'"]');
					var $clone = $target.clone().removeClass('success').removeClass('error');//去除提示类
					$clone.find("[id$='-error'],.select2").remove();//去除错误提示与控件美化的标签
					var $select = $clone.find('select');
					if($select.length > 0) {
						setTimeout(function() {
							$select.removeClass('select2-hidden-accessible').select2();
						}, 100);
					}
					var name = $clone.attr('name')+length;
					new_names.push(name);
					$clone.attr('name', name);
					$clone.find('[type=number],:text').val('');
					$clone.insertBefore($container);
					$last = $clone;
				});
				
				var btn_attrs = {'type':'button', 'data-target':new_names.join(','), 
									'data-type':'clone_del', 'data-prompt':prompt};
				var $btn = $('<button>').attr(btn_attrs).text('删除').addClass('btn');
				$last.find('.controls').append($btn);
			}).delegate('[data-type="clone_del"]', 'click', function() {
				var $this = $(this);
				var prompt = $this.data('prompt');
				var  okFn = function() {
					var target = $this.data('target');
					var targets = target.split(',');
					$.each(targets, function(key, value) {
						var $target = $('[name="'+value+'"]');
						$target.remove();
					});
				};
				
				dialogView.confirm(prompt, okFn);
			}).delegate('[data-type="custom"]', 'click', function() {
				//自定义按钮操作
				var $this = $(this);
				var prompt = $this.data('prompt');
				var ajax = $this.data('ajax');
				var href = $this.data('href');
				var params = $this.data();
				
				var success = function(json) {
					if(comUtil.isJsonSuccess(json)) {
						comUtil.setReloadTimeout(500, href);
					}
					dialogView.alert(json.msg);
				};
				
				if(_.isEmpty(prompt)) {
					//么有提示语，直接操作
					_model.post(ajax, $this, params, success);
					return;
				}
				var  okFn = function() {
					_model.post(ajax, $this, params, success);
				};
				
				dialogView.confirm(prompt, okFn);
			});
			
		}
	});
	return menu;
});
