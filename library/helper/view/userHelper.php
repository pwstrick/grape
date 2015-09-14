<?php

/**
 * lists页面的view代码
 */
function lists_view($filter, $email_value, $begin_value, $rows, $page_html) {
	//目前是写死的，到时候应该是在controller中将逻辑写好，在view层与页面控件结合
	$group1 = matrix_a_btn(array('href'=>'#', 'text'=>'未完成(100)'), 'info', '');
	$group2 = matrix_a_btn(array('href'=>'#', 'text'=>'已完成(2)'), 'info', '');
	$group3 = matrix_a_btn(array('href'=>'#', 'text'=>'已退货(21)'), 'info', 'active');
	$btn_groups = array(
		array($group1, $group2, $group3)
	);
	
	//超链接按钮
	$buttons = array(
		matrix_a_btn(array('href'=>base_url('user/add'), 'text'=>'新建用户'))
	);
	
	//过滤条件
	$options = array(0=>'包含', 1=>'等于', 2=>'开头', 3=>'完与');
	$select = form_dropdown('filter', $options, $filter);//单选下拉框
	$select = matrix_select($select, true);
	
	$email_attr = quick_input_attr('email', 'span3', '请输入邮箱');
	$email = form_input($email_attr, $email_value);
	
	$begin_attr = quick_input_attr('begin', 'span2', '开始注册时间');
	$begin = my97DatePicker($begin_attr, $begin_value);
	
	$inputs = array(
		array($select, $email),
		array($begin)	
	);
	
	//table列表
	//设置表头 排序的icon可以根据逻辑做修改 这里是写死的
	$ths = array(
		table_th(form_checkbox(), '5%'),
		table_th(form_a(array('href'=>'#'), '<i class="icon-sort"></i>邮箱'), '35%'),
		table_th(form_a(array('href'=>'#'), '<i class="icon-caret-down"></i>角色'), '20%'),
		table_th('上次登录时间', '20%'),
		table_th('操作', '20%')
	);
	$trs = array();
	foreach ($rows as $key=>$row) {
		$a1 = form_a(array('class'=>'mr5', 'href'=>base_url('user/add?id='.$row['id'])), '查看');
		$attrs = array(
			'data-id'=>$row['id'],
			'data-name'=>"xx",
			'class'=>'mr5',
			'data-href'=>base_url('user/add'),
			'data-prompt'=>'您确定这个订单吗？'
		);
		$a2 = table_a_btn($attrs, '删除');
		$tds = array(
			table_format_td(form_checkbox()),
			table_format_td($row['email']),
			table_format_td(matrix_badge(array('text'=>$row['role']))),
			table_format_td($row['time']),
			table_format_td($a1.$a2),
		);
		$trs[] = table_format_tr($tds, array('data-sort'=>$key, 'data-id'=>$row['id']));
	}
	$table_attrs = array('data-type'=>'dragsort', 'data-ajax'=>'ajax/sort.php', 'data-td'=>$row['id']);
	$table = table_format($ths, $trs, $page_html, $table_attrs);
	return query_form($inputs, $buttons, $btn_groups).$table;
}

/**
 * add添加页面的view
 */
function add_view($form_token) {
	$forms = array();
	
	$pwd = form_password(array('class'=>'span4', 'name'=>'pwd', 'id'=>'pwd'));
	$pwd = form_format_widget('密码', $pwd, true, '不能输入纯数字');
	
	$repwd = form_password(array(
			'class'=>'span4', 'name'=>'repwd', 
			'data-confirm-to'=>'pwd', 'data-confirm-to-message'=>'密码确认不正确'));
	$repwd = form_format_widget('确认密码', $repwd);
	
	/*
	 * 自定义按钮
	 */
	$custom_btn = matrix_custom_btn('取消', array('data-prompt'=>'您确定取消吗？', 'data-ajax'=>'ajax/operate.php'));
	$custom_btn = form_format_widget('', $custom_btn);
	
	$field_china = form_format_field_widget('国家', '中国');
	
	/*
	 * radio与checkbox
	 */
	$checkboxs[] = matrix_checkbox_inline(array('name'=>'join[]'), 1, '软妹子');
	$checkboxs[] = matrix_checkbox_inline(array('name'=>'join[]'), 2, '红宝石', true);
	$checkboxs[] = matrix_checkbox_inline(array('name'=>'join[]'), 3, '做一个春梦');
	$checkboxs = implode('', $checkboxs);
	$checkboxs = form_format_input_widget('搭配', $checkboxs);
	
	/*
	 * 上传插件
	 */
	$cover = uploadify(array('id'=>'cover'));
	$cover = form_format_widget('封面', $cover);
	
	/*
	 * 弹出层
	 */
	$category_attrs = array(
		'id'=>'selectCategory',
		'data-href'=>base_url('user/layer?math=Math.random()'),
		'data-title'=>'选择品类',
		'data-height'=>'400',
		'data-scrolling'=>'true'
	);
	$category = matrix_btn('选择品类', $category_attrs, 'warning');
	$category = form_format_widget('类别', $category);
	
	$widget1 = array($pwd, $repwd, $custom_btn, $field_china, $checkboxs, $cover, $category);//控件数组
	$forms[] = form_detail_container($widget1, '主要内容');
	
	/*
	 * 富文本编辑器
	*/
	$ueditor = ueditor(array('id'=>'txtContent', 'name'=>'txtContent', 'style'=>'height:250px;width:100%'));
	$ueditor = form_format_widget('编辑器', $ueditor);
	$widget2 = array($ueditor);
	$forms[] = form_detail_container($widget2, '编辑器');
	
	$forms[] = form_actions(form_success_button());//提交按钮
	return implode('', $forms);
}

/**
 * layer弹出层页面view
 */
function layer_view() {
	
}