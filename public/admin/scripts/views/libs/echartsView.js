define([
	'echarts'
], function (ec) {
	var chart ={};

	chart.line = function(id, options) {//折线图显示
			require(['echarts/chart/line'],
				function () {
						var myChart = ec.init(document.getElementById(id));
						var defaults = {
							toolbox: {
								show : true,
								feature : {
									mark : {show: true},
									dataView : {show: true, readOnly: false},
									magicType : {show: true, type: ['line']},
									restore : {show: true},
									saveAsImage : {show: true}
								}
							},
							calculable : true
						};
						options = $.extend(defaults, options);
						myChart.setOption(options);
				}
			);
	};

	return chart;
});
