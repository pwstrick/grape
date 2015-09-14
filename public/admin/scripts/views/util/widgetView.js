define([
    'jquery'
], function ($) {
	var widget = {
		menuCollapse: function(table_id) {//菜单伸缩
			//初始化
			$('#'+table_id).find('tbody>tr').each(function() {
				//查找子集
				var _this = $(this);
				var row = _this.data('row');
				var depth = +_this.data('depth');
				for(var i=depth-1; i>=0; i--) {
					var $prev = _this.prevAll('tr[data-depth='+i+']').eq(0); 
					$prev.attr('data-group', row);
				}
			});
			$('#'+table_id).find('tbody>tr').delegate('td:first>i', 'click', function() {
				var $this = $(this)
				var $tr = $(this).parent().parent();
				var row = $tr.data('row');
				var group = $tr.data('group');
				if(row == group) {
					return;
				}
				row = ~~row;
				group = ~~group;
				
				if($this.hasClass('icon-caret-down')) {
					//折叠
					$this.removeClass('icon-caret-down').addClass('icon-caret-right');
					for(i=row+1; i<=group; i++) {
						$tr.siblings('[data-row='+i+']').hide();
					}
				}else if($this.hasClass('icon-caret-right')){
					//显示
					$this.removeClass('icon-caret-right').addClass('icon-caret-down');
					for(i=row+1; i<=group; i++) {
						var $brother = $tr.siblings('[data-row='+i+']');
						var $i = $brother.find('td:first>i');
						$brother.show();
						if($i.hasClass('icon-caret-down')) {
							continue;
						}
						
						//跳过已经折叠的菜单
						var i_row = ~~$brother.data('row');
						var i_group = ~~$brother.data('group');
						var minus = i_group - i_row;
						if(minus <= 0) {
							continue;
						}
						i += minus;
					}
				}
			});
		}
	}
	return widget;
});