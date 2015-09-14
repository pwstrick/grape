define([
	'bootstrap_tooltip'
], function () {
	var layer ={};
	layer.tooltip = function() {//表单特效
		$('.tip').tooltip();
		$('.tip-left').tooltip({ placement: 'left' });
		$('.tip-right').tooltip({ placement: 'right' });
		$('.tip-top').tooltip({ placement: 'top' });
		$('.tip-bottom').tooltip({ placement: 'bottom' });
	};

	return layer;
});
