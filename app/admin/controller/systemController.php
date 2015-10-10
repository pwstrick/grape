<?php

/**
 * 系统设置
 * @author pwstrick
 *
 */
class systemController extends adminController {
	
	/**
	 * action列表
	 */
	public $initphp_list = array(
			'admininfo', 'adminpwd', 'adminuser', 'adminuseradd', 'adminuserdel',
			'module', 'moduleadd', 'moduledel', 'moduleactionadd', 'moduleactiondel',
			'auth', 'authgroupadd', 'authuseradd', 'groupuser', 'groupuseradd', 
			'groupuserdel', 'grouplist', 'grouplistadd', 'grouplistdel', 'ajaxsort'
	);
	
	/**
	 * 管理员信息
	 * @author pwstrick
	 */
	public function admininfo() {
		$title = '用户信息';
		$breadcrumbs = array(
			array(base_url('system/admininfo'), $title)
		);
		
		$user = $this->user();
		InitPHP::getHelper('view/system');
		
		$form = admininfo_view($user);
		$form = $this->form_token_view($form);//包裹form
		$this->view->assign('form', $form);
		
		$this->mainFormTemplate($title, $breadcrumbs);
	}
	
	/**
	 * 管理员密码修改
	 * @author pwstrick
	 */
	public function adminpwd() {
		$title = '修改密码';
		$userModel = InitPHP::getMysqlDao('user', 'mysql/sys');
		$url = base_url('system/adminpwd');
		
		if($this->controller->is_post()) {
			$new = md5($this->p('new_pwd') . constHelper::PWD_KEY);
			$old = md5($this->p('old_pwd') . constHelper::PWD_KEY);
			$id = $this->userID();
			$user = $userModel->getRowById($id);
			if($user['pwd'] != $old) {
				$this->ajaxFailureOutput('旧密码不正确');
			}
			
			$affected = $userModel->updatePwd($new, $old, $id);
			if($affected > 0) {
				$this->ajaxSuccessOutput('密码修改成功');
			}else {
				$this->ajaxFailureOutput('密码修改失败');
			}
		}
		
		$breadcrumbs = array(
			array($url, $title)
		);
		InitPHP::getHelper('view/system');
		
		$form = adminpwd_view();
		$form_attrs = array(
			'data-href' => base_url('public/login')
		);
		$form = $this->form_token_view($form, $form_attrs);//包裹form
		$this->view->assign('form', $form);
		$this->mainFormTemplate($title, $breadcrumbs);
	}
	
	/**
	 * 管理员列表
	 * @author pwstrick
	 */
	public function adminuser() {
		$breadcrumbs = array(
			array(base_url('system/adminuser'), '用户列表')
		);
		$userModel = InitPHP::getMysqlDao('user', 'mysql/sys');
		$users = $userModel->getListByStatus();
		
		InitPHP::getHelper('view/system');
		$form = adminuser_view($users);
		$this->view->assign('form', $form);
		
		$this->mainListTemplate('用户列表', $breadcrumbs);
	}
	
	/**
	 * 管理员添加修改
	 * @author pwstrick
	 */
	public function adminuseradd() {
		$id = (int)$this->p('id');//修改将会传ID
		$operate = $this->operateTitle($id);
		$title = $operate.'用户';
		$userModel = InitPHP::getMysqlDao('user', 'mysql/sys');
		$url = base_url('system/adminuser');
		
		if($this->controller->is_post()) {
			$row = array(
				'pwd' => md5($this->p('pwd') . constHelper::PWD_KEY)
			);
			if($id > 0) {
				//更新
				$affected = $userModel->updateById($row, $id);
			}else {
				$row['create_time'] = time();
				$row['account'] = $this->p('account');
				$affected = $userModel->insert($row);
			}
			if($affected > 0) {
				$msg = '用户' .$operate. '成功';
				$this->ajaxSuccessOutput($msg);
			}else {
				$msg = '用户' .$operate. '失败';
				$this->ajaxFailureOutput($msg);
			}
		}
		
		$breadcrumbs = array(
			array($url, '用户列表'),
			array(base_url('system/adminuseradd'), $title)
		);
		InitPHP::getHelper('view/system');
		$user = array();
		if($id > 0) {
			$user = $userModel->getRowById($id);
		}
		
		$form = adminuseradd_view($user);
		$form_attrs = array(
			'data-href' => $url
		);
		$form = $this->form_token_view($form, $form_attrs);//包裹form
		$this->view->assign('form', $form);
		
		$this->mainFormTemplate($title, $breadcrumbs);
	}
	
