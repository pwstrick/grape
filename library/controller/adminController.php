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
			//普通页面访问 就只做跳转
			if(empty($this->user()) && $this->_checkLogin()) {
				$this->controller->redirect(base_url('public/login'));
			}
			//TODO 做权限验证
			//载入控件编辑函数
			InitPHP::getHelper('admin');
		}
	}
	
	/**
	 * 检查当前请求是否需要登录
	 * @return boolean true：需要验证
	 */
	private function _checkLogin() {
		$enum = enumHelper::$no_login_url;
		if(!isset($enum[$this->controller_name])) {
			return true;
		}
		return !in_array($this->action_name, $enum[$this->controller_name]);
	}
	
	/**
	 * 排除token验证的判断
	 * @return boolean true：需要判断
	 */
	private function _filterToken() {
		$enum = enumHelper::$no_token_url;
		if(!isset($enum[$this->controller_name])) {
			return true;
		}
		return !in_array($this->action_name, $enum[$this->controller_name]);
	}
	
	/**
     * 提取当前登录用户对象信息
     *
     * @param string $attr
     * @return mixed
     */
    protected function user($attr = NULL) {
       	$session = $this->getUtil('session'); 
		$user = $session->get(constHelper::ADMIN_SESSION);

        if (NULL != $attr && $attr) {
            return isset($user[$attr]) ? $user[$attr] : '';
        }
        return $user;
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
	 * 主要页面模版
	 * @param string $page_title
	 * @param array $breadcrumbs
	 * @param string $script
	 * @param array $ohter_link 其他的css或script链接
	 */
	protected function mainTemplate($page_title, $breadcrumbs=array(), $script='com', $ohter_link=array()) {
		$home = array(
			array(base_url(), '控制面板')
		);
		$breadcrumbs = array_merge($home, $breadcrumbs);
		$this->view->assign('breadcrumbs', format_breadcrumbs($breadcrumbs));
		$this->view->assign('script', $script);
		$this->view->assign('page_account', $this->user('account'));
		$menus = enumHelper::$admin_menu;
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
					if($key == $this->controller_name && $sub['action'] == $this->action_name) {
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
	 */
	protected function mainListTemplate($page_title, $breadcrumbs=array(), $ohter_link=array()) {
		$this->mainTemplate($page_title, $breadcrumbs, 'list', $ohter_link);
		$this->view->display('com/form');
	}
	
	/**
	 * 表单提交页面通用模版
	 * @param string $page_title
	 * @param array $breadcrumbs
	 */
	protected function mainFormTemplate($page_title, $breadcrumbs=array(), $ohter_link=array()) {
		$this->mainTemplate($page_title, $breadcrumbs, 'form', $ohter_link);
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