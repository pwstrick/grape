define([
	'backbone',
	'viewUtil',
	'dialogView',
	'modelUtil'
], function (Backbone, viewUtil, dialogView, modelUtil) {
	var menu = Backbone.View.extend({
		el: $('body'),
		initialize: function() {
			viewUtil.model.push(this.model);
		},
		events: {},
		render: function() {},
		teacher_edit: function() {//讲师form提交
			if($('#teacher_edit').length == 0) {
				return;
			}
			this.model.teacher_edit_validate(viewUtil.callback);
		},
		teacher_del: function() {//tag标签删除操作
			if($('#teacher_edit').length != 0) {
				//讲师编辑添加页面删除操作
				modelUtil.detailDelPost($('#teacher_edit .form-actions'), dialogView);
			}
			
			if($('#course_edit').length != 0) {
				//课程编辑添加页面删除操作
				modelUtil.detailDelPost($('#course_edit .form-actions'), dialogView);
			}
			
			if($('table.table').length != 0) {
				//列表页面删除操作
				modelUtil.listDelPost($('table.table>tbody>tr>td'), dialogView);
			}
		},
		course_edit: function() {//课程form提交
			if($('#course_edit').length == 0) {
				return;
			}
	    	this.model.course_edit_validate(viewUtil.callback);
		},
		sms_send: function() {//短信发送
			if($('table.table').length != 0) {
				//列表页面删除操作
				modelUtil.smsSend($('table.table>tbody>tr>td'), 
						dialogView, true, ['id']);
			}
			if($('#student_detail').length != 0) {
				modelUtil.smsSend($('#student_detail'), 
						dialogView, true, ['id']);
			}
		},
		table_check: function() {//表格多选框选中
			viewUtil.selectAll();
		},
		batch_sms_send: function() {//批量发送短信
			if($('#batch_sms').length == 0) {
				return;
			}
			$('#batch_sms').click(function() {
				$checked = $(':checkbox[name=cb_id]:checked');
				if($checked.length == 0) {
					dialogView.alert('请选中要发送的会员');
					return false;
				}
				
				var ids = [];
				var mobiles = [];
				$checked.each(function() {
					ids.push($(this).val());
					mobiles.push($(this).data('mobile'));
				});
				$(this).data('mobile', mobiles.join(','));
				$(this).data('id', ids.join(','));
				
				modelUtil.btnSmsSend($(this), dialogView, true, ['id']);
				return false;
			});
		}
	});
	return menu;
});
