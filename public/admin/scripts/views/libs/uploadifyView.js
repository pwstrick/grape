define([
	'comUtil',
	'constUtil',
	'dialogView',
	'uploadify'
], function (comUtil, constUtil, dialog) {
	var layer ={};
	/**
	*	已上传的图片列表
	*/
	function container(div, url, imageid, is_crop) {
		var inner = $('<div>');
		inner.append($('<img>', {src:url}));
		if(is_crop === true) {
			inner.append($('<button>',{'class':'btn btn-info', 'type':'button', 'data-id':imageid}).html('裁剪'));
		}
		inner.append($('<button>',{'class':'btn btn-warning', 'type':'button', 'data-id':imageid}).html('删除'));
		div.append(inner);
	};
	
	layer.uploadImages = function(btn) {//设置快速回复编辑框
		var id = "#"+btn;
		var $this = $(id);
		if($this.length == 0) {//不存在
			return;
		}
		var parent =  $this.parent();
		var button =  $this.siblings(':button');//上传按钮
		var div =  $this.siblings('div.uploadify-upload-images');//已上传的图片列表
		var hidden =  $this.siblings('input[type=hidden]');//隐藏域按钮保存image的ID
		var initImageID = $(id).data('ids');
		var initImageUrl = $(id).data('urls');
		var queueSizeLimit = $(id).data('queuelimit');
		//验证参数添加
		var attrs = {type:'hidden', id:btn+'_hidden', name:btn+'_hidden'};
		var hiddenn_attrs = comUtil.getValidateAttrs($(id), attrs);

		var is_crop = $this.data('crop');//是否显示裁剪按钮
		var crop_url = $this.data('cropUrl');//裁剪路径

		//数量限制
		if(queueSizeLimit != undefined) {
			queueSizeLimit = +queueSizeLimit;
		}else {
			queueSizeLimit = 999;
		}
		
		//Firefox不会传session_id
//		var session_name = $this.data('sessionName') || 'SESSION_ID';
//		var session_id = $this.data('sessionId');
//		var formData = {};
//		if(session_id == undefined) {
//			formData[session_name] = session_id;
//		}
		
		var auto = queueSizeLimit==1 ? true : false;
		$this.uploadify({
			'preventCaching': false,
			'queueSizeLimit': queueSizeLimit,
			'auto' : auto,
			'fileObjName': 'upfile',
			'height'        : 30,
			'swf'           : comUtil.scriptsUrl('libs/uploadify/uploadify.swf'),
			'uploader'      : comUtil.absUrl(constUtil.ueditorUpload),
			'width'         : 120,
			'fileSizeLimit' : '2MB',
			'formData' : $this.data(),
			'fileTypeExts' : '*.gif; *.jpg; *.png; *.bmp; *.jpeg',
			'onInit' : function(instance) {
							
							if(button.length == 0) {
								button = $('<button>', {'type':'button', 'class':'btn btn-primary hide'}).html('上传');
								if(div.length == 0)
									parent.append(button);
								else
									button.insertBefore(div);
							}
							button.click(function() {
								$this.uploadify('upload','*');
							});

							if(hidden.length == 0) {
								hidden = $('<input>', hiddenn_attrs);
								parent.append(hidden);
								//hidden.insertAfter(button);
							}

							if(div.length == 0) {
								div = $('<div>', {'class':'uploadify-upload-images'});
								parent.append(div);
							}
							div.delegate(':button.btn-warning', 'click', function() {
									if(!confirm('您确定要删除该图片吗？'))
										return false;
										var has = [];
										var strHas = [];
										if(hidden.val().length > 0) {
											strHas = hidden.val().split(',');
										}
										//强制类型转换为int
										var length = strHas.length;
										for(i = 0; i<length; i++) {
											(function(j){
												has.push(+strHas[j]);
											})(i);
										}
										if(!$(this).attr('data-id')) {
											return;//ID不存在
										}

										var deleteId = +$(this).attr('data-id');//类型转换
										var row = $.inArray(deleteId, has);
										if(row == -1) {
											return;
										}
										has.splice(row, 1);
										hidden.val(has.join(','));
										$(this).parent().remove();
										
							}).delegate(':button.btn-info', 'click', function() {
								var $this = $(this);
								var params = $this.data();
								var attrs = [];
								$.each(params, function(key, value) {
									attrs.push(key+'='+value);
								});
								//裁剪
								var attrs = {
									url: crop_url + '?' + attrs.join('&'),
									title: '图片裁剪',
									padding: 10,
									height: 350,
									width: 850,
									//data: {'id':id},
									onclose: function() {
										var data = this.returnValue;
										if(data === undefined) return;
										if(data.url.length == 0 || data.imageid <= 0) return;
										var oldId = $this.attr('data-id');
										var imageid = data.imageid;
										$this.siblings(':button').attr('data-id', imageid);
										$this.attr('data-id', imageid);
										$this.siblings('img').attr('src', data.url);
										//隐藏域修改
										var ids = hidden.val().split(',');
										var newIds = [];
										$.each(ids, function(key, value) {
											if(oldId == value) {
												newIds.push(imageid);
											}else {
												newIds.push(value);
											}
										});
										hidden.val(newIds.join(','));
									}
								};
								dialog.parent(attrs);
							});

							//如果是修改图片，并且初始化的时候有数据
							if(initImageID == undefined || 
								initImageUrl == undefined ||
								initImageID == '' ||
								initImageUrl == '') {
								return;
							}
							//类型转换为string 防止split报错
							initImageID = initImageID.toString();
							var initImageIDs = initImageID.split(',');
							var initImageUrls = initImageUrl.split(',');
							var idsLength = initImageIDs.length;
							var urlsLength = initImageUrls.length;
							if(idsLength != idsLength) {
								return;
							}
							for(i=0; i<idsLength; i++) {
								(function(j) {
									container(div, initImageUrls[j], initImageIDs[j], is_crop);
								})(i);
							}
							hidden.val(initImageID);
						},
			'onUploadSuccess': function(file, data, response) {
							var image = (0,eval)('(' + data + ')');

							if(image.imageid == 0) {//上传失败
								alert(file.name + '上传失败！' + image.msg)
								return;
							}
							//只有一张的情况
							if(auto) {
								div.html('');
								hidden.val(image.imageid);
								container(div, image.url, image.imageid, is_crop);
								return;
							}

							//新增到隐藏域中
							var has = [];
							var strHas = [];
							if(hidden.val().length > 0) {
								strHas = hidden.val().split(',');
							}
							//强制类型转换为int
							var length = strHas.length;
							for(i = 0; i<length; i++) {
								(function(j){
									has.push(+strHas[j]);
								})(i);
							}

							var intFileValue = +image.imageid;
							//判断是否重复
							if($.inArray(intFileValue, has) >= 0) {
								alert('该图片已上传过！');
								return;
							}

							has.push(intFileValue);
							hidden.val(has.join(','));

							container(div, image.url, image.imageid, is_crop);
						},
			'onSelect': function(file) {
				if(!auto) {
					$(id).siblings(':button').show();
				}
			},
			'onUploadComplete': function(file) {
				$(id).siblings(':button').hide();
			},
			'onCancel': function(file) {
				if ($("#upload_images-queue > div").size() == 1) {
					$(id).siblings(':button').hide();
				}
			}
		});	
	};

	return layer;
});
