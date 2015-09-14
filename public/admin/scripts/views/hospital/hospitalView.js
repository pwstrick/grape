define([
	'backbone',
	'viewUtil',
	'dialogView',
	'comUtil'
], function (Backbone, viewUtil, dialogView, comUtil) {
	var menu = Backbone.View.extend({
		el: $('body'),
		initialize: function() {
			viewUtil.model.push(this.model);
		},
		events: {},
		render: function() {},
		history_edit: function() {//form提交
			if($('#history_edit').length == 0) {
				return;
			}
			var _model = this.model;
			var success = function(json) {
	    		if(_model.isSuccess(json)) {
					location.href = _model.hospital_info_url;//跳转到资料详情页面
				}
				viewUtil.setError(json.msg);
	    	};
	    	_model.history_edit_validate(success);
		},
		history_edit_tag: function() {//tag标签删除操作
			var _model = this.model;
			if($('#history_edit').length == 0) {
				return;
			}
			$('#history_edit .controls').delegate('span:not(.help-inline)', 'click', function() {
				if(!confirm('您确定要删除该条信息吗？')) {
					return;
				}
				
				var $hidden = $(this).siblings(':hidden');
				var id = $(this).attr('data-id');
				viewUtil.tagRemove($hidden, id, $(this));
			});
			$('#history_edit .controls').delegate('a', 'click', function() {
				var $this = $(this);
				var attrs = {
					url: _model.addtag_url,
					title: $this.html(),
					padding: 10,
					height: 200,
					width: 400,
					data: {tag:$this.attr('data-type')},
					onclose: function() {
						//返回的是一个JSON串
						var data = this.returnValue.data;
						if(data === undefined) return;
						//当关闭的时候接收弹出层的返回值
						$span = $('<span/>').addClass('btn btn-info mr5').attr('data-id', data.name).html(data.name);
						//在隐藏域中新增一个value值
						var $hidden = $this.siblings(':hidden');
						$span.insertBefore($hidden);
						
						viewUtil.tagAdd($hidden, data.name);
					}
				};
				dialogView.parent(attrs);
				return false;
			});
		},
		history_addtag: function() {//弹出层中添加标签
			var _model = this.model;
			var $form = $('#history_addtag');
			if($form.length == 0) {
				return;
			}
			var topDialog = top.dialog.get(window);//从父级页面传过来的参数
			var tag = topDialog.data.tag;
			$(':hidden[name=tag]').val(tag);
			//关闭按钮
			dialogView.close({'btn':$form.find('[type=button]')});
			var success = function(json) {
	    		if(_model.isSuccess(json)) {
	    			//将值传回
	    			dialogView.close({'data':json});
				}
	    		viewUtil.setError(json.msg);
	    	};
	    	_model.history_addtag_validate(success);
		},
		hospital_pwd: function() {//修改密码form提交
			if($('#hospital_pwd').length == 0) {
				return;
			}
			var _model = this.model;
			var success = function(json) {
	    		if(_model.isSuccess(json)) {
					//退出重新登陆
	    			comUtil.redirectLogin();
				}
				viewUtil.setError(json.msg);
	    	};
	    	_model.hospital_pwd_validate(success);
		}
	});
	return menu;
});
