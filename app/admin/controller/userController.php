<?php

/**
 * 用户管理
 * @author pwstrick
 *
 */
class userController extends adminController {
	/**
	 * action列表
	 */
	public $initphp_list = array('add', 'lists', 'layer', 'ajaxtree');
	
	/**
	 * 用户列表
	 * @author pwstrick
	 */
	public function lists() {
		InitPHP::getHelper('view/user');
		$breadcrumbs = array(
			array(base_url('user/lists'), '用户列表')
		);
		$filter = $this->p('filter');
		$email = $this->p('email');
		$begin = $this->p('begin');
		$rows = array(
			array('id'=>1, 'email'=>'iris1@hua.li', 'role'=>'客服1', 'time'=>'2015年1月22日 15:33'),
			array('id'=>2, 'email'=>'iris2@hua.li', 'role'=>'客服2', 'time'=>'2015年1月22日 15:33'),
			array('id'=>3, 'email'=>'iris3@hua.li', 'role'=>'客服3', 'time'=>'2015年1月22日 15:33'),
			array('id'=>4, 'email'=>'iris4@hua.li', 'role'=>'客服4', 'time'=>'2015年1月22日 15:33'),
			array('id'=>5, 'email'=>'iris5@hua.li', 'role'=>'客服5', 'time'=>'2015年1月22日 15:33'),
			array('id'=>6, 'email'=>'iris6@hua.li', 'role'=>'客服6', 'time'=>'2015年1月22日 15:33'),
			array('id'=>7, 'email'=>'iris7@hua.li', 'role'=>'客服7', 'time'=>'2015年1月22日 15:33'),
			array('id'=>8, 'email'=>'iris8@hua.li', 'role'=>'客服8', 'time'=>'2015年1月22日 15:33'),
			array('id'=>9, 'email'=>'iris9@hua.li', 'role'=>'客服9', 'time'=>'2015年1月22日 15:33')
		);
		
		
		$page_html = $this->alternate(100, 10);
		$form = lists_view($filter, $email, $begin, $rows, $page_html);
		
		$this->view->assign('form', $form);
		$other_link = $this->formatMetaLink(array(form_my97_script()));
		
		$this->mainListTemplate('用户列表', $breadcrumbs, $other_link);
	}
	
	/**
	 * 添加用户
	 * @author pwstrick
	 */
	public function add() {
		InitPHP::getHelper('view/user');
		$breadcrumbs = array(
			array(base_url('user/lists'), '用户列表'),
			array(base_url('user/add'), '用户添加修改')
		);
		
		$form = add_view();
		$attrs = array(
			'id'=>'add_view', 
			'data-uploadify'=>'cover', 
			'data-ueditor'=>'txtContent',
			'data-hiddeniframe'=>'selectCategory'
		);
		$form = $this->form_token_view($form, $attrs);
		$this->view->assign('form', $form);
		$this->mainFormTemplate('用户添加', $breadcrumbs);
	}
	
	/**
	 * 弹出层
	 * @author pwstrick
	 */
	public function layer() {
		if($this->controller->is_post()) {
			$tree = $this->p('tree_hidden');
			$data = array('ids'=>$tree, 'names'=>array('随机3', '随机二'));
			$this->ajaxSuccessOutput('获取成功！', $data);
		}
		
		$css_tree = form_css(script_url('libs/ztree/zTreeStyle/zTreeStyle.css'));
		$other_link = $this->formatMetaLink(array(), array($css_tree));
		$this->layerTemplate('树形结构', $other_link);
		$this->view->display('user/layer');
	}
	
	public function ajaxtree() {
		$data = array(
			array('id'=>1, 'pId'=>0, 'name'=>"随意勾选 1", 'open'=>true),
			array('id'=>11, 'pId'=>1, 'name'=>"随意勾选 1-1", 'open'=>true),
			array('id'=>111, 'pId'=>11, 'name'=>"随意勾选 1-1-1"),
			array('id'=>112, 'pId'=>11, 'name'=>"随意勾选 1-1-2"),
			array('id'=>12, 'pId'=>1, 'name'=>"随意勾选 1-2", 'open'=>true),
			array('id'=>121, 'pId'=>12, 'name'=>"随意勾选 1-2-1"),
			array('id'=>122, 'pId'=>12, 'name'=>"随意勾选 1-2-2"),
			array('id'=>2, 'pId'=>0, 'name'=>"随意勾选 2", 'open'=>true),
			array('id'=>21, 'pId'=>2, 'name'=>"随意勾选 2-1"),
			array('id'=>22, 'pId'=>2, 'name'=>"随意勾选 2-2", 'open'=>true),
			array('id'=>221, 'pId'=>22, 'name'=>"随意勾选 2-2-1"),
			array('id'=>222, 'pId'=>22, 'name'=>"随意勾选 2-2-2"),
			array('id'=>23, 'pId'=>2, 'name'=>"随意勾选 2-3")
		);
		$this->ajaxSuccessOutput('获取成功！', $data);
	}
}