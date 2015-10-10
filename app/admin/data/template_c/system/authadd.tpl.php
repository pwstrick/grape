<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2015-10-09 17:19:03, compiled from D:\htdocs\grape/app/admin/template/system/authadd.htm */ ?>
<script type="text/javascript">
    $(function () {
    	var ajax = $('#hf_ajax').val();
    	var id = $('#hf_id').val();
    	var token = $('#init_token').val();
    	$('table').delegate('input:radio', 'change', function () {
            var $this = $(this);
            var attrs = $this.data();
            var value = $this.val();
            attrs['value'] = value;
            attrs['id'] = id;
            attrs['init_token'] = token;
            var $next = $this.closest('td').next();
            $next.html('<span class="in-progress">正在处理中</span>');
            $.post(ajax, attrs, function (data) {
            	$next.html('<span class="done">设置成功</span>');
            });
        });
    });
</script>
<style>
	.menu-expand i{
		width:10px;
		display:inline-block
	}
</style>
<input type="hidden" id="hf_ajax" value="<?php echo $ajax ?>"/>
<input type="hidden" id="hf_id" value="<?php echo $id ?>"/>

<div class="widget-box">
	<div class="widget-content">
	<table class="table table-bordered table-striped table-hover" id="menu">
		<tbody>
		<?php foreach ($modules as $module) { ?>
		<tr data-row="<?php echo $module['number'] ?>" data-depth="<?php echo $module['depth'] ?>">
			<td class="menu-expand" colspan="3">
				<?php if ($module['caret']===true) { ?>
				<i class="icon-caret-down"></i>
				<?php } else { ?>
				<i class="icon-caret-right vbh"></i>
				<?php } ?>
				<?php echo $module['module_name'] ?>
			</td>
		</tr>
		<?php if (!empty($module['children'])) { ?>
		<?php foreach ($module['children'] as $child) { ?>
		<tr data-row="<?php echo $child['number'] ?>" data-depth="<?php echo $child['depth'] ?>">
			<td class="menu-expand" width="60%"><?php echo $child['action_name'] ?></td>
			<td width="20%">
			<?php if ($child['action_checked']) { ?>
				<label class="dib"><input checked type="radio" value="1" name="auth<?php echo $child['action_id'] ?>" data-aid="<?php echo $child['action_id'] ?>">允许</label>
				<label class="dib"><input type="radio" value="0" name="auth<?php echo $child['action_id'] ?>" data-aid="<?php echo $child['action_id'] ?>">禁止</label>
			<?php } else { ?>
				<label class="dib"><input type="radio" value="1" name="auth<?php echo $child['action_id'] ?>" data-aid="<?php echo $child['action_id'] ?>">允许</label>
				<label class="dib"><input checked type="radio" value="0" name="auth<?php echo $child['action_id'] ?>" data-aid="<?php echo $child['action_id'] ?>">禁止</label>
			<?php } ?>
			</td>
			<td class="taskStatus" width="20%"></td>
		</tr>
		<?php } ?>
		<?php } ?>
		<?php } ?>
		</tbody>
	</table>
	</div>
</div>