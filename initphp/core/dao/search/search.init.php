<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 全文检索初始化
 * @author pwstrick
 * 
 */
class searchInit {
	
	private static $instance = array();  //单例模式获取全文检索类
	private $search_type = array('SPHINX');
	
	/**
	 * 获取search对象
	 * @param string $type
	 */
	public function init($type = 'SPHINX', $server = 'default') {
		$InitPHP_conf = InitPHP::getConfig(); //需要设置文件缓存目录
		$type = strtoupper($type);
		$type = (in_array($type, $this->search_type)) ? $type : 'SPHINX';
		switch ($type) {
			case 'SPHINX' :
				$instance_name = 'sphinx_' . $server;
				if (isset(searchInit::$instance[$instance_name])) return searchInit::$instance[$instance_name];
				$search = $this->load_search('sphinx.init.php', 'sphinxInit', $server);
				$search->setServer($InitPHP_conf['sphinx'][$server]['server'], $InitPHP_conf['sphinx'][$server]['port']);
				searchInit::$instance[$instance_name] = $search;
				return $search;
				break;
		}
	}
	
	/**
	 * 加载不同search类文件
	 * @param  string $file  文件名
	 * @param  string $class 类名
	 * @param  String $server 服务器
	 * @return obj
	 */
	private function load_search($file, $class, $server) {
		if (searchInit::$instance['require'][$file] != TRUE) {
			require('driver/' . $file);
			searchInit::$instance['require'][$file] = TRUE;
		}
		$tag = $class . "_" . $server;
		if (!searchInit::$instance['class'][$tag]) {
			searchInit::$instance['class'][$tag] = new $class;
			return searchInit::$instance['class'][$tag];
		} else {
			return searchInit::$instance['class'][$tag];
		}
	}
}