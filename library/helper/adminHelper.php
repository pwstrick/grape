<?php
/**
 * 管理后台控件封装
 * @author pwstrick
 */
require_once 'formHelper.php';

/*********************************分页与导航*****************************************/

/**
 * 格式化面包导航
 * @param array $breadcrumbs 面包屑内容 array(0=>url, 1=>name)
 */
function format_breadcrumbs($breadcrumbs) {
	$rows = array();
	$length = count($breadcrumbs);
	foreach ($breadcrumbs as $index => $breadcrumb) {
		if(count($breadcrumb) < 2) continue;
		$row = array(
			'url' => $breadcrumb[0], 
			'name' => $breadcrumb[1]
		);
		$row['css'] = ($index == $length-1) ? 'current' : 'tip-bottom';
		$rows[] = $row;
	}
	return $rows;
}

/*********************************查询条件表单*****************************************/
/**
 * 列表页面，查询form
 * @param array $inputs 查询控件组合，可以拼接多个列表
 * array(
 * 		array(input1,input2),
 * 		array(input3,input4)
 * )
 * @param array buttons 顶部按钮链接
 * @param array $btn_groups 过滤按钮
 * array(
 * 		array(btn1,btn2),
 * 		array(btn3,btn4)
 * )
 */
function query_form($inputs=array(), $buttons=array(), $btn_groups=array()) {
	$html = '';
	//上部的超链接按钮
	if(!empty($buttons)) {
		$html .= '<div>';
		foreach ($buttons as $button) {
			if(is_array($button)) {//array就是按钮属性
				$html .= matrix_a_btn($button);
			}else {//string就直接连接
				$html .= $button;
			}
		}
		$html .= '</div>';
	}

	if(empty($inputs) && empty($btn_groups)) {
		return $html;
	}
	
	$html .= '<form method="get">';
	$html .= '<article class="widget-box">';
	$html .= '<section class="widget-content">';
	$html .= '<div class="controls controls-row">';
	//过滤按钮
	foreach ($btn_groups as $groups) {
		$html .= '<div class="btn-group mb10" data-toggle="buttons-radio">';
		foreach ($groups as $child) {
			$html .= $child;
		}
		$html .= '</div>';
	}
	//控件拼接
	if(!empty($inputs)) {
		$length = count($inputs) - 1;
		foreach ($inputs as $key => $children) {
			$html .= '<div class="ovh">';
			foreach ($children as $child) {
				$html .= $child;
			}
			//最后一列将查询按钮加上
			if($key == $length) {
				$html .= form_button(array('type' => 'submit','class' => 'btn btn-success span1'), '查询');
			}
			$html .= '</div>';
		}
	}
	$html .= '</div>';
	$html .= '</section>';
	$html .= '</article>';
	$html .= '</form>';
	return $html;
}

/*********************************表格*****************************************/
/**
 * 设置表头
 * @param string $text 文本内容
 * @param string $width 宽度
 * @param string $style style属性集合
 * @param array $extra_attrs 其他额外的参数
 */
function table_th($text, $width=null, $style='', $extra_attrs=array()) {
	$th = array('text'=>$text);
	if($width !== null) {
		$th['width'] = $width;
	}
	if(!empty($style)) {
		$th['style'] = $style;
	}
	if(!empty($extra_attrs)) {
		$th = array_merge($th, $extra_attrs);
	}
	return $th;
}

/**
 * 格式化表格tr单元格数据
 * @param array $tds td标签集合
 * @param array $tr_attrs tr属性
 * @return array
 */
function table_format_tr($tds, $tr_attrs=array()) {
	return array('tr'=>$tds, 'attrs'=>$tr_attrs);
}

/**
 * 格式化表格td单元格数据
 * @param string $text 文本内容
 * @param string $td_attrs td属性
 * @return array
 */
function table_format_td($text, $td_attrs=array()) {
	return array('td'=>$text, 'attrs'=>$td_attrs);
}

/**
 * table中的a标签
 * @param string $href 地址
 * @param string $text 文本内容
 * @param array $extra_attrs
 * @return string
 */
