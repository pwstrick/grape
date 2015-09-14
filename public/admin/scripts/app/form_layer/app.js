define([
    'jquery',
	'shortcutView',
	'ztreeView',
	'formModel',
	'formView'
], function($, shortcut, ztreeView, formModel, formView) {
  var initialize = function() {
		shortcut.allNeed();
		
		var $form = $('form').eq(0);
		
		var model = new formModel();
		var view = new formView({model:model});
		
		//弹出层效果
		view.btn_close($form);
		
		//绑定树形按钮
		var ztree = $form.data('ztreeCheckbox');
		if(ztree != undefined) {
			var ztrees = ztree.split(',');
			$.each(ztrees, function(index, value) {
				if(value.length > 0) {
					view.tree_iframe($form, value);//要在setCheckbox的前面赋data-value值
					ztreeView.setCheckbox(value);
				}
			});
		}
		
		//通用验证特效
		if($form.hasClass('validate')) {
			view.btn_validate($form);
		}
  };
  return { 
    initialize: initialize
  };
});
