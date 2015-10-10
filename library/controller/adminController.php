<?php

class adminController extends Controller {
	
	protected $controller_name;
	protected $action_name;
	
	public function __construct() {
		parent::__construct();
		
		$this->controller_name = $this->getC();
		$this->action_name = $this->getA();
		
		//post请求做限制判断
		if(($this->controller->is_post() || $this->controller->is_ajax()) && $this->_filterToken()) {
			$result = $this->controller->check_token(false);
			if(!$result) {
				$this->ajaxFailureOutput('当前是伪造请求！');
			}
		}
		
		//ajax请求就要做CSRF风险控制
		if($this->controller->is_ajax()) {
			if(empty($this->user()) && $this->_checkLogin()) {
				$this->controller->ajax_return(constHelper::AJAX_REDIRECT, '请先登录');
			}
		}else {
			//载入控件编辑函数
			InitPHP::getHelper('admin');
			
			//普通页面访问 就只做跳转
			if(empty($this->user()) && $this->_checkLogin()) {
				$this->controller->redirect(base_url('public/login'));
			}
			//TODO 做权限验证
			if($this->_filterAuth() && !$this->_checkAuth()) {
				$this->comError('您的权限不足，不能访问当前页面');
			}
		}
	}
	
	/**
	 * 获取变量
	 */
	public function getActionList() {
		return $this->initphp_list;
	}
	
	private function _enumCheck($enum) {
		if(!isset($enum[$this->controller_name])) {
			return true;
		}
		return !in_array($this->action_name, $enum[$this->controller_name]);
	}
	
	/**
	 * 排除不需要权限判断的页面
	 * @return boolean true：需要判断
	 */
	private function _filterAuth() {
		$enum = enumHelper::$no_auth_url;
		return $this->_enumCheck($enum);
	}
	
	/**
	 * 检查当前请求是否需要登录
	 * @return boolean true：需要验证
	 */
	private function _checkLogin() {
		$enum = enumHelper::$no_login_url;
		return $this->_enumCheck($enum);
	}
	
	/**
	 * 排除token验证的判断
	 * @return boolean true：需要判断
	 */
	private function _filterToken() {
		$enum = enumHelper::$no_token_url;
		return $this->_enumCheck($enum);
	}
	
	/**
     * 提取当前登录用户对象信息
     *
     * @param string $attr
     * @return mixed
     */
    protected function user($attr = '') {
       	$session = $this->getUtil('session'); 
		$user = $session->get(constHelper::ADMIN_SESSION);

        if (!empty($attr)) {
            return isset($user[$attr]) ? $user[$attr] : '';
        }
        return $user;
    }
    
    protected function userID() {
    	return (int)$this->user('id');
    }
	
    /**
     * 获取当前页码
     * @return number
     */
	protected function pageNo() {
		$p = (int)$this->p('p');
        return $p > 0 ? $p : 1;
	}
	
	/**
	 * 有token验证码的form，用于CSRF风险控制
	 */
	protected function form_token_view($content, $form_attrs) {
		$forms[] = matrix_form($form_attrs);//form表单开始
		$forms[] = $content;
		$forms[] = form_hidden('init_token', $this->controller->get_token());
		$forms[] = form_close();//form表单结束
		return implode('', $forms);
	}
	
	/**
	 * 参数名
	 * @param string $key
	 */
	protected function p($key, $isfilter=true, $type=null) {
		return $this->controller->get_gp($key, $type, $isfilter);
	}

	/**
	 * 不需要登录的模版设置
	 * @param string $script
	 * @param string $page_title
	 */
	protected function publicTemplate($page_title, $script='public') {
		$this->view->assign('script', $script);
		$this->view->assign('page_title', $page_title);
		$this->view->set_tpl('layout/public_header', 'F');
		$this->view->set_tpl('layout/public_footer', 'L');
	}
	