function table_a($href, $text, $extra_attrs=array()) {
	$data = array('href'=>$href, 'class'=>'mr5');
	if(!empty($extra_attrs)) {
		$data = array_merge($data, $extra_attrs);
	}
	return form_a($data, $text);
}

/**
 * table中需要做操作的a标签，可作为删除，或审核按钮
 * @param string $attrs 属性
 * @param string $text 文本内容
 * @param string $type 超链接类型
 * @return string
 */
function table_a_btn($attrs, $text, $type='del') {
	$href = 'javascript:void(0)';
	$data = array_merge(array('data-type' => $type), $attrs);
	return table_a($href, $text, $data);
}

/**
 * 控制生成表格
 * @param array $theads 抬头
 * @param array $rows 内容行
 * @param string $page_html 分页代码
 * @param array $tbody_attrs tbody标签的属性
 * @return string
 */
function table_format($theads, $rows = array(), $page_html='', $tbody_attrs=array()) {
	$html = '<div class="widget-box">';
	$html .= '<div class="widget-content">';
	$html .= '<table class="table table-bordered table-striped table-hover">';
	$html .= '<thead>';
	$html .= '<tr>';
	//显示抬头
	foreach ($theads as $thead) {
		$html .= '<th ' . _parse_remove_attributes($thead, 'text') . '>'.$thead['text'].'</th>';
	}
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody '._parse_remove_attributes($tbody_attrs). '>';
	
	//显示内容
	foreach ($rows as $tr) {
		if(!empty($tr['attrs'])) {
			$html .= '<tr '._parse_remove_attributes($tr['attrs']). '>';
		}else {
			$html .= '<tr>';
		}
	
		foreach ($tr['tr'] as $td) {
			$html .= '<td '._parse_remove_attributes($td['attrs']). '>'.$td['td'].'</td>';
		}
		$html .= '</tr>';
	}
	
	$html .= '</tbody>';
	$html .= '</table>';
	if(empty($rows)) {
		$html .= '<div class="alert alert-info alert-nolist"><h4 class="alert-heading">暂无数据</h4></div>';
	}
	if(!empty($page_html) && !empty($rows)) {
		$html .= $page_html;
	}
	$html .= '</div>';
	$html .= '</div>';
	return $html;
}

/*********************************matrix美化控件*****************************************/
/**
 * matrix框架中的下拉框
 * @param string select 下拉框html代码
 * @param boolean $is_container 是否需要div包裹
 * @param string class 宽度样式
 */
function matrix_select($select, $is_container=true, $class='span2') {
	$html = $select;
	if($is_container) {
		$html = '<div class="'.$class.'">';
		$html .= $select;
		$html .= '</div>';
	}
	return $html;
}

/**
 * 详情页中matrix下拉框 会有验证等特殊属性
 * @param array $datas 数据数组
 * @param array $attrs 属性数组
 * @param string $selected 要选中的内容
 * @param array $first 下拉框中第一个选择设置
 * @return string
 */
function matrix_select_attrs($datas, $attrs, $selected='', $first=array()) {
	if(empty($datas) )
		return '';

	if(!empty($first) && is_array($datas))
		$datas = $first + $datas;

	$name = '';
	$join = array();
	foreach ($attrs as $key => $value) {
		if($key == 'name') {
			$name = $value;
		}else {
			$join[] = sprintf('%s="%s"', $key, $value);
		}
	}
	return form_dropdown($name, $datas, $selected, implode(' ', $join));
}

/**
 * matrix通用组件样式
 * @return string
 */
function _matrix_widget_class($attrs, $btn_css, $other_css, $class_name) {
	$class = $other_css.' '. $class_name;
	if(!empty($btn_css)) {
		$class = $class.' '.$class_name.'-' . $btn_css;
	}
	$attrs['class'] = $class;
	return $attrs;
}

/**
 * 列表页面，matrix界面中的a标签按钮样式
 * @param array $attrs a标签属性
 * @param string $btn_css 按钮样式
 * @param string $other_css 其它按钮样式
 * @return string
 */
