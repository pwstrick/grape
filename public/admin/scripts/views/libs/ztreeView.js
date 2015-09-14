define([
	'comUtil',
	'modelUtil',
	'ztree_excheck'
], function (comUtil, modelUtil) {
	var layer ={};
	layer.setCheckbox = function(treeObjId) {//设置带多选框的树形结构
		if(!document.getElementById(treeObjId)) {//不存在
			return;
		}
		var $ul = $('#'+treeObjId);
		//添加隐藏域
		var hidden_id = treeObjId+'_hidden';
		var attrs = {'type':'hidden', 'id':hidden_id, 'name':hidden_id};
		var hidden_attrs = comUtil.getValidateAttrs($ul, attrs);
		$hidden = $('<input>').attr(hidden_attrs);
		//从父级传递过来的数据或者默认值
		var values = [];
		var values_int = [];
		var value = $ul.data('value') || '';
		value = value.toString();
		if(value != '') {
			$hidden.val(value);
			values = value.split(',');//赋默认值 默认是string类型
			$.each(values, function(index, data) {
				values_int.push(~~data);
			});
		}
		$hidden.insertAfter($ul);
				
		//关联父级与子集的属性
		var py = $ul.data('py') || '';//点击勾选 关联父
		var sy = $ul.data('sy') || '';//点击勾选 关联子
		var pn = $ul.data('pn') || '';//取消勾选 关联父
		var sn = $ul.data('sn') || '';//取消勾选 关联子
		var href = $ul.data('ajax');
		var idKey = $ul.data('idKey') || "id";
		var pIdKey = $ul.data('pidKey') || "pId";
		
		var params = $ul.data();
		
		var setting = {
			check: {
				enable: true,
				chkboxType:{"Y":(py+sy), "N":(pn+sn)}
			},
			data: {
				simpleData: {
					enable: true,
					idKey: idKey,
					pIdKey: pIdKey
				},
				key: {
					name: $ul.data('keyName') || "name"
				}
			},
			view: {
				showIcon: false
			},
			callback: {
				onClick: onClick,
				onCheck: onCheck
			}
		};

//		var zNodes =[
//			{id:1, pId:0, name:"随意勾选 1", open:true},
//			{id:11, pId:1, name:"随意勾选 1-1", open:true},
//			{id:111, pId:11, name:"随意勾选 1-1-1"},
//			{id:112, pId:11, name:"随意勾选 1-1-2"},
//			{id:12, pId:1, name:"随意勾选 1-2", open:true},
//			{id:"121", pId:12, name:"随意勾选 1-2-1"},
//			{id:122, pId:12, name:"随意勾选 1-2-2"},
//			{id:2, pId:0, name:"随意勾选 2", open:true},
//			{id:21, pId:2, name:"随意勾选 2-1"},
//			{id:22, pId:2, name:"随意勾选 2-2", open:true},
//			{id:221, pId:22, name:"随意勾选 2-2-1"},
//			{id:222, pId:22, name:"随意勾选 2-2-2"},
//			{id:23, pId:2, name:"随意勾选 2-3"}
//		];
		
		function setHidden(treeObj) {
			if(treeObj === undefined) {
				treeObj = $.fn.zTree.getZTreeObj(treeObjId);
			}
			var nodes = treeObj.getCheckedNodes(true);
			var length = nodes.length;
			if(length == 0) {
				$hidden.val('');
			}else {
				var ids = [];
				for(var i=0; i<length; i++) {
					ids.push(nodes[i][idKey]);
				}
				$hidden.val(ids.join(','));
			}
		}
		/**
		 * 点击文本取消或选中多选框
		 * @param {Object} event
		 * @param {Object} treeId
		 * @param {Object} treeNode
		 * @param {Object} clickFlag
		 */
		function onClick(event, treeId, treeNode, clickFlag) {
			var treeObj = $.fn.zTree.getZTreeObj(treeObjId);
			treeObj.checkNode(treeNode, clickFlag, true);
			setHidden(treeObj);
			//
			//console.log(nodes)
//			if(treeNode.checked) {
//				//选中就添加
//				comUtil.tagAdd($hidden, treeNode[idKey].toString());
//			}else {
//				comUtil.tagRemove($hidden, treeNode[idKey].toString());
//			}
		}

		/**
		 * 选中或取消下拉框的时候触发
		 * @param {Object} event
		 * @param {Object} treeId
		 * @param {Object} treeNode
		 */
		function onCheck(event, treeId, treeNode) {
			setHidden();
//			if(treeNode.checked) {
//				//选中就添加
//				comUtil.tagAdd($hidden, treeNode[idKey].toString());
//			}else {
//				comUtil.tagRemove($hidden, treeNode[idKey].toString());
//			}
		}
		
		
		modelUtil.comPost(href, $ul, params, function(json) {
			//ajax获取到的数据
			var nodes = json.data;
			if(comUtil.isNoData(nodes)) {
				return;
			}
			$.each(nodes, function(index, data) {
				var offset;
				//判断选中
				if(_.isString(data[idKey])) {//返回是string类型
					offset = $.inArray(data[idKey], values);
				}else {
					offset = $.inArray(data[idKey], values_int);
				}

				if(offset >= 0) {
					data.checked = true;
				}
			});

			$.fn.zTree.init($("#"+treeObjId), setting, nodes);
		});
	};

	return layer;
});
