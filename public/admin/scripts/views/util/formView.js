$(document).ready(function() {
	$('select:not([data-ajax-change=true])').select2();
	$('input[type=checkbox],input[type=radio],input[type=file]:not(.uploadify-file)').uniform();
	
	if($('select[name=province]').length > 0) {
		//市与区的联动
		//$('select[name=province]').change(function() {
			//alert(23)
//			$.post('/widget/city', {'city':$(this).val()}, function(data) {
//				var json = (0,eval)('('+data+')');
//				var $district = $('select[name=district]');
//				var $first = $district.find('option:not(:first)');
//				$first.remove();
//				$district.prev().find('.select2-chosen').text($district.find('option:first').text());
//				if(json.data == null || json.data.length == 0) {
//					return;
//				}
//				$.each(json.data, function(key, value) {
//					$district.append('<option value="'+key+'">'+value+'</option>');
//				});
//			});
		//});
	}
});