	/**
	 * 判断当前页面是否可以访问
	 * @return true:可以访问
	 */
	private function _checkAuth() {
		$uid = $this->userID();
		$mkey = $this->controller_name;
		$akey = $this->action_name;
// 		$without = array(1);//需要排除的后台账户
// 		if(in_array($uid, $without)) {
// 			return true;
// 		}
// 		if ('home' == $mkey) {
// 			return 1;
// 		}

		$actionModel = InitPHP::getMysqlDao('action', 'mysql/sys');
		$row = $actionModel->getOneByKey($mkey, $akey);
		if (empty($row)) {
			return false;
		}
		
		$aid = (int)$row['action_id'];
		if ($aid <= 0) {
			return false;
		}
		
		//用户权限
		$aclUserModel = InitPHP::getMysqlDao('aclUser', 'mysql/sys');
		$row = $aclUserModel->getHisAcl($uid, $aid);
		if (!empty($row)) {
			if (1 == $row['access']) {
				return true;
			}
			if (0 == $row['access']) {
				return false;
			}
		}
		
		//提取用户组
		$groupUserModel = InitPHP::getMysqlDao('groupUser', 'mysql/sys');
		$rows = $groupUserModel->getListByUid($uid);
		$gids = array();
		foreach ($rows as $row) {
			$gids[$row['group_id']] = $row['group_id'];
		}
		
		//用户组权限判断
		$result = false;
		$aclGroupModel = InitPHP::getMysqlDao('aclGroup', 'mysql/sys');
		foreach ($gids as $gid) {
			$row = $aclGroupModel->getGroupAcl($gid, $aid);
			if (1 == $row['access']) {
				$result = true;
				break;
			}
		}
		return $result;
	}
	
	/**
	 * 获取可访问的菜单
	 */
	private function _getMenu() {
		//提取模块
        $moduleModel = InitPHP::getMysqlDao('module', 'mysql/sys');
        $rows = $moduleModel->getListByStatus();
        $modules = array();
        foreach ($rows as $row) {
            $row['actions'] = array();
            $modules[$row['module_key']] = $row;
        }
		
        //提取功能
        $actionModel = InitPHP::getMysqlDao('action', 'mysql/sys');
        $rows = $actionModel->getMenuList();
        $actions = array();
        foreach ($rows as $row) {
        	$actions[$row['action_id']] = $row;
        }
		
        $uid = $this->userID();
        //提取用户组
        $groupUserModel = InitPHP::getMysqlDao('groupUser', 'mysql/sys');
        $rows = $groupUserModel->getListByUid($uid);
        $myGIDs = array();
        foreach ($rows as $row) {
        	$myGIDs[$row['group_id']] = $row['group_id'];
        }
        
        //提取用户组权限
        $aclGroupModel = InitPHP::getMysqlDao('aclGroup', 'mysql/sys');
        $gAcls = array();
        foreach ($myGIDs as $gid) {
        	$rows = $aclGroupModel->getListByGroupId($gid);
        	foreach ($rows as $row) {
        		$gAcls[$gid][$row['action_id']] = $row['access'];
        	}
        }
        
        //提取用户权限
        $aclUserModel = InitPHP::getMysqlDao('aclUser', 'mysql/sys');
        $rows = $aclUserModel->getListByUserId($uid);
        $userAcls = array();
        foreach ($rows as $row) {
        	$userAcls[$row['action_id']] = $row['access'];
        }
        
        //功能清算 - 个人设置
        $allowedActions = array();
        foreach ($userAcls as $aid => $access) {
        	if (0 == $access) { //清楚用户被禁止的功能
        		if (array_key_exists($aid, $actions)) {
        			unset($actions[$aid]);
        		}
        	}
        	if (1 == $access) { //保留被允许的功能
        		if (array_key_exists($aid, $actions)) {
        			$allowedActions[$aid] = $actions[$aid];
        		}
        	}
        }
        
        //功能清算 - 分组设置, 同一个功能在不同分组中权限设置. 只要有一个分组允许访问. 那么该组内的用户都有权访问.
        foreach ($gAcls as $gid => $acls) {
        	foreach ($acls as $aid => $access) {
        		if (1 == $access) {
        			if (array_key_exists($aid, $actions)) {
        				$allowedActions[$aid] = $actions[$aid];
        			}
        		}
        	}
        }
        
        //组织菜单
        foreach ($allowedActions as $aid => $row) {
        	if (array_key_exists($row['module_key'], $modules)) {
        		$modules[$row['module_key']]['actions'][$aid] = $row;
        	}
        }
		
        $basic = array(
        	'index' => array(
        		'action' => 'index',
        		'icon' => 'home',
        		'text' => '控制面板'
        	)
        );
        //将菜单组织成可以当前结构
        foreach ($modules as &$module) {
        	$module['text'] = $module['module_name'];
        	if(!empty($module['actions'])) {
        		$module['action'] = '#';
        		$module['sub'] = [];
        		foreach ($module['actions'] as $action) {
        			$module['sub'][] = array(
        				'action' => $action['action_key'],
        				'text' => $action['action_name'],
        				'sort' => (int)$action['sort']
        			);
        		}
        	}
        }
        $basic = array_merge($basic, $modules);
        $sorts = array_column($basic, 'sort');
        
        array_multisort($sorts, SORT_ASC, SORT_NUMERIC, $basic); //排序
        foreach ($basic as &$menu) {
        	if(empty($menu['sub'])) {
        		continue;
        	}
        	$sorts = array_column($menu['sub'], 'sort');
        	array_multisort($sorts, SORT_ASC, SORT_NUMERIC, $menu['sub']); //排序
        }

        return $basic;
	}
	