	/**
	 * 用户删除
	 * @author pwstrick
	 */
	public function adminuserdel() {
		$id = $this->p('id');
		$userModel = InitPHP::getMysqlDao('user', 'mysql/sys');
		$affected = $userModel->updateStatusById($id, constHelper::STATUS_DEL);
	
		if($affected > 0) {
			$this->ajaxSuccessOutput('用户删除成功');
		}else {
			$this->ajaxFailureOutput('用户删除失败');
		}
	}
	
	/**
	 * 模块与功能的排序
	 * @author pwstrick
	 */
	public function ajaxsort() {
		$others = $this->p('others');//其他各类参数 [p1=value1&p2=value2,p1=value3&p2=value4]
		$sorts = $this->p('orders');//[[id,value],[id,value]]
		$moduleModel = InitPHP::getMysqlDao('module', 'mysql/sys');
		$actionModel = InitPHP::getMysqlDao('action', 'mysql/sys');
		
		foreach ($sorts as $key=>$sort) {
			$other = htmlspecialchars_decode($others[$key]);
			$params = explode('&', $other);
			$object = [];
			$sort = explode(',', $sort);
			foreach ($params as $param) {
				$keys = explode('=', $param);
				$object[$keys[0]] = $keys[1];
			}
			if($object['type'] == 'module') {
				$moduleModel->updateSort($sort[0], $sort[1]);
			}else {
				$actionModel->updateSort($sort[0], $sort[1]);
			}
		}
		//$this->ajaxFailureOutput();
		$this->ajaxSuccessOutput('排序成功');
	}
	
