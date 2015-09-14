define([
	'backbone',
	'viewUtil',
	'comUtil',
	'mustache'
], function (Backbone, viewUtil, comUtil, Mustache) {
	var menu = Backbone.View.extend({
		el: $('body'),
		initialize: function() {
			viewUtil.model.push(this.model);
		},
		events: {
		},
		render: function() {

		},
		lineChart: function() {//折线图表分析
			var _model = this.model;
			require(['echartsView'],
				function (chart) {
					if($('#line_chart').length == 0) {
						return;
					}
					//var url = comUtil.absUrl()
					var fn = function(json) {
						var data = json.data;
						var options = {
							tooltip : {
								trigger: 'axis'
							},
							legend: {
								data:['用户数','订单数'],
								x: 'left'
							},
							toolbox: {
								show : true,
								feature : {
									mark : {show: true},
									restore : {show: true},
									saveAsImage : {show: true}
								}
							},
							calculable : true,
							xAxis : [
								{
									type : 'category',
									boundaryGap : false,
									data : data['date']
								}
							],
							yAxis : [
								{
									name : '用户数',
									type : 'value',
									axisLabel : {
										formatter: '{value} '
									}
								},
								{
									name : '订单数',
									type : 'value',
									axisLabel : {
										formatter: '{value} '
									}
								}
							],
							series : [
								{
									name:'用户数',
									type:'line',
									data: data['user_num']
								},
								{
									name:'订单数',
									type:'line',
									yAxisIndex:1,
									data: data['order_num']
								}
							]
						};
						chart.line('line_chart', options);
					};
					_model.post(_model.ajaxHomeChart, $('#line_chart'), {}, fn);
				}
			);
		},
		getOrders: function() {//获取最近订单信息
			var _model = this.model;
			var _this = this;
			var $new_orders = $('#new_orders');
			_this._ajaxOrders(_model, $new_orders, 1);
			$new_orders.delegate('.pagination a', 'click', function() {
				_this._ajaxOrders(_model, $new_orders, $(this).data('page'));
			});
		},
		_ajaxOrders: function(_model, $new_orders, pageNo) {
			var fn = function(json) {//包数据
				if(comUtil.isNoData(json.data)) {
					$new_orders.append(viewUtil.noListDiv());
					return;
				}
				var rendered = Mustache.render($('#order_template').html(), {'orders': json.data.list});
    			$('#order_info').html(rendered);
				viewUtil.ajaxPagination($new_orders, json.data.page);
			};
			_model.post(_model.ajaxHomeOrder, $new_orders, {'p': pageNo}, fn);
		},
		getUsers: function() {//获取最近用户信息
			var _model = this.model;
			var _this = this;
			var $new_users = $('#new_users');
			_this._ajaxUsers(_model, $new_users, 1);
			$new_users.delegate('.pagination a', 'click', function() {
				_this._ajaxUsers(_model, $new_users, $(this).data('page'));
			});
		},
		_ajaxUsers: function(_model, $new_users, pageNo) {
			var fn = function(json) {//包数据
				if(comUtil.isNoData(json.data)) {
					$new_users.append(viewUtil.noListDiv());
					return;
				}
				var rendered = Mustache.render($('#user_template').html(), {'users': json.data.list});
    			$('#user_info').html(rendered);
				viewUtil.ajaxPagination($new_users, json.data.page);
			};
			_model.post(_model.ajaxHomeUser, $new_users, {'p': pageNo}, fn);
		},
		menuOrder: function() {//菜单排序
			var _model = this.model;
			$('button[name=btnSort]').each(function(index) {
				var $btn = $(this);
				$btn.click(function() {
					var sorts = [];
					$('#menu').find(':input[name=sort]').each(function() {
						var value = ~~$(this).val();
						var id = +$(this).data('id');
						sorts.push([id, value]);
					});
					var fn = function(json) {
						if(comUtil.isJsonSuccess(json)) {
							//成功则刷新页面
							comUtil.setReloadTimeout(1000);
						}
						viewUtil.setError(json.msg, {'id':'prompt'+index, 'container':$btn, 'direction':'next'});
					}
					_model.post($(this).data('url'), $(this), {'orders[]':sorts}, fn);
				});
			});
		}
	});
	return menu;
});