	/**
	 * 主要页面模版
	 * @param bool $is_token 是否需要设置token参数
	 * @param string $page_title
	 * @param array $breadcrumbs
	 * @param string $script
	 * @param array $ohter_link 其他的css或script链接
	 */
	protected function mainTemplate($is_token, $page_title, $breadcrumbs=array(), $script='com', $ohter_link=array()) {
		$home = array(
			array(base_url(), '控制面板')
		);
		$breadcrumbs = array_merge($home, $breadcrumbs);
		if($is_token)
			$this->view->assign('hidden_init_token', $this->controller->get_token());//设置token 用于防御CSRF
		$this->view->assign('breadcrumbs', format_breadcrumbs($breadcrumbs));
		$this->view->assign('script', $script);
		$this->view->assign('page_account', $this->user('account'));
		//$menus = enumHelper::$admin_menu;
		$menus = $this->_getMenu();
		foreach ($menus as $key=>&$menu) {
			$menu['css'] = '';
			$menu['style'] = '';//ul的样式
			if($key == $this->controller_name && $menu['action'] == $this->action_name) {
				$menu['css'] = 'active';
			}
			if($menu['action'] != '#') {
				$menu['url'] = base_url($key.'/'.$menu['action']);
			}
			if(isset($menu['sub'])) {
				foreach ($menu['sub'] as &$sub) {
					$sub['css'] = '';
					if($key == $this->controller_name && 
						($sub['action'] == $this->action_name || strpos($this->action_name, $sub['action']) === 0)) {
						$sub['css'] = 'active';
						$menu['style'] = 'display:block';//展开二级菜单
					}
					$sub['url'] = base_url($key.'/'.$sub['action']);
				}
			}
		}

		if(isset($ohter_link['page_css'])) {
			$this->view->assign('page_css', $ohter_link['page_css']);
		}
		if(isset($ohter_link['page_scripts'])) {
			$this->view->assign('page_scripts', $ohter_link['page_scripts']);
		}
		$this->view->assign('page_menu', $menus);
		$this->view->assign('page_title', $page_title);
		$this->view->set_tpl('layout/main_header', 'F');
		$this->view->set_tpl('layout/main_footer', 'L');
	}
	
	/**
	 * 列表页面通用模版
	 * @param string $page_title
	 * @param array $breadcrumbs
	 * @param array $ohter_link 自定义脚本或CSS链接
	 */
	protected function mainListTemplate($page_title, $breadcrumbs=array(), $ohter_link=array()) {
		$this->mainTemplate(true, $page_title, $breadcrumbs, 'list', $ohter_link);
		$this->view->display('com/form');
	}
	
