define([
	'comUtil',
	'constUtil',
	'ueditor'
], function (comUtil, constUtil) {
	var layer ={};
	layer.setUeditor = function(txtId) {//设置编辑
		if(!document.getElementById(txtId)) {//不存在
			return;
		}
		var editor = UE.getEditor(txtId, {
			toolbars: [[
	            'fullscreen', 'source', '|', 'undo', 'redo', '|',
	            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
	            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
	            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
	            'directionalityltr', 'directionalityrtl', 'indent', '|',
	            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
	            'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
	            'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
	            'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
	            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
	            'print', 'preview', 'searchreplace', 'help', 'drafts'
	        ]],
			serverUrl: comUtil.absUrl(constUtil.ueditorUpload), //图片上传提交地址
			enableAutoSave: false,
			autoSyncData: false,
			autoHeightEnabled: false,
			lang:"zh-cn"
			//imagePath: ''
		});

		//用多选框做初始化的
		editor.ready(function(){
		    //$('#' + shellId + ' #edui1_toolbarbox').css('display','none');
		    //editor.fireEvent("contentChange");
		    
			var $old = $('textarea[name='+txtId+']');
		    var $textarea = $old.parent().find('iframe').contents();
		    
			/**
			 * 监听编辑器内容变化
			 */
			editor.addListener("contentChange",function(){
			    fn();
			});
		    var fn = function() {
		    	$old.val(editor.getContent());
		    }
		
		    if (document.all) {
		        $textarea.get(0).attachEvent('onpropertychange',function(e) {            
		            fn();
		        });
		    }else{
		        $textarea.on('input',fn);
		    }
		});
		return editor;
	};

	return layer;
});
