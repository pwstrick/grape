<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2015-10-07 16:36:04, compiled from D:\htdocs\grape/app/admin/template/user/layer.htm */ ?>
<form class="form-horizontal validate" method="post" id="product_category" data-ztree-checkbox="tree" data-prompt-error="true" data-layer="true">
	<fieldset class="control-group">
		<div class="controls">
			<ul data-ajax="<?php echo base_url('user/ajaxtree') ?>" id="tree" class="ztree" data-required="true" data-required-message="请选择类别" style="height:200px;overflow-y:scroll;"></ul>
		</div>
	</fieldset>
	<fieldset class="form-actions">
		<button class="btn btn-success mr5" type="submit">保存</button>
		<button class="btn" name="close" type="button">关闭</button>
		<input name="init_token" id="init_token" value="<?php echo $init_token ?>" type="hidden"/>
	</fieldset>
</form>