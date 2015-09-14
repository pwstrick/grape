define(['spin', 'constUtil'], function(Spinner, constUtil) {
	var comUtil = {
		absUrl : function(url) { //绝对URL
			return constUtil.domain + url;
		},
		adminUrl : function(url) { //绝对URL
			return constUtil.domain_admin + url;
		},
		scriptsUrl : function(url) {
			return constUtil.domain_scripts + url;
		},
		isJsonSuccess: function(json) {
			return +json.result === constUtil.resultSuccess && json.result !== null;//类型转换做判断
		},
		isJsonFailure: function(json) {
			return +json.result === constUtil.resultFailure;
		},
		isRelogin: function(json) {
			return json.code === constUtil.apiCodeKeyRelogin;
		},
		isNoData: function(data) {//返回值是否存在
			return data == null || data == '';
		},
		redirectLogin: function() {
			location.href = this.absUrl(constUtil.webLogin);
		},
		redirectLastUrl: function() {
			var url = $.cookie(constUtil.lastUrl);
			url = !url ? constUtil.domain : url;
			location.href = url;
		},
		evalStrJson: function(json) { //str转换为json
			if(!json) return {};
			var data = (0,eval)('(' + json + ')');
			return data;
		},
		setReloadTimeout: function(millisec, url) { //等待几秒后刷新页面
			var hasInput = !!millisec;
			setTimeout(function() {
				if(!url) {location.reload();}else{location.href = url;}
			}, hasInput ? millisec : 1000);
		},
		setMyTimeout: function(millisec, fun, context) { //等待几秒后执行函数
			var hasInput = !!millisec;
			var args = [].slice.call(arguments, 3);
			setTimeout(function() {
				if(typeof fun == 'function') {
					fun.apply(context, args);
				}
			}, hasInput ? millisec : 1000);
		},
		showJsonPrompt: function(json, fn, context) {//通用JSON返回格式操作
			//移除ajax遮罩层
			this.stopAjaxLoading();
			var data;
			if(!json) {
				//返回空字符串的情况 返回空格式
				data = {"msg":"","code":"","result":1};
			}else if(typeof json == 'string')
				data = (0,eval)('(' + json + ')');
			else
				data = json;
			//json是否跳转到登录页面
			if(data.result == 2) {
				this.redirectLogin();
				return function(){};//因为需要回调
			}
			var params = [data];
			return function() {
				//console.log(123);
				params = params.concat([].slice.call(arguments, 0));
				// if(+data.result === constUtil.resultSuccess && !!fnSuccess) {
					// fnSuccess.apply(context, params);
				// }
				// if(+data.result === constUtil.resultFailure && !!fnFailure) {
					// fnSuccess.apply(context, params);
				// }
				if(fn) {
					fn.apply(context, params);
				}
			};
		},
		showAjaxLoading: function(btn) {
			if(btn == null || btn == undefined || $(btn).length == 0) return;
			var left = $(btn).offset().left;
			var top = $(btn).offset().top;
			var width = $(btn).outerWidth();
			var height = $(btn).outerHeight();
			var opts = {
				  lines: 9, // The number of lines to draw
				  length: 0, // The length of each line
				  width: 10, // The line thickness
				  radius: 15, // The radius of the inner circle
				  corners: 1, // Corner roundness (0..1)
				  rotate: 0, // The rotation offset
				  direction: 1, // 1: clockwise, -1: counterclockwise
				  color: '#000', // #rgb or #rrggbb or array of colors
				  speed: 1, // Rounds per second
				  trail: 81, // Afterglow percentage
				  shadow: false, // Whether to render a shadow
				  hwaccel: false, // Whether to use hardware acceleration
				  className: 'spinner', // The CSS class to assign to the spinner
				  zIndex: 2e9, // The z-index (defaults to 2000000000)
				  top: '50%', // Top position relative to parent
				  left: '50%' // Left position relative to parent
			};
			//原先的菊花载入效果只能定义一个，现在要定义多个
			var length = $('[id^=ajax_spin]').length;
			var ajax_spin = 'ajax_spin' + length;
			var ajax_inner = 'ajax_inner' + length;
			$('#'+ajax_spin).remove();
			$('body').append('<div id="'+ajax_spin+'" style="position:absolute;background:#FFF;filter:alpha(opacity=30);opacity:0.3"><div id="'+ajax_inner+'" style="position:relative;height:50px;"></div></div>');
			$('#'+ajax_spin).css({'top':top, 'left': left, 'width': width, 'height':height});
			var target = document.getElementById(ajax_inner);
			var spinner = new Spinner(opts).spin(target);
			//return spinner;
		},
		stopAjaxLoading: function() {
			$('[id^=ajax_spin]').remove();
			//$('#ajax_spin').remove();
			//new Spinner(opts).spin(target)
			//spinner.stop();
		},
		/**
		 * 获取某个控件中的验证参数
		 * @param  $object 需要被转换的特殊对象
		 * @param  exist_attrs 已经有的属性 JSON格式{id:123}
		 */
		getValidateAttrs: function($object, exist_attrs) {
			var validate_attrs = {};
			var rule_attrs = constUtil.rules;
			//因为jquery会把data中的属性都变为小写 对于驼峰命名格式的就要做映射转换
			var rule_attrs_hash = constUtil.rules_hash;
			var datas = $object.data();
			$.each(datas, function(key, value) {
				if($.inArray(key, rule_attrs) > -1) {
					key = rule_attrs_hash[key] || key;
					validate_attrs['data-'+key] = value;
					validate_attrs['data-'+key+'-message'] = datas[key+'Message'];;
				}
			});
			if(exist_attrs != undefined)
				$.extend(validate_attrs, exist_attrs);
			return validate_attrs;
		},
		tagRemove: function($hidden, search, $this) {//字符串分割，删除一条记录
			var hidden = $hidden.val();
			var hiddens = hidden.split(',');
			var row = $.inArray(search, hiddens);//强类型
			hiddens.splice(row, 1);
			$hidden.val(hiddens.join(','));
			if($this != undefined)
				$this.remove();
		},
		tagAdd: function($hidden, search) {//字符串分割，新增一条记录
			var hidden = $hidden.val();
			if(hidden.length == 0)
				hiddens = [];
			else
				hiddens = hidden.split(',');
			hiddens.push(search);
			$hidden.val(hiddens.join(','));
		}
	};
	return comUtil;
});
