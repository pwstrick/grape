define(['backbone', 'underscore', 'constUtil', 'comUtil', 'modelUtil', 'validateView'],
	function(Backbone, _, constUtil, comUtil, modelUtil, validateView) {
		var list = Backbone.Model.extend({
			url: '',
			defaults: {},
			initialize: function() {
	
			},
			isSuccess: function(json) {
				return comUtil.isJsonSuccess(json);
			},
			teacher_edit_validate: function(success) {
		    	//医院资料修改表单验证
		    	var rules = {
		    		upload_images_hidden:{
			    		required:true
			    	},
					name:{
						required:true,
						rangelength:[2,10]
					},
					introduce:{
						required:true,
						rangelength:[10,500]
					},
					department:{
						valueNotEquals:0
					},
					job:{
						valueNotEquals:0
					},
					auth:{
						required:true
					}
				};
		    	var messages = {
		    		upload_images_hidden:{
		    			required:'请上传头像'	
		    		},
		    		name:{
		    			required:'请输入姓名',
		    			rangelength:'姓名长度最少输入2个字，最多输入10个字'
		    		},
		    		introduce:{
		    			required:'请输入讲师简介',
		    			rangelength:'讲师简介最少输入10个字符，最多输入500个字'
		    		},
					department:{
						valueNotEquals:'请选择正确的科室'
					},
					job:{
						valueNotEquals:'请选择正确的职务'
					},
					auth:{
						required:'请选择母乳师认证'
					}
		    	};
		    	
		    	validateView.common('teacher_edit', rules, messages, success);
		    },
		    course_edit_validate: function(success) {
		    	
		    	var rules = {
		    		upload_images_hidden:{
				    	required:true
				    },
					name:{
						required:true,
						rangelength:[2,18]
					},
					teacher: {
						valueNotEquals:0
					},
					classroom: {
						valueNotEquals:0
					},
					can_appointment_num: {
						required:true,
						number:true
					},
					from: {
						isTime:true
					},
					end: {
						isTime:true
					},
					introduce:{
						required:true,
						rangelength:[10,100]
					},
					'pregnancy[]':{
						required:true
					},
					timstamp:{
						no_repeate:true
					},
					can_appointment_num:{
						min:1
					}
				};
			    var messages = {
			    	upload_images_hidden:{
					    required:'请上传封面图片'
					},
			    	name:{
			    		required:'请输入课程名称',
			    		rangelength:'课程名称长度最少输入2个字，最多输入18个字'
			    	},
			    	teacher:{
			    		valueNotEquals:'请在下拉框中指定讲师'
			    	},
			    	classroom: {
			    		valueNotEquals:'请在下拉框中指定教室'
			    	},
			    	can_appointment_num: {
			    		required:'请输入可预约人数',
						number:'请输入正确的数字'
			    	},
			    	from: {
			    		isTime:'请输入正确的开始时间'
					},
					end: {
						isTime:'请输入正确的结束时间'
					},
			    	introduce:{
			    		required:'请输入课程简介',
			    		rangelength:'课程简介最少输入10个字符，最多输入100个字'
			    	},
					'pregnancy[]':{
						required:'请选择匹配孕期'
					},
					timstamp:{
						no_repeate:'请输入具体日期'
					},
					can_appointment_num:{
						min:'不能输入0或负数'
					}
			    };
		    	validateView.common('course_edit', rules, messages, success);
		    },
			post: function(url, btn, input, fn) {//post通用函数
				return modelUtil.comPost(url, btn, input, fn);
			}
	});
	return list;
});
