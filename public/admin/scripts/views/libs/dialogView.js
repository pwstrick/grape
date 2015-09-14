define([
	'dialogPlus'
], function (dialog) {
	var layer ={};
	layer.confirm = function(content, okFn) {
		var params = {
		    content: content,
		    quickClose: true,// 点击空白处快速关闭
		    okValue: '确定',
		    ok: okFn,
		    cancelValue: '取消',
		    cancel: function() {},
		    padding: 30
		};
		var d = dialog(params);
		d.show();
	};
	layer.bubble = function(it, content, align) {//气泡显示
		align = align || 'right';
		var d = dialog({
				//cancel:true,
				//cancelValue:'关闭',
				align: align,
		    content: content,
		    quickClose: true// 点击空白处快速关闭
		});
		d.show(it);
		return d;
	};
	layer.alert = function(content) {//弹出信息
		var d = dialog({
			content: content,
			padding: 20
		});
		d.show();
		setTimeout(function () {
			d.close().remove();
		}, 3000);
	};
	layer.show = function(content, opts) {//弹出信息
		var defaults = {
			content: content,
			padding: 20,
			cancelValue:'关闭',
			cancel:function(){}
		};
		var d = dialog($.extend({},defaults,opts));
		d.show();
	};
	layer.prompt = function(options) {//确定与取消
		var d = dialog(options);
		d.show();
		return d;
	};
	layer.pop = function(follow, opts) {//弹出信息
		var defaults = {
			align : 'bottom',
			quickClose: true,
			content: '',
			width: 300
		};
		var d = dialog($.extend({},defaults,opts));
		d.show(follow);
		return d;
	};
	layer.modal = function(attrs) {//iframe弹框
		dialog(attrs).showModal();
	};
	layer.parent = function(attrs) {//iframe弹框 与父窗口有联动
		window.dialog = dialog;
		dialog(attrs).showModal();
	};
	layer.close = function(attrs) {//iframe弹框 层中关闭事件绑定
		if(top.dialog == undefined) return;
		var topDialog = top.dialog.get(window);//从父级页面传过来的参数
		if(attrs['data'] == undefined)
			attrs['data'] = '';
		if(attrs['btn'] == undefined) {
			topDialog.close(attrs['data']);//直接关闭
		}else {
			attrs['btn'].click(function() {
				topDialog.close(attrs['data']);
			});
		}
	};
	function _showModal(attrs) {
		dialog(attrs).showModal();
	}
	// layer.setPrivateMessage = function() {//私信弹出层
	// 	$('a[name=btn_send_msg]').click(function() {
	// 		window.dialog = dialog;
	// 		var sendid = $(this).attr('sendid');
	// 		var uname = $(this).attr('uname');
	// 		var attrs = {
	// 			url: constUtil.absUrl(constUtil.webMessageSend),
	// 			title: '发私信',
	// 			padding: 10,
	// 			height: 250,
	// 			width: 510,
	// 			data: {sendid:sendid, uname:uname}
	// 		};
	// 		_showModal(attrs);
	// 		return false;
	// 	});
	// };

	return layer;
});
