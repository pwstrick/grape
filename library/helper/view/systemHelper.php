<?php

/**
 * adminpwd页面的view代码
 */
function adminpwd_view() {
	$forms = array();
	
	$old_attr = array(
		'class'=>'span4',
		'name'=>'old_pwd',
		'data-required'=>'true',
		'data-required-message'=>'请输入旧密码'
	);
	$old = form_password($old_attr);
	$old = form_format_widget('旧密码', $old, true);
	
	$new_attr = array(
		'class'=>'span4',
		'name'=>'new_pwd',
		'data-required'=>'true',
		'data-required-message'=>'请输入新密码'
	);
	$new = form_password($new_attr);
	$new = form_format_widget('新密码', $new, true);
	
	$widget1 = array($old, $new);//控件数组
	$forms[] = form_detail_container($widget1);
	
	$btn = form_success_button();
	$forms[] = form_actions($btn);
	
	return form_implode($forms);
}

/**
 * admininfo页面的view代码
 */
function admininfo_view($user) {
	$account = form_set_defaultvalue($user, 'account');
	$field_account = form_format_field_widget('帐号', $account);
	
	$widget1 = array($field_account);//控件数组
	$form = form_detail_container($widget1);
	
	return $form;
}

/**
 * adminuseradd页面的view代码
 */
function adminuseradd_view($user) {
	$forms = array();
	
	if(empty($user['account'])) {
		$account_attr = array(
			'class'=>'span4',
			'name'=>'account',
			'data-required'=>'true',
			'data-required-message'=>'请输入帐号'
		);
		$account = form_input($account_attr);
		$account = form_format_widget('帐号', $account, true);
	}else {
		$account = form_format_field_widget('帐号', $user['account']);
	}
	
	$pwd_attr = array(
		'class'=>'span4', 
		'name'=>'pwd',
		'data-required'=>'true',
		'data-required-message'=>'请输入密码'
	);
	$pwd = form_password($pwd_attr);
	$pwd = form_format_widget('密码', $pwd, true);
	
	$widget = array($account, $pwd);
	$forms[] = form_detail_container($widget);
	$forms[] = form_detail_actions();
	
	return form_implode($forms);
}

/**
 * adminuser页面的view代码
 */
function adminuser_view($users) {
	//超链接按钮
	$buttons = array(
		matrix_a_btn(array('href'=>base_url('system/adminuseradd'), 'text'=>'添加用户'))
	);
	
	$ths = array(
		table_th('编号', '20%'),
		table_th('账号', '50%'),
		table_th('操作', '30%')
	);
	$trs = array();
	foreach ($users as $user) {
		$a_edit = table_a(base_url('system/adminuseradd', array('id'=>$user['id'])), '修改');
		$attrs = array(
			'data-id' => $user['id'],
			'data-reload' => 'true',
			'data-href' => base_url('system/adminuserdel'),
			'data-prompt' => '您确定删除此帐号吗？',
			'class' => 'warning'
		);
		$a_del = table_a_btn($attrs, '删除');
		$tds = array(
			table_format_td($user['id']),
			table_format_td($user['account']),
			table_format_td($a_edit.$a_del)
		);
		$trs[] = table_format_tr($tds);
	}
	$table = table_format($ths, $trs);
	
	return query_form(array(), $buttons).$table;
}

/**
 * auth页面的view代码
 */
function auth_view($acl_groups, $acl_users) {
	$tables = array();
	
	//分组权限
	$th1s = array(
		table_th('分组权限', null, '', array('colspan'=>2))
	);
	$tr1s = [];
	foreach ($acl_groups as $group) {
		$a_auth = table_a(base_url('system/authgroupadd', array('gid'=>$group['group_id'])), '权限分配');
		$tds = array(
			table_format_td($group['group_name'], array('width'=>'70%')),
			table_format_td($a_auth, array('width'=>'30%'))
		);
		$tr1s[] = table_format_tr($tds);
	}
	$tables[] = table_format($th1s, $tr1s);
	
	//用户权限
	$ths = array(
		table_th('用户权限', null, '', array('colspan'=>2))
	);
	foreach ($acl_users as $user) {
		$a_auth = table_a(base_url('system/authuseradd', array('uid'=>$user['id'])), '权限分配');
		$tds = array(
				table_format_td($user['account'], array('width'=>'70%')),
				table_format_td($a_auth, array('width'=>'30%'))
		);
		$trs[] = table_format_tr($tds);
	}
	$tables[] = table_format($ths, $trs);
	
	return form_implode($tables);
}

