 define([
 	'jquery',
 	'modelUtil'
 ], function ($, modelUtil) {
 	var select2 ={};
 	select2.citys = function() {//市与区的联动
 		if($('select[name=city]').length == 0) {
 			return;
 		}

		$('select[name=city]').on('change', function() {
//			var fn = function(json) {
//				var $district = $('select[name=district]');
//				var $first = $district.find('option:not(:first)');
//				$first.remove();
//				if(json.data == null || json.data.length == 0) {
//					$district.prev().find('.select2-chosen').text($district.find('option:first').text());
//					return;
//				}
//				$.each(json.data, function(key, value) {
//					$district.append('<option value="'+key+'">'+value+'</option>');
//				});
//			};
//			
//			modelUtil.comPost('/widget/city', $(this), {'city':$(this).val()}, fn);
		});
 	};

 	return select2;
 });
