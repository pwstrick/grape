define(['backbone', 'underscore', 'constUtil', 'comUtil', 'modelUtil', 'validateView'],
	function(Backbone, _, constUtil, comUtil, modelUtil, validateView) {
		var list = Backbone.Model.extend({
			url: '',
			defaults: {},
			initialize: function() {
	    	},
			com_validate: function($form, success) {//通用验证规则
				var rule_attrs = constUtil.rules;
				var fields = $form.find(':input[name]').not(':submit');
				var rules = {};
				var messages = {};
	
				$.each(fields, function(index, field) {
					var $field = $(field);
					var datas = $field.data();
					var name = $field.attr('name');
					$.each(datas, function(key, value) {
						//key = key.toLowerCase();
						if($.inArray(key, rule_attrs) > -1) {
							if(rules[name] == undefined) {
								rules[name] = {};
								messages[name] = {};
							}
							rules[name][key] = value;
							var message = datas[key+'Message'];
							messages[name][key] = message || '';
						}
					});
				});

				validateView.common($form.attr('id'), rules, messages, success);
	//			console.log(rules);
	//			console.log(messages);
			},
			post: function(url, btn, input, fn) {//post通用函数
				return modelUtil.comPost(url, btn, input, fn);
			}
	});
	return list;
});