/**
 * groupuseradd页面的view代码
 */
function groupuseradd_view($users, $group_id) {
	$forms = array();
	
	$checks = [];
	foreach ($users as $user) {
		$checks[] = matrix_checkbox(array('name'=>'user[]'), $user['id'], $user['account']);
	}
	$checks[] = form_hidden('group_id', $group_id);
	$check = form_format_input_widget('选择用户', form_implode($checks));
	$widget = array($check);//控件数组
	$forms[] = form_detail_container($widget);
	
	$forms[] = form_detail_actions();
	return form_implode($forms);
}

/**
 * groupuser页面的view代码
 */
function groupuser_view($rows) {
	$tables = array();
	foreach ($rows as $row) {
		$add_user = table_a(base_url('system/groupuseradd', array('gid'=>$row['group_id'])), '添加成员');
		$ths = array(
			table_th(sprintf('%s（%d）', $row['group_name'], $row['number']), '70%'),
			table_th($add_user, '30%')
		);
		
		if(empty($row['children'])) {
			$tables[] = table_format($ths);
			continue;
		}
		
		$trs = array();
		foreach ($row['children'] as $child) {
			$attrs = array(
				'data-id' => $child['id'],
				'data-reload' => 'true',
				'data-href' => base_url('system/groupuserdel'),
				'data-prompt' => '您确定移除此成员吗？',
				'class' => 'warning'
			);
			$a_del = table_a_btn($attrs, '移除此成员');
			$tds = array(
				table_format_td($child['account']),
				table_format_td($a_del)
			);
			$trs[] = table_format_tr($tds);
		}
		$tables[] = table_format($ths, $trs);
	}
	
	return form_implode($tables);
}

/**
 * grouplistadd页面的view代码
 */
function grouplistadd_view($row) {
	$forms = array();
	
	$name_value = form_set_defaultvalue($row, 'group_name');
	$name_attrs = array(
		'class'=>'span4',
		'name'=>'group_name',
		'data-required' => 'true',
		'data-required-message' => '请输入分组名称'
	);
	$name = form_input($name_attrs, $name_value);
	$name = form_format_widget('分组名称', $name, true);
	
	$widget = array($name);//控件数组
	$forms[] = form_detail_container($widget);
	$forms[] = form_detail_actions();
	return form_implode($forms);
}

/**
 * grouplist页面的view代码
 */
function grouplist_view($rows) {
	//超链接按钮
	$buttons = array(
		matrix_a_btn(array('href'=>base_url('system/grouplistadd'), 'text'=>'添加分组'))
	);
	
	//table列表
	//设置表头 排序的icon可以根据逻辑做修改 这里是写死的
	$ths = array(
		table_th('编号', '20%'),
		table_th('分组', '40%'),
		table_th('操作', '40%')
	);
	
	$trs = array();
	foreach ($rows as $key=>$row) {
		$a1 = table_a(base_url('system/grouplistadd', array('id'=>$row['group_id'])), '修改');
		$attrs = array(
			'data-id' => $row['group_id'],
			'data-reload' => 'true',
			'data-href' => base_url('system/grouplistdel'),
			'data-prompt' => '您确定关闭此分组吗？',
			'class' => 'warning'
		);
		$a2 = table_a_btn($attrs, '关闭分组');
		$tds = array(
			table_format_td($row['group_id']),
			table_format_td($row['group_name']),
			table_format_td($a1.$a2),
		);
		$trs[] = table_format_tr($tds);
	}
	
	$table = table_format($ths, $trs);
	return query_form(array(), $buttons).$table;
}

/**
 * moduleactionadd页面的view代码
 */