function matrix_a_btn($attrs, $btn_css='primary', $other_css='mr5') {
	$attrs = _matrix_widget_class($attrs, $btn_css, $other_css, 'btn');
	return form_a($attrs, $attrs['text']);
}

/**
 * matrix标签按钮
 * @param string $content 文本内容
 * @param array $attrs 属性集合
 * @param string $btn_css 按钮样式
 * @param string $other_css 其它按钮样式
 */
function matrix_span_btn($content, $attrs=array(), $btn_css='info', $other_css='mr5') {
	$attrs = _matrix_widget_class($attrs, $btn_css, $other_css, 'btn');
	return form_span($content, $attrs);
}

/**
 * matrix框架中的label
 * @param array $attrs
 * @param string $label_css 有success、warning等选项
 * @param string $other_css
 */
function matrix_label($attrs, $label_css='', $other_css='') {
	$attrs = _matrix_widget_class($attrs, $label_css, $other_css, 'label');
	return form_span($attrs['text'], $attrs);
}

/**
 * matrix框架中的badge
 * @param array $attrs
 * @param string $badge_css 有success、warning等选项
 * @param string $other_css
 */
function matrix_badge($attrs, $badge_css='', $other_css='') {
	$attrs = _matrix_widget_class($attrs, $badge_css, $other_css, 'badge');
	return form_span($attrs['text'], $attrs);
}

/**
 * img标签拼接
 * @param array $data 数据内容
 * @param boolean $asyn 异步加载
 * @return string
 */
function matrix_img($data, $asyn=true) {
	if($asyn) {
		$data['data-url'] = $data['src'];
		$data['src'] = base_url(constHelper::IMG_ASYN_URL);
		if(empty($data['class']))
			$data['class'] = 'asyn';
		else
			$data['class'] = $data['class'].' asyn';
	}
	return form_img($data);
}

/**
 * matrix框架form显示
 * @param array $form_attributes form表单属性
 */
function matrix_form($form_attributes=array()) {
	if(!isset($form_attributes['id'])) {
		//默认给个ID值
		$form_attributes['id'] = 'form'.rand(0, 9999);
	}
	$form_attributes['class'] = 'form-horizontal validate';
	$form_attributes['data-type'] = 'form';
	$form_attributes['method'] = 'post';
	return form_open($form_attributes);
}

/**
 * 自定义按钮，详情页面会有按钮做ajax操作
 * @param string $content
 * @param array $attrs
 * @param string $btn_css 样式选择
 * @param string $other_css 其他样式
 */
function matrix_custom_btn($content, $attrs=array(), $btn_css='', $other_css='') {
	$attrs['data-type'] = 'custom';
	return matrix_btn($content, $attrs, $btn_css, $other_css);
}

/**
 * 美化过的按钮
 * @param string $content
 * @param array $attrs
 * @param string $btn_css
 * @param string $other_css
 * @return string
 */
function matrix_btn($content, $attrs=array(), $btn_css='', $other_css='') {
	$data = _matrix_widget_class($attrs, $btn_css, $other_css, 'btn');
	return form_button($data, $content);
}

/**
 * 多选框
 * @param array $attrs
 * @param string $value
 * @param string $text
 * @param bool $checked
 * @param string $label_css
 * @return string
 */
function matrix_checkbox($attrs, $value, $text, $checked=false, $label_css='') {
	$html = '<label class="'.$label_css.'">';
	$html .= form_checkbox($attrs, $value, $checked).$text;
	$html .= '</label>';
	return $html;
}
/**
 * 一行可以有多个checkbox
 * @return string
 */
function matrix_checkbox_inline($attrs, $value, $text, $checked=false) {
	return matrix_checkbox($attrs, $value, $text, $checked, 'dib');
}

/**
 * 单选框
 */
function matrix_radio($attrs, $value, $text, $checked=false, $label_css='') {
	$html = '<label class="'.$label_css.'">';
	$html .= form_radio($attrs, $value, $checked).$text;
	$html .= '</label>';
	return $html;
}
/**
 * 一行可以有多个radio
 */
