define([
    'jquery',
    'dialogView',
    'modelUtil',
    'viewUtil',
    'domReady',
	'gritter',
	'bootstrap_typeahead',
	'bootstrap_dropdown',
	'scrollLoading'
], function ($, dialogView, modelUtil, viewUtil, domReady) {
	var shortcut = {
		allNeed: function() {//都需要的方法
			//this.equalHeight();
			this.setGritter();
			this.sidebarNavigation();
			//this.searchTypeahead();
			viewUtil.placeholder();
			this.asyn();
			//this.editGoBack();
			this.formchange();
			domReady(function () {
				$('.mask_layer').remove();
			});
		},
		formchange: function() {
			var _this = this;
			//HTML5事件 oninput
			$('form[data-type=form]').on('input propertychange', function() {
				_this.editGoBack();
			}).delegate('input:hidden', 'change', function() {
				
			});
		},
		editGoBack: function() {//编辑页面返回上一页
			$edit_go_back = $('a[data-type=edit_go_back]');
			if($edit_go_back.length == 0) {
				return;
			}
			var success = $._data($edit_go_back[0], 'events');//判断是否已经绑定了事件
			if(success === undefined) {
				$edit_go_back.click(function() {
					var $this = $(this);
					var okFun = function() {
						var href = $this.attr('href');
						location.href = href;
					}
					dialogView.confirm('确认离开当前页面吗？未保存的数据将会丢失！', okFun);
					return false;
				});
			}
		},
		asyn: function() {
			$('.asyn').scrollLoading();//异步加载图片
		},
		setGritter: function() {//浮动层
//				$.gritter.add({
//						title:	'Important Unread messages',
//						text:	'You have 12 unread messages.',
//						image: 	'/sites/images/demo/envelope.png',
//						sticky: false
//				});
//				$('#gritter-notify .normal').click(function(){
//						$.gritter.add({
//							title:	'Normal notification',
//							text:	'This is a normal notification',
//							sticky: false
//						});
//				});
//
//				$('#gritter-notify .sticky').click(function(){
//						$.gritter.add({
//							title:	'Sticky notification',
//							text:	'This is a sticky notification',
//							sticky: true
//						});
//				});
//
//				$('#gritter-notify .image').click(function(){
//						var imgsrc = $(this).attr('data-image');
//						$.gritter.add({
//							title:	'Important Unread messages',
//							text:	'You have 12 unread messages.',
//							image: imgsrc,
//							sticky: false
//						});
//				});
		},
		equalHeight: function(target) {
			// find the tallest height in the collection
			// that was passed in (.column)
			target = target || $('.column');
			tallest = 0;
		    target.each(function(){
			    thisHeight = $(this).height();
							//get padding
							//bottom = $(this).css('paddingBottom');
							//bottom = parseInt($(this).css('paddingBottom'), 10)
			    if( thisHeight > tallest)
			       tallest = thisHeight + 40;
		    });

		    // set each items height to use the tallest value found
		    target.each(function(){
		    	$(this).height(tallest);
		    });
		},
		sidebarNavigation: function() {//侧边栏导航特效
			$('.submenu > a').click(function(e) {
				e.preventDefault();
				var submenu = $(this).siblings('ul');
				var li = $(this).parents('li');
				var submenus = $('#sidebar li.submenu ul');
				var submenus_parents = $('#sidebar li.submenu');
				if(li.hasClass('open')) {
					if(($(window).width() > 768) || ($(window).width() < 479)) {
						submenu.slideUp();
					} else {
						submenu.fadeOut(250);
					}
					li.removeClass('open');
				}else {
					if(($(window).width() > 768) || ($(window).width() < 479)) {
						submenus.slideUp();
						submenu.slideDown();
					} else {
						submenus.fadeOut(250);
						submenu.fadeIn(250);
					}
					submenus_parents.removeClass('open');
					li.addClass('open');
				}
			});
			$('.submenu > a').each(function() {
				//计算未读数
				var $label = $(this).find('.label');
				var total;
				if($label.length == 0) {
					total = 0;
				}else {
					total = ~~$.trim($label.html());//类型转换
				}
				$(this).next('ul').find('li>a').each(function() {
					$a_label = $(this).find('.label');
					if($a_label.length > 0) {
						num = ~~$.trim($a_label.html());
						total += num;
					}
				});
				if(total > 0) {
					$(this).append('<span class="label label-important">'+total+'</span>')
				}
			});
			/*var ul = $('#sidebar > ul');
			$('#sidebar > a').click(function(e) {
				e.preventDefault();
				var sidebar = $('#sidebar');
				if(sidebar.hasClass('open')) {
					sidebar.removeClass('open');
					ul.slideUp(250);
				}else {
					sidebar.addClass('open');
					ul.slideDown(250);
				}
			});*/
		},
		searchTypeahead: function() {//搜索框自动补全
			$('#search input[type=text]').typeahead({
					source: [
						{name:'Dashboard'},
						{name:'Form elements'},
						{name:'Common Elements'},
						{name:'Validation'},
						{name:'Wizard'},
						{name:'Buttons'},
						{name:'Icons'},
						{name:'Interface elements'},
						{name:'Support'},
						{name:'Calendar'},
						{name:'Gallery'}
					],
					items: 4,
					display: 'name'
			});
		}
	};
	return shortcut;
});