	/**
	 * 模块列表
	 * @author pwstrick
	 */
	public function module() {
		$breadcrumbs = array(
			array(base_url('system/module'), '模块列表')
		);
		$moduleModel = InitPHP::getMysqlDao('module', 'mysql/sys');
		$actionModel = InitPHP::getMysqlDao('action', 'mysql/sys');
		
		$modules = $moduleModel->getListByStatus();//获取未删除列表
		foreach ($modules as &$module) {
			//获取这个模块下的功能
			$module['children'] = $actionModel->getListByStatus($module['module_key']);
		}
		//设置深度与序号 用于伸缩
		$number = -1;
		foreach ($modules as &$module) {
			$module['number'] = ++$number;
			$module['depth'] = 0;
			if(empty($module['children'])) {
				$module['caret'] = false;
				$module['children_count'] = 0;
				continue;
			}
			$module['caret'] = true;
			$children_count = count($module['children']);
			$module['children_count'] = $children_count;
			
			$parent_depth = $number;
			$children_index = $children_count-1;
			foreach ($module['children'] as $key => &$child) {
				$number++;
				$child['number'] = $number;
				$child['depth'] = 1;
				if($key == $children_index) {
					$child['action_name'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└─&nbsp;' . $child['action_name'];
				}else {
					$child['action_name'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├─&nbsp;' . $child['action_name'];
				}
			}
		}
		
		$this->mainTemplate(true, '模块列表', $breadcrumbs, 'menu');
		$this->view->assign('modules', $modules);
		$this->view->display('system/module');
	}
	
	/**
	 * 获取controller文件夹下面的文件
	 * @param boolean $only_name 是否只要名字
	 * @author pwstrick
	 */
	private function _getControllers($only_name=true) {
		$path = APP_PATH . '/controller';
		$files = scandir($path);
		$files = array_filter($files, function($file) {
			return $file != '.' && $file != '..' && substr($file, -4, 4) == '.php';
		});
		if($only_name) {
			foreach ($files as &$file) {
				$file = str_replace('Controller.php', '', $file);
			}
		}
		return $files;
	}
	
	/**
	 * 模块添加
	 * @author pwstrick
	 */
	public function moduleadd() {
		$id = (int)$this->p('id');//修改将会传ID
		$operate = $this->operateTitle($id);
		$title = $operate.'模块';
		$moduleModel = InitPHP::getMysqlDao('module', 'mysql/sys');
		$url = base_url('system/module');
		
		//post提交
		if($this->controller->is_post()) {
			$row = array(
				'module_name' => $this->p('name'),
				'sort' => (int)$this->p('sort'),
				'action' => $this->p('action'),
				'icon' => $this->p('icon') 
			);
			if($id > 0) {
				//修改操作
				$affected = $moduleModel->updateById($row, $id);
			}else {
				$row['module_key'] = $this->p('key');
				$affected = $moduleModel->insert($row);
			}

			if($affected > 0) {
				$msg = '模块' .$operate. '成功';
				$this->ajaxSuccessOutput($msg);
			}else {
				$msg = '模块' .$operate. '失败';
				$this->ajaxFailureOutput($msg);
			}
		}
		
		$breadcrumbs = array(
			array($url, '模块列表'),
			array(base_url('system/moduleadd'), $title)
		);
		InitPHP::getHelper('view/system');

		$module = array();
		if($id > 0) {
			$module = $moduleModel->getRowById($id);
		}
		//获取已有的模块列表
		$modules = $moduleModel->getListByStatus();
				
		//获取controller中的文件列表
		$files = $this->_getControllers();
		$modules = array_column($modules, 'module_key');
		//过滤
		$files = array_filter($files, function($m) use($modules){
			return !in_array($m, $modules);
		});
		//将key设置成value
		foreach ($files as $key=>$value) {
			$files[$value] = $value;
			unset($files[$key]);
		}

		$form = moduleadd_view($module, $files, $id);
		$form_attrs = array(
			'data-href' => $url
		);
		$form = $this->form_token_view($form, $form_attrs);//包裹form
		$this->view->assign('form', $form);
		
		$this->mainFormTemplate($title, $breadcrumbs);
	}
	
	/**
	 * 模块删除
	 * @author pwstrick
	 */
	public function moduledel() {
		$id = $this->p('id');
		$moduleModel = InitPHP::getMysqlDao('module', 'mysql/sys');
		$affected = $moduleModel->updateStatusById($id, constHelper::STATUS_DEL);
		
		if($affected > 0) {
			$this->ajaxSuccessOutput('模块移除成功');
		}else {
			$this->ajaxFailureOutput('模块移除失败');
		}
	}
	
	/**
	 * 模块功能添加
	 * @author pwstrick
	 */
	public function moduleactionadd() {
		$id = (int)$this->p('id');//获取到模块的ID
		$aid = (int)$this->p('aid');//获取到功能的ID
		$moduleModel = InitPHP::getMysqlDao('module', 'mysql/sys');
		$actionModel = InitPHP::getMysqlDao('action', 'mysql/sys');
		$module = $moduleModel->getRowById($id);//获取到模块信息
		$action = $actionModel->getRowById($aid);//获取到功能信息
		$operate = $this->operateTitle($aid);
		$url = base_url('system/module');
		if(empty($module)) {
			$this->controller->redirect($url);
		}
		
		/**
		 * post提交
		 */
		if($this->controller->is_post()) {
			$row = array(
				'action_name' => $this->p('action_name'),
				'sort' => (int)$this->p('sort'),
				'action_menu' => (int)$this->p('action_menu')
			);
			
			if($aid > 0) {
				//修改操作
				$affected = $actionModel->updateById($row, $id);
			}else {
				$row['module_key'] = $module['module_key'];
				$row['action_key'] = $this->p('action_key');
				$affected = $actionModel->insert($row);
			}
			
			if($affected > 0) {
				$msg = '功能' .$operate. '成功';
				$this->ajaxSuccessOutput($msg);
			}else {
				$msg = '功能' .$operate. '失败';
				$this->ajaxFailureOutput($msg);
			}
		}
				
		$title = $operate.'功能';
		$breadcrumbs = array(
			array($url, '模块列表'),
			array(base_url('system/moduleactionadd'), $title)
		);
		InitPHP::getHelper('view/system');
		
		//初始化controller 获取到相关的action数组
		$controller = $module['module_key'];
		$actions = InitPHP::getController($module['module_key'].'Controller', 'getActionList');
		
		//获取已有的action列表
		$exist_actions = $actionModel->getListByStatus($module['module_key']);
		$exist_actions = array_column($exist_actions, 'action_key');
		//过滤 将已有的action去除掉
		$actions = array_filter($actions, function($a) use($exist_actions){
			return !in_array($a, $exist_actions);
		});
		
		//将key设置成value
		foreach ($actions as $key=>$value) {
			$actions[$value] = $value;
			unset($actions[$key]);
		}
		
		$form = moduleactionadd_view($module, $action, $actions, $aid);
		$form_attrs = array(
			'data-href' => $url
		);
		$form = $this->form_token_view($form, $form_attrs);//包裹form
		$this->view->assign('form', $form);
		
		$this->mainFormTemplate($title, $breadcrumbs);
	}
	
	/**
	 * 模块删除
	 * @author pwstrick
	 */
	public function moduleactiondel() {
		$id = (int)$this->p('aid');
		$actionModel = InitPHP::getMysqlDao('action', 'mysql/sys');
		$affected = $actionModel->updateStatusById($id, constHelper::STATUS_DEL);
	
		if($affected > 0) {
			$this->ajaxSuccessOutput('功能移除成功');
		}else {
			$this->ajaxFailureOutput('功能移除失败');
		}
	}
	
	/**
	 * 权限设置
	 * @author pwstrick
	 */
	public function auth() {
		$breadcrumbs = array(
			array(base_url('system/auth'), '权限设置')
		);
		InitPHP::getHelper('view/system');
		$groupModel = InitPHP::getMysqlDao('group', 'mysql/sys');
		$userModel = InitPHP::getMysqlDao('user', 'mysql/sys');
		
		$groups = $groupModel->getListByStatus();
		$users = $userModel->getListByStatus();
		
		$form = auth_view($groups, $users);
		$this->view->assign('form', $form);
		
		$this->mainListTemplate('权限设置', $breadcrumbs);
	}
	
	/**
	 * 添加分组权限
	 * @author pwstrick
	 */
	public function authgroupadd() {
		$gid = (int)$this->p('gid');
		$url = base_url('system/auth');
		$aclGroupModel = InitPHP::getMysqlDao('aclGroup', 'mysql/sys');
		
		if($this->controller->is_post()) {
			$row = array(
				'group_id' => (int)$this->p('id'),
				'action_id' => (int)$this->p('aid'),
				'access' => (int)$this->p('value'),
				'update_time' => time()
			);
			
			$affected = $aclGroupModel->access($row);
			if($affected <= 0) {
				//添加
				$aclGroupModel->insert($row);
			} 
			$this->ajaxSuccessOutput('权限设置成功');
		}
		
		$breadcrumbs = array(
			array($url, '权限设置'),
			array(base_url('system/authgroupadd'), '添加分组权限')
		);
		if($gid <= 0) {
			$this->controller->redirect($url);
		}
		
		//获取模块列表
		$moduleModel = InitPHP::getMysqlDao('module', 'mysql/sys');
		$actionModel = InitPHP::getMysqlDao('action', 'mysql/sys');
		$modules = $moduleModel->getListByStatus();//获取未删除列表
		$acls = $aclGroupModel->getListByGroupId($gid);//获取权限组
		$aclHash = [];
		foreach ($acls as $acl) {
			$aclHash[$acl['action_id']] = $acl['access'];
		}
		
		$modules = $this->_authmodule($modules, $aclHash, $actionModel);
		
		$this->view->assign('ajax', base_url('system/authgroupadd'));
		$this->view->assign('modules', $modules);
		$this->view->assign('id', $gid);
		$this->mainTemplate(true, '添加分组权限', $breadcrumbs, 'menu');
		$this->view->display('system/authadd');
	}
	
	private function _authmodule($modules, $aclHash, $actionModel) {
		foreach ($modules as &$module) {
			//获取这个模块下的功能
			$module['children'] = $actionModel->getListByStatus($module['module_key']);
		}
		//设置深度与序号 用于伸缩
		$number = -1;
		foreach ($modules as &$module) {
			$module['number'] = ++$number;
			$module['depth'] = 0;
			if(empty($module['children'])) {
				$module['caret'] = false;
				$module['children_count'] = 0;
				continue;
			}
			$module['caret'] = true;
			$children_count = count($module['children']);
			$module['children_count'] = $children_count;
		
			$parent_depth = $number;
			$children_index = $children_count-1;
			foreach ($module['children'] as $key => &$child) {
				$number++;
				$child['number'] = $number;
				$child['depth'] = 1;
				if($key == $children_index) {
					$child['action_name'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└─&nbsp;' . $child['action_name'];
				}else {
					$child['action_name'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├─&nbsp;' . $child['action_name'];
				}
				//true是允许 false是禁止
				$child['action_checked'] = isset($aclHash[$child['action_id']]) && $aclHash[$child['action_id']] == 1;
			}
		}
		
		return $modules;
	}
	
	/**
	 * 添加用户权限
	 * @author pwstrick
	 */
	public function authuseradd() {
		$uid = (int)$this->p('uid');
		$url = base_url('system/auth');
		$aclUserModel = InitPHP::getMysqlDao('aclUser', 'mysql/sys');
		
		if($this->controller->is_post()) {
			$row = array(
				'user_id' => (int)$this->p('id'),
				'action_id' => (int)$this->p('aid'),
				'access' => (int)$this->p('value'),
				'update_time' => time()
			);
				
			$affected = $aclUserModel->access($row);
			if($affected <= 0) {
				//添加
				$aclUserModel->insert($row);
			}
			$this->ajaxSuccessOutput('权限设置成功');
		}
		
		$breadcrumbs = array(
			array($url, '权限设置'),
			array(base_url('system/authuseradd'), '添加用户权限')
		);
		if($uid <= 0) {
			$this->controller->redirect($url);
		}
		
		//获取模块列表
		$moduleModel = InitPHP::getMysqlDao('module', 'mysql/sys');
		$actionModel = InitPHP::getMysqlDao('action', 'mysql/sys');
		$modules = $moduleModel->getListByStatus();//获取未删除列表
		$acls = $aclUserModel->getListByUserId($uid);//获取权限组
		$aclHash = [];
		foreach ($acls as $acl) {
			$aclHash[$acl['action_id']] = $acl['access'];
		}
		
		$modules = $this->_authmodule($modules, $aclHash, $actionModel);
		
		$this->view->assign('ajax', base_url('system/authuseradd'));
		$this->view->assign('modules', $modules);
		$this->view->assign('id', $uid);
		$this->mainTemplate(true, '添加用户权限', $breadcrumbs, 'menu');
		$this->view->display('system/authadd');
	}
	
	/**
	 * 用户与分组列表
	 * @author pwstrick
	 */
	public function groupuser() {
		$breadcrumbs = array(
			array(base_url('system/groupuser'), '分组与用户')
		);
		InitPHP::getHelper('view/system');
		
		$groupModel = InitPHP::getMysqlDao('group', 'mysql/sys');
		$groupUserModel = InitPHP::getMysqlDao('groupUser', 'mysql/sys');
		$userModel = InitPHP::getMysqlDao('user', 'mysql/sys');
		$users = $userModel->getRows();//获取所有管理员列表
		$userHashs = [];
		foreach ($users as $hash) {
			$userHashs[$hash['id']] = $hash;
		}
		
		$groups = $groupModel->getListByStatus();
		$groupHashs = [];
		foreach ($groups as $hash) {
			$groupHashs[$hash['group_id']] = $hash;
		}

		foreach ($groups as &$group) {
			$group['children'] = $groupUserModel->getListByGroupId($group['group_id']);
			$group['number'] = count($group['children']);
			if(empty($group['children'])) 
				continue;
			foreach ($group['children'] as &$child) {
				$child['account'] = $userHashs[$child['user_id']]['account'];
			}
		}
		
		$form = groupuser_view($groups);
		$this->view->assign('form', $form);
		
		$this->mainListTemplate('分组与用户', $breadcrumbs);
		
	}
	
	/**
	 * 用户与分组添加
	 * @author pwstrick
	 */
	public function groupuseradd() {
		InitPHP::getHelper('view/system');
		$group_id = (int)$this->p('gid');
		$url = base_url('system/groupuser');
		$groupUserModel = InitPHP::getMysqlDao('groupUser', 'mysql/sys');
		
		if($this->controller->is_post()) {
			$user_ids = $this->p('user');
			$group_id = (int)$this->p('group_id');
			if(empty($user_ids) || $group_id <= 0) {
				$this->ajaxFailureOutput('请选择用户');
			}
			
			foreach ($user_ids as $user_id) {
				$row = array(
					'group_id' => $group_id,
					'user_id' => $user_id
				);
				$groupUserModel->insert($row);
			}
			$this->ajaxSuccessOutput('用户选择成功');
		}
		
		$breadcrumbs = array(
			array($url, '分组与用户'),
			array(base_url('system/groupuseradd'), '选择用户')
		);
		if($group_id <= 0) {
			$this->controller->redirect($url);
		}
		
		
		$userModel = InitPHP::getMysqlDao('user', 'mysql/sys');
		$users = $userModel->getRows();//获取所有管理员列表
		$groups = $groupUserModel->getListByGroupId($group_id);
		$user_ids = array_column($groups, 'user_id');
		$users = array_filter($users, function($u) use($user_ids) {
			return !in_array($u['id'], $user_ids);
		});
		
		$form = groupuseradd_view($users, $group_id);
		$attrs = array(
			'id'=>'groupuseradd',
			'data-href' => $url
		);
		$form = $this->form_token_view($form, $attrs);
		$this->view->assign('form', $form);
		$this->mainFormTemplate('选择用户', $breadcrumbs);
	}
	
	/**
	 * 用户与分组删除
	 * @author pwstrick
	 */
	public function groupuserdel() {
		$id = (int)$this->p('id');
		$groupUserModel = InitPHP::getMysqlDao('groupUser', 'mysql/sys');
		$affected = $groupUserModel->updateStatusById($id, constHelper::STATUS_DEL);
		
		if($affected > 0) {
			$this->ajaxSuccessOutput('成员移除成功');
		}else {
			$this->ajaxFailureOutput('成员移除失败');
		}
	}
	
	/**
	 * 分组列表
	 * @author pwstrick
	 */
	public function grouplist() {
		$breadcrumbs = array(
			array(base_url('system/grouplist'), '分组列表')
		);
		InitPHP::getHelper('view/system');
		
		$groupModel = InitPHP::getMysqlDao('group', 'mysql/sys');
		$rows = $groupModel->getListByStatus();
		
		$form = grouplist_view($rows);
		$this->view->assign('form', $form);
		
		$this->mainListTemplate('分组列表', $breadcrumbs);
	}
	
	/**
	 * 添加分组
	 * @author pwstrick
	 */
	public function grouplistadd() {
		InitPHP::getHelper('view/system');
		$id = (int)$this->p('id');
		$operate = $this->operateTitle($id);
		$url = base_url('system/grouplist');
		$breadcrumbs = array(
			array($url, '分组列表'),
			array(base_url('system/grouplistadd'), $operate.'分组')
		);
		$groupModel = InitPHP::getMysqlDao('group', 'mysql/sys');
		$group = $groupModel->getRowById($id);
		
		if($this->controller->is_post()) {
			$row = array(
				'group_name' => $this->p('group_name')
			);
			if($id > 0) {
				//更新
				$affected = $groupModel->updateById($row, $id);
			}else {
				$affected = $groupModel->insert($row);
			}
			
			if($affected > 0) {
				$this->ajaxSuccessOutput($operate.'分组成功');
			}else {
				$this->ajaxFailureOutput($operate.'分组失败');
			}
		}
		
		$form = grouplistadd_view($group);
		$attrs = array(
			'id'=>'grouplistadd',
			'data-href' => $url
		);
		$form = $this->form_token_view($form, $attrs);
		$this->view->assign('form', $form);
		$this->mainFormTemplate($operate.'分组', $breadcrumbs);
	}
	
	/**
	 * 分组删除
	 * @author pwstrick
	 */
	public function grouplistdel() {
		$id = (int)$this->p('id');
		$groupModel = InitPHP::getMysqlDao('group', 'mysql/sys');
		$affected = $groupModel->updateStatusById($id, constHelper::STATUS_DEL);
		
		if($affected > 0) {
			$this->ajaxSuccessOutput('关闭分组成功');
		}else {
			$this->ajaxFailureOutput('关闭分组失败');
		}
	}
}