function matrix_radio_inline($attrs, $value, $text, $checked=false) {
	return matrix_radio($attrs, $value, $text, $checked, 'dib');
}
/*********************************详情控件*****************************************/
/**
 * forms数组拼接
 * @param array $forms
 */
function form_implode($forms) {
	return implode('', $forms);
}

/**
 * 详情form中form-actions样式
 * @param mixed $contents
 * @param $id_prompt 提示信息的<div>的ID名
 * @return string
 */
function form_actions($contents, $id_prompt = 'prompt') {
	$html = form_fieldset('', array('class'=>'form-actions'));
	if(is_array($contents)) {
		$html .= implode('', $contents);
	}else {
		$html .= $contents;
	}

	$html .= form_fieldset_close();
	return $html;
	//return $html . '<div class="alert alert-error hide" id="'.$id_prompt.'"></div>';
}

/**
 * 表单页面将会有一个提交和返回按钮
 */
function form_detail_actions() {
	$btn = form_success_button() . a_edit_go_back();//提交与返回按钮
	return form_actions($btn);
}

/**
 * 绿色成功submit按钮
 * @param string $text 文本内容
 */
function form_success_button($text = '保存提交') {
	return form_button(array('type' => 'submit', 'class'=>'btn btn-success mr5'), $text);
}

/**
 * 通用返回上一级按钮
 * @param string $href 链接地址
 * @param bool $is_form 是否要套form-actions样式
 * @param bool $is_edit_back 是否是编辑页面的返回，需要有提示
 * @return string
 */
function a_go_back($href='javascript:history.go(-1)', $is_form=false, $is_edit_back=false) {
	$attrs = array('href'=>$href, 'text'=>'返回');
	if($is_edit_back) {
		$attrs['data-type'] = 'edit_go_back';
	}
	$btn = matrix_a_btn($attrs, 'primary');
	if($is_form)
		return form_actions($btn);
	return $btn;
}

/**
 * 编辑页面返回上一级按钮
 * @param string $href 链接地址
 * @return string
 */
function a_edit_go_back($href='javascript:history.go(-1)') {
	return a_go_back($href, false, true);
}

/**
 * 生成二维码按钮
 * @param string $href 链接地址
 */
function a_qrcode($href) {
	return matrix_a_btn(array('href'=>$href, 'text'=>'生成二维码', 'target'=>'_blank'), 'inverse');
}

/**
 * 格式化表单提交组建
 * @param string $label 左边的提示语
 * @param string $widget 标签内容
 * @param bool $must 是否必填的星号
 * @param string $prompt 提示信息
 * @param int $display 展示类型 0：普通 1：文本 2：checkbox与radio一行显示
 * @return array
 */
function form_format_widget($label, $widget, $must=false, $prompt='', $display=0) {
	return array('label'=>$label, 'widget'=> array('input'=>$widget, 'prompt'=>$prompt, 'display'=>$display), 'must'=>$must);
}

/**
 * 详情页面，字段内容展示
 * @param string $label 左边的提示语
 * @param string $widget 标签内容
 * @param string $prompt 提示信息
 * @return string
 */
function form_format_field_widget($label, $widget, $prompt='') {
	return form_format_widget($label, $widget, false, $prompt, 1);
}

/**
 * 详情页面，checkbox与radio一行显示
 * @param string $label 左边的提示语
 * @param string $widget 标签内容
 * @param string $prompt 提示信息
 * @return string
 */
function form_format_input_widget($label, $widget, $prompt='') {
	return form_format_widget($label, $widget, false, $prompt, 2);
}

/**
 * 验证表单内容
 * @param array $inputs 标签数组
 * @param string $widget_title 区域标题
 * @param string $btn 底部按钮，可多个
 */
