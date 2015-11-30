define([
    'jquery',
	'shortcutView',
	'uploadifyView',
	'ueditorView',
	'ztreeView',
	'formModel',
	'formView',
	'cityView'
], function($, shortcut, uploadifyView, ueditorView, ztreeView, formModel, formView, cityView) {
  var initialize = function() {
		shortcut.allNeed();
		
		var model = new formModel();
		var view = new formView({model:model});
		
		$('form').each(function() {
			var $form = $(this);
			//绑定上传按钮
			var uploadify = $form.data('uploadify');
			if(uploadify != undefined) {
				var uploadifys = uploadify.split(',');
				$.each(uploadifys, function(index, value) {
					if(value.length > 0) {
						uploadifyView.uploadImages(value);
					}
				});
			}
			
			//绑定编辑器
			//var input_ueditors = [];
			var ueditor = $form.data('ueditor');
			if(ueditor != undefined) {
				var ueditors = ueditor.split(',');
				$.each(ueditors, function(index, value) {
					if(value.length > 0) {
						//input_ueditors.push();
						ueditorView.setUeditor(value);
					}
				});
			}
			
			//弹出层效果
			var iframe = $form.data('hiddeniframe');
			if(iframe != undefined) {
				var iframes = iframe.split(',');
				$.each(iframes, function(index, value) {
					if(value.length > 0) {
						view.hidden_iframe(value);
					}
				});
			}
			
			//绑定树形按钮
			var ztree = $form.data('ztreeCheckbox');
			if(ztree != undefined) {
				var ztrees = ztree.split(',');
				$.each(ztrees, function(index, value) {
					if(value.length > 0) {
						ztreeView.setCheckbox(value);
					}
				});
			}
			
			//绑定省市级联动
			var city = $form.data('city');
			if(city != undefined) {
				var cities = city.split(',');
				$.each(cities, function(index, value) {
					if(value.length > 0) {
						cityView.init(value);
					}
				});
			}
			
			//通用验证特效
			if($form.hasClass('validate')) {
				//var special_inputs = {ueditors:input_ueditors}
				view.btn_validate($form);
				view.form_btn();
			}
		});
  };
  return { 
    initialize: initialize
  };
});
