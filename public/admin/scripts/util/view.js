define(['comUtil'], function(comUtil) {
	var viewUtil = {
		model:[],
		errorSelector: '',
		modelIndex: -1,
		setCurrentModel: function(ownModel) {//设置当前的model索引
			this.modelIndex = $.inArray(ownModel, this.model);
		},
		getSelectedModel: function() {//根据索引获取model
			var _model = this.model;
			if($.isArray(this.model)) {//判断model是否是array
				if(this.modelIndex == -1 || this.modelIndex >= this.model.length) {
					_model = this.model[this.model.length - 1];
				}else {
					_model = this.model[this.modelIndex];
				}
			}
			return _model;
		},
		isError: function(method) {
		  	var selector = this._getErrorSelector();
			$(selector).html('&nbsp;');
			var _model = this.getSelectedModel();
			var validate = _model[method].apply(_model, [].slice.call(arguments, 1));
			if(validate) {
				$(selector).html(validate).show();
				return false;
			}
			//$(this.errorSelector).html($(this.errorSelector).attr('title'));
			return true;
		},
		/**
		 * 设置提示信息
		 * @param msg
		 * @param others {id:'xx', direction:'next', container:$btn}
		 */
		setError: function(msg, others) {
			var selector = $(this._getErrorSelector());
			
			//others是其他属性
			if(others != undefined && selector.length == 0) {
				if(others.id != undefined) {
					this.getErrorDiv(msg, others);
					return;
				}
			}
			return selector.show().html(msg);
		},
		getErrorDiv: function(msg, others) {//拼接错误信息标签
			var id = others.id;
			var direction = others.direction;
			$container = others.container != undefined ? others.container : $('body');
			var $div = $('<div>').addClass('alert alert-error');
			if(id != undefined && id.length > 0) {
				$old = $('#'+id);
				if($old.length > 0) {
					$old.html(msg);
					return $old;
				}
				$div.attr('id', id);
			}
			$div.html(msg);
			switch(direction) {//插入前后或内
				case 'prev':
					$container.before($div);
					break;
				case 'next':
					$container.after($div);
					break;
				default:
					$container.append($div);
					break;
			}
			return $div;
		},
		clearError: function(msg) {
			return $(this._getErrorSelector()).hide().html('');
		},
		_getErrorSelector: function() {//默认dom对象
			return this.errorSelector || '#prompt';
		},
		placeholder: function() {//IE浏览器实现placeholder属性
				if($.browser.msie == false || $.browser.version.slice(0,3) >= 10) {
					return;
				}
		    $('input[placeholder]:not(:password)').each(function() {
		        var input = $(this);
		        $(input).val(input.attr('placeholder'));
		        $(input).focus(function(){
		             if (input.val() == input.attr('placeholder')) {
		                 input.val('');
		             }
		        });
		        $(input).blur(function(){
		            if (input.val() == '' || input.val() == input.attr('placeholder')) {
		                input.val(input.attr('placeholder'));
		            }
		        });
		    });
		},
		selectAll: function() {//表格中选中全部
			$("span.icon input:checkbox, th input:checkbox").click(function() {
				var checkedStatus = this.checked;
				var checkbox = $(this).parents('.widget-box').find('tr td:first-child input:checkbox');
				checkbox.each(function() {
					this.checked = checkedStatus;
					if (checkedStatus == this.checked) {
						$(this).closest('.checker > span').removeClass('checked');
					}
					if (this.checked) {
						$(this).closest('.checker > span').addClass('checked');
					}
				});
			});
		},
		enterClick: function(input, btn) {//回车提交
			$(input).keydown(function(e){
				if(e.keyCode==13){
				   $(btn).click(); //处理事件
				}
			});
		},
		tagRemove: function($hidden, search, $this) {//字符串分割，删除一条记录
			comUtil.tagRemove($hidden, search, $this);
		},
		tagAdd: function($hidden, search) {//字符串分割，新增一条记录
			comUtil.tagAdd($hidden, search);
		},
		callback: function(json) {//form提交的回调
			if(comUtil.isJsonSuccess(json)) {
    			if(!json.data) {
    				history.go(-1);//跳转到讲师详情
    			}else {
    				location.href = json.data.url;
    			}
			}
			this.setError(json.msg);
		},
		noListDiv: function() {//列表页面无list信息的时候返回
			return '<div class="alert alert-info alert-nolist"><h4 class="alert-heading">暂无数据</h4></div>';
		},
		ajaxPagination: function($container, page) {//ajax获取分页信息
			if(comUtil.isNoData(page)) {
				$container.append(this.noListDiv());
				return;
			}
			var $div = $('<div>').addClass('pagination alternate');
			$div.append('<span>共' + page.total + '条信息</span>');
			var $ul = $('<ul>');
			var $last = $('<li>');
			var $next = $('<li>');
			
			if(page.last == 0) {
				$last.addClass('disabled');
			}
			$last.append('<a href="javascript:void(0)" data-page="'+page.last+'">上一页</a>');
			$ul.append($last)
			
			$.each(page.numbers, function(index, value) {
				var $digit = $('<li>');
				if(value == page.current) {
					$digit.addClass('active');
				}
				$digit.append('<a href="javascript:void(0)" data-page="'+value+'">'+value+'</a>');
				$ul.append($digit);
			});
			
			if(page.next == 0) {
				$next.addClass('disabled');
			}
			$next.append('<a href="javascript:void(0)" data-page="'+page.next+'">下一页</a>');
			$ul.append($next)
			$div.append($ul);
			
			$container.find('.pagination').remove().end().append($div);
			return $div;
		},
		topiFrame: function($btn, dialogView, closeFn) {//通用iframe弹出层操作
			var height = $btn.data('height') || 200;
			var width = $btn.data('width') || 400;
			var scrolling = $btn.data('scrolling');
			if(scrolling === true)
				scrolling = 'yes';
			else
				scrolling = 'no';
			var value = $btn.attr('data-value');
			var params = $btn.data();
			params['value'] = value;
			var attrs = {
				url: $btn.data('href'),
				title: $btn.data('title'),
				padding: 10,
				height: height,
				width: width,
				scrolling: scrolling,
				data: params,
				onclose: function() {
					//console.log(this.returnValue)
					var data = this.returnValue.data;
					if(data === undefined) return;
					//在隐藏域中新增一个value值
					closeFn.call(this, data);
				}
			};
			dialogView.parent(attrs);
		}
	};
	return viewUtil;
});