	/**
	 * 表单提交页面通用模版
	 * @param string $page_title
	 * @param array $breadcrumbs
	 * @param array $ohter_link 自定义脚本或CSS链接
	 */
	protected function mainFormTemplate($page_title, $breadcrumbs=array(), $ohter_link=array()) {
		$this->mainTemplate(false, $page_title, $breadcrumbs, 'form', $ohter_link);
		$this->view->display('com/form');
	}
	
	/**
	 * 弹出层的模版设置
	 * @param string $script
	 * @param string $page_title
	 */
	protected function layerTemplate($page_title, $ohter_link=array(), $script='form_layer') {
		$this->view->assign('script', $script);
		if(isset($ohter_link['page_css'])) {
			$this->view->assign('page_css', $ohter_link['page_css']);
		}
		if(isset($ohter_link['page_scripts'])) {
			$this->view->assign('page_scripts', $ohter_link['page_scripts']);
		}
		$this->view->assign('page_title', $page_title);
		$this->view->set_tpl('layout/layer_header', 'F');
		$this->view->set_tpl('layout/layer_footer', 'L');
	}
	
	/**
	 * ajax请求成功返回参数
	 * @param string $message
	 * @param array $data
	 */
	protected function ajaxSuccessOutput($message='', $data=array()) {
		$this->controller->ajax_return(constHelper::AJAX_SUCCESS, $message, $data);
	}
	
	/**
	 * ajax请求失败返回参数
	 * @param string $message
	 * @param array $data
	 */
	protected function ajaxFailureOutput($message='', $data=array()) {
		$this->controller->ajax_return(constHelper::AJAX_FAILURE, $message, $data);
	}
	
	/**
	 * 格式化css与script的链接
	 * @param array $scripts
	 * @param array $css
	 */
	protected function formatMetaLink($scripts=array(), $css=array()) {
		$other_link = array();
		if(!empty($scripts)) {
			$other_link['page_scripts'] = $scripts;
		}
		if(!empty($css)) {
			$other_link['page_css'] = $css;
		}
		return $other_link;
	}
	
	/**
	 * 显示是修改还是添加
	 * @param int $id
	 */
	protected function operateTitle($id) {
		return $id > 0 ? '修改' : '添加';
	}

	/**
	 * 通用错误页面
	 * @param string 错误提示
	 * @param string 错误代码 404 500等
	 */
	protected function comError($msg, $code='500') {
		$this->mainTemplate($code);
		$this->view->assign('page_title', $code);
		$this->view->assign('code', $code);
		$this->view->assign('message', $msg);
		$this->view->display('com/error');
		exit();
	}
	
	/**
	 * alternate分页样式
	 * @param number $total 总数
	 * @param number $size 每页显示数
	 */
	function alternate($total, $size) {
		$pager= InitPHP::getLibrary('pager'); //分页加载
		$config['base_url'] = current_url_query(true, 'p');
	
		$config['total_rows'] = $total;
		$config['per_page'] = $size;
		$config['query_string_segment'] = 'p';
	
		$config['full_tag_open'] = '<div class="pagination alternate"><span>共'.$total.'条信息</span><ul>';
		$config['full_tag_close'] = '</ul></div>';
	
	
		$config['next_link'] = '下一页';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['next_tag_open_disabled'] = '<li class="disabled">';
		$config['next_tag_close_disabled'] = '</li>';
	
		$config['prev_link'] = '上一页';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['prev_tag_open_disabled'] = '<li class="disabled">';
		$config['prev_tag_close_disabled'] = '</li>';
	
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
	
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
		$config['cur_tag_close'] = '</a></li>';
	
		//$config['display_pages'] = TRUE;
		$config['last_link'] = FALSE;
		$config['first_link'] = FALSE;
		$config['num_links'] = 10;
		$config['use_page_numbers'] = TRUE;
		//$config['uri_segment'] = 1;
	
		$pager->initialize($config);
		return $pager->create_links();
	}
}