function form_detail_container($inputs, $widget_title='', $btn='') {
	$html = '<div class="widget-box">';
	if(!empty($widget_title)) {
		$html .= '<header class="widget-title">';
		$html .= '<span class="icon"><i class="icon-align-justify"></i></span>';
		$html .= '<h5>'.$widget_title.'</h5>';
		$html .= '</header>';
	}
	$html .= '<section class="widget-content nopadding">';
	$html .= form_detail($inputs);
	$html .= '</section>';
	$html .= '</div>';
	$html .= $btn;
// 	if(!empty($btn)) {//默认是submit提交按钮
// 		//$html .= form_actions(form_success_button());
// 	}
	return $html;
}

/**
 * 弹出层验证表单内容
 * @param array $inputs 标签数组
 */
function form_detail($inputs) {
	$html = '';
	foreach ($inputs as $input) {
		$html .= form_fieldset('', array('class'=>'control-group'));
		if($input['must']) {//必填选项
			$input['label'] = '<span class="must_label">*</span>' . $input['label'];
		}
		$html .= form_label($input['label'], '', array('class'=>'control-label'));//label说明
		//是否只是展示内容
		switch ($input['widget']['display']) {
			case 1:
				$html .= '<div class="controls controls-info">';
				break;
			case 2:
				$html .= '<div class="controls controls-horizontal">';
				break;
			default:
				$html .= '<div class="controls">';
				break;
		}
		$html .= $input['widget']['input'];
		if(!empty($input['widget']['prompt'])) {
			$html .= '<span class="help-block">'.$input['widget']['prompt'].'</span>';
		}
		$html .= '</div>';
		$html .= form_fieldset_close();
	}
	return $html;
}

/**
 * 快速设置输入框属性
 * @param string $name name属性
 * @param string $class class属性
 * @param string $placeholder placeholder属性
 * @param string $id属性
 */
function quick_input_attr($name, $class='', $placeholder='', $id = '') {
	$row = array(
		'name' => $name
	);
	if(!empty($placeholder)) {
		$row['placeholder'] = $placeholder;
	}
	if(!empty($class)) {
		$row['class'] = $class;
	}
	if(!empty($id)) {
		$row['id'] = $id;
	}
	return $row;
}

/**
 * 添加css标签
 */
function form_css($url) {
	return '<link href="'.$url.'" type="text/css" rel="stylesheet"/>';
}

/**
 * 添加css标签
 * @param string $url 相对路径
 */
function form_css_format($url) {
	return form_css(css_url($url));
}

/**
 * 添加script标签
 */
function form_script($url) {
	return '<script type="text/javascript" src="'.$url.'"></script>';
}

/**
 * 添加script标签
 * @param string $url 相对路径
 */
function form_script_format($url) {
	return form_script(script_url($url));
}

/**
 * 添加时间控件标记
 */
function form_my97_script() {
	return form_script(script_url('libs/My97DatePicker/WdatePicker.js'));
}

/**
 * 设置控件默认值
 * @param array $row
 * @param string $name
 * @param string $default
 */
function form_set_defaultvalue($row, $name, $default='') {
	return empty($row[$name]) ? $default : $row[$name];
}

/*********************************第三方插件*****************************************/
/**
 * uploadify插件
 * @param array $attributes 属性集合
 * @param string $ids id集合，多个用“,”隔开
 * @param string $urls 图片地址集合，多个用“,”隔开
 */
function uploadify($attributes, $ids='', $urls='') {
	$attributes['class'] = 'uploadify-file hide';
	$attributes['data-ids'] = $ids;
	$attributes['data-urls'] = $urls;
	return form_upload($attributes);
}

/**
 * ueditor插件
 * @param array $attributes 属性集合
 */
function ueditor($attributes, $value) {
	return form_textarea($attributes, $value);
}

/**
 * My97DatePicker时间插件
 * @param array $attributes 属性集合
 * @param string $value 输入框内的值
 * @param string $wdate_attr 设置规则
 */
function my97DatePicker($attributes, $value, $wdate_attr='') {
	$attributes['onclick'] = 'WdatePicker('.$wdate_attr.')';
	return form_input($attributes, $value);
}