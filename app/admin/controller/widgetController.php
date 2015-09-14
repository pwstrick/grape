<?php

/**
 * 组件操作管理
 * @author pwstrick
 *
 */
class widgetController extends adminController {
	/**
	 * action列表
	 */
	public $initphp_list = array('ueditor');
	
	/**
	 * 百度编辑器初始化以及上传
	 */
	public function ueditor() {
		$action = $this->p('action');
		$path = script_url('libs/ueditor/php/config.json');
		$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($path)), true);
		switch ($action) {
			case 'config':
				echo json_encode($CONFIG);
				break;
			default:
				$this->_uploadImage('upfile');
				break;
		}
		//$this->_upload('upfile');
	}
	
	private function _uploadImage($field_name) {
		$upload = $this->getLibrary('upload');
		$folder = server_upload_path();
		$upload_path = server_upload_abs_path($folder);
		//添加文件夹
		if(!file_exists($upload_path))
			mkdir($upload_path, DIR_WRITE_MODE, true);
		//$allowed_types = '*';
		//$config['max_size'] = '2048';
		$file_name = time() . rand(1000, 9999);
		
		// 		$this->upload->initialize($config);
		$upload = $upload->upload($field_name, $file_name, $upload_path, array('allowFileType'=>'*'));
		
		if(is_int($upload)) {
			$this->controller->json_return(array('msg' => '', 'state' => 'FAILURE', 'imageid' => 0));
		}
	
		$abs_path = file_abs_path($folder, $upload['newName']);
		$hash = md5(file_get_contents($abs_path));;
		$relative_path = $folder . $upload['newName'];
		$sizeAry = getimagesize($abs_path);
		list($width, $height, $type) = $sizeAry;
		
		$imageModel = InitPHP::getMysqlDao('image');
		//判断图片是否存在
		$exists = $imageModel->checkHash($hash);
		if(!empty($exists)) {//如果存在
			$this->controller->json_return(array(
				'url' => upload_url($exists['path']),
				'state' => 'SUCCESS',
				'imageid' => $exists['id'])
			);
		}

		$image = array(
			'path' => $relative_path,
			'size' => $upload['size'],
			'width' => $width,
			'height' => $height,
			'type' => $type,
			'hash' => $hash,
			'create_time' => time()
		);
		$image_id = $imageModel->insert($image);
	
		$this->controller->json_return(array(
			'url' => upload_url($relative_path),
			'state' => 'SUCCESS',
			'imageid' => $image_id)
		);
	}
}