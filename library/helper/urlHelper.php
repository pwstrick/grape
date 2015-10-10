<?php

/**
 * 返回上一页
 */
function redirect_last_url() {
	return getenv('HTTP_REFERER');
}

/**
 * 获取带基础URL路径的地址
 * @param string $url
 * @param array $params 参数数组
 */
function base_url($url='', $params=array()) {
	$config = InitPHP::getConfig();
	if(empty($url)) {
		return rtrim($config['url'], '/');
	}
	if(!empty($params)) {
		foreach ($params as $key=>$value) {
			$params[] = $key.'='.$value;
			unset($params[$key]);
		}
		$params = implode('&', $params);
		if(strpos($url, '?') !== false) {
			$url .= '&'.$params;
		}else {
			$url .= '?'.$params;
		}
	}
	return rtrim($config['url'], '/') . '/' . $url;
}

/**
 * 获取脚本URL路径的地址
 * @param string $url
 */
function script_url($url) {
	return base_url(SCRIPT_PATH .'/'. $url);
}

/**
 * 获取CSS样式URL路径的地址
 * @param string $url
 */
function css_url($url) {
	return base_url('styles/'. $url);
}

/**
 * 获取当前路径 带参数
 */
function current_url_query($query_string = FALSE, $remove='') {
	$config = InitPHP::getConfig();
	$current_url = trim($config['url'], '/');

	// BEGIN MODIFICATION
	if ($query_string === TRUE)
	{
		$gets = $_GET;
		if(!empty($remove)) {
			$newGets = array();
			foreach ($gets as $key => $value) {
				if($key == $remove) {
					continue;
				}
				$newGets[$key] = $value;
			}
			$gets = $newGets;
		}

		// Use your preferred method of fetching the query string
		if(!empty($gets))
			$current_url .= '?'.http_build_query($gets);
	}
	// END MODIFICATION

	return $current_url;
}

/**
 * 图片上传路径设置
 * @return string
 */
function server_upload_path() {
	return sprintf('images/%s/%s/%s/', date('Y'), date('m'), date('d'));
}

/**
 * 上传绝对路径
 * @return string
 */
function server_upload_abs_path($folder) {
	return sprintf('%s/%s', UPLOAD_PATH, $folder);
}

/**
 * 文件绝对路径
 */
function file_abs_path($folder, $name) {
	return sprintf('%s%s', server_upload_abs_path($folder), $name);
}

/**
 * 上传文件URL
 * @param string $url
 */
function upload_url($url) {
	if(empty($url)) return '';
	return UPLOAD_HTTP . '/' . $url;
	//return base_url(UPLOAD_FOLDER . '/' . trim($url, '/'));
}