define(['backbone', 'underscore', 'constUtil', 'comUtil', 'modelUtil', 'validateView'],
	function(Backbone, _, constUtil, comUtil, modelUtil, validateView) {
		var list = Backbone.Model.extend({
			url: '',
			hospital_info_url: comUtil.absUrl(constUtil.hospitalInfo),
			addtag_url: comUtil.absUrl(constUtil.hospitalAddtag),
			defaults: {},
			initialize: function() {
	
			},
			isSuccess: function(json) {
				return comUtil.isJsonSuccess(json);
			},
		    history_edit_validate: function(success) {
		    	//医院资料修改表单验证
		    	var rules = {
		    		upload_images_hidden:{
		    			required:true
		    		},
					name:{
						required:true
					},
					address:{
						required:true
					},
					introduce:{
						required:true,
						minlength:10,
						maxlength:500
					},
					city:{
						valueNotEquals:0
					},
					district:{
						valueNotEquals:0
					},
					classrooms_hidden:{
						required:true
					},
					departments_hidden:{
						required:true
					},
					jobs_hidden:{
						required:true
					}
				};
		    	var messages = {
		    		upload_images_hidden:{
		    			required:'请上传医院封面图'
		    		},
		    		name:{
		    			required:'请输入医院名称'
		    		},
		    		address:{
		    			required:'请输入医院地址'
		    		},
		    		introduce:{
		    			required:'请输入医院简介',
		    			minlength:'至少输入10个字',
		    			maxlength:'最多输入500个字'
		    		},
		    		city:{
		    			valueNotEquals:'请选择城市'
		    		},
		    		district:{
		    			valueNotEquals:'请选择区域'
		    		},
		    		classrooms_hidden:{
						required:'请填写上课教室'
					},
					departments_hidden:{
						required:'请填写医院科室'
					},
					jobs_hidden:{
						required:'请填写医生职务'
					}
		    	};
		    	
		    	validateView.common('history_edit', rules, messages, success);
		    },
		    history_addtag_validate: function(success) {
		    	var rules = {
					name:{
						maxlength:8,
						required:true
					}
				};
			    var messages = {
			    	name:{
			    		maxlength:'名称不能超过8个字',
			    		required:'请输入名称'
			    	}
			    };
			    validateView.common('history_addtag', rules, messages, success);
		    },
		    hospital_pwd_validate: function(success) {
		    	//医院资料修改表单验证
		    	var rules = {
		    		pwd_now:{
						required:true,
		                minlength: 6,
		                maxlength: 15
					},
					pwd_new:{
						required:true,
		                minlength: 6,
		                maxlength: 15
					},
					pwd_success:{
						required:true,
		                minlength: 6,
		                equalTo: "#pwd_new",
		                maxlength: 15
					}
				};
		    	var messages = {
		    		pwd_now:{
		    			required:'请输入当前密码',
		    			minlength:'请输入6位数以上的密码',
		    			maxlength:'密码不能超过15位'
		    		},
		    		pwd_new:{
		    			required:'请输入新密码',
		    			minlength:'请输入6位数以上的密码',
		    			maxlength:'密码不能超过15位'
		    		},
		    		pwd_success:{
		    			required:'请输入确认密码',
		    			minlength:'请输入6位数以上的密码',
		    			maxlength:'密码不能超过15位',
		    			equalTo:'确认密码输入不一致'
		    		}
		    	};
		    	
		    	validateView.common('hospital_pwd', rules, messages, success);
		    },
			post: function(url, btn, input, fn) {//post通用函数
				return modelUtil.comPost(url, btn, input, fn);
			}
	});
	return list;
});