function moduleactionadd_view($module, $action, $actions, $aid) {
	$module_key_value = $module['module_key'];
	$module_key_input = form_hidden('module_key', $module_key_value);//隐藏域
	$module_key = form_format_field_widget('当前模块', $module_key_value.$module_key_input);//展示
	
	$action_key_value = form_set_defaultvalue($action, 'action_key');
	if($aid > 0) {
		$action_key = form_format_field_widget('选择功能', $action_key_value);//展示
	}else {
		$action_key_attrs = array(
			'name' => 'action_key',
			'class' => 'span3',
			'data-value-not-equals' => '',
			'data-value-not-equals-message' => '请选择功能'
		);
		$action_key = matrix_select_attrs($actions, $action_key_attrs, $action_key_value);
		$action_key = form_format_widget('选择功能', $action_key, true);
	}
	
	$action_menu_value = form_set_defaultvalue($action, 'action_menu', 0);
	$action_menu_attrs = array(
		'name' => 'action_menu',
		'class' => 'span3'
	);
	$action_menu = matrix_select_attrs(enumHelper::$action_menu, $action_menu_attrs, $action_menu_value);
	$action_menu = form_format_widget('菜单功能', $action_menu);
	
	$name_value = form_set_defaultvalue($action, 'action_name');
	$name_attr = array(
		'name' => 'action_name',
		'class' => 'span4',
		'data-required' => 'true',
		'data-required-message'	=> '请输入功能名'
	);
	$name = form_input($name_attr, $name_value);
	$name = form_format_widget('功能名称', $name, true);
	
	$sort_value = form_set_defaultvalue($action, 'sort');
	$sort_attr = array(
		'name' => 'sort',
		'class' => 'span2',
		'data-digits' => 'true',
		'data-digits-message' => '请输入正确的排序数字'
	);
	$sort = form_input($sort_attr, $sort_value);
	$sort = form_format_widget('排序', $sort);
	
	//form内容拼接
	$forms = array();
	$widget = array($module_key, $action_key, $action_menu, $name, $sort);//控件数组
	$forms[] = form_detail_container($widget);
	$forms[] = form_detail_actions();
	return form_implode($forms);
}

/**
 * moduleadd页面的view代码
 */
function moduleadd_view($module, $files, $id) {
	$key_value = form_set_defaultvalue($module, 'module_key');
	if($id > 0) {
		//修改的时候是不需要下拉框的
		$key = form_format_field_widget('选择模块', $key_value);
	}else {
		$key_attrs = array(
			'name' => 'key',
			'class' => 'span2',
			'data-value-not-equals' => '',
			'data-value-not-equals-message' => '请选择模块'
		);
		$key = matrix_select_attrs($files, $key_attrs, $key_value);
		$key = form_format_widget('选择模块', $key, true);
	}
	
	$name_value = form_set_defaultvalue($module, 'module_name');
	$name_attr = array(
		'name' => 'name',
		'class' => 'span4',
		'data-required' => 'true',
		'data-required-message'	=> '请输入模块名'
	);
	$name = form_input($name_attr, $name_value);
	$name = form_format_widget('模块名称', $name, true);
	
	$sort_value = form_set_defaultvalue($module, 'sort');
	$sort_attr = array(
		'name' => 'sort',
		'class' => 'span2',
		'data-digits' => 'true',
		'data-digits-message' => '请输入正确的排序数字'
	);
	$sort = form_input($sort_attr, $sort_value);
	$sort = form_format_widget('排序', $sort);
	
	$action_value = form_set_defaultvalue($module, 'action');
	$action_attr = array(
		'name' => 'action',
		'class' => 'span2',
	);
	$action = form_input($action_attr, $action_value);
	$action = form_format_widget('默认功能', $action);
	
	$icon_value = form_set_defaultvalue($module, 'icon');
	$icon_attr = array(
		'name' => 'icon',
		'class' => 'span2',
	);
	$icon = form_input($icon_attr, $icon_value);
	$icon_a_attr = array(
		'target'=>'_blank',
		'href' => 'http://matrix.pwstrick.com/buttons.html'
	);
	$icon_prompt = '显示在左边菜单栏中的小icon，'.form_a($icon_a_attr,'查询icon名').'，将“icon-”前缀去掉就是名字';
	$icon = form_format_widget('默认icon', $icon, false, $icon_prompt);
	
	//form内容拼接
	$forms = array();
	$widget = array($key, $name, $sort, $action, $icon);//控件数组
	$forms[] = form_detail_container($widget);
	$forms[] = form_detail_actions();
	return form_implode($forms);
}