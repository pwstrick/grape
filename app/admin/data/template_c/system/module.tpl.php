<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2015-10-09 18:22:42, compiled from D:\htdocs\grape/app/admin/template/system/module.htm */ ?>
<style>
	.menu-expand i{
		width:10px;
		display:inline-block
	}
</style>
<div>
	<a href="<?php echo base_url('system/moduleadd') ?>" class="mr5 btn btn-primary">添加模块</a>
</div>
<div class="widget-box">
	<div class="widget-content">
	<button class="btn btn-info mb20" name="btnSort" data-url="<?php echo base_url('system/ajaxsort') ?>">排序</button>
	<table class="table table-bordered table-striped table-hover" id="menu">
		<thead>
			<tr>
				<th width="20%">排序</th>
				<th width="20%">控制器/功能</th>
				<th width="5%">icon</th>
				<th width="25%">模块名</th>
				<th width="10%">是否是菜单</th>
				<th width="20%">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($modules as $module) { ?>
		<tr data-row="<?php echo $module['number'] ?>" data-depth="<?php echo $module['depth'] ?>">
			<td class="menu-expand">
				<?php if ($module['caret']===true) { ?>
				<i class="icon-caret-down"></i>
				<?php } else { ?>
				<i class="icon-caret-right vbh"></i>
				<?php } ?>
				<input type="text" class="span3" name="sort" data-id="<?php echo $module['module_id'] ?>" value="<?php echo $module['sort'] ?>" data-type="module"/>
			</td>
			<td><?php echo $module['module_key'].'/'.$module['action'].'('.$module['children_count'].')' ?></td>
			<td>
				<?php if (!empty($module['icon'])) { ?>
				<i class="icon-<?php echo $module['icon'] ?>"></i>
				<?php } ?>
			</td>
			<td class="left"><?php echo $module['module_name'] ?></td>
			<td></td>
			<td>
				<a class="mr5" href="<?php echo base_url('system/moduleactionadd?id='.$module['module_id']) ?>">添加功能</a>
				<a class="mr5" href="<?php echo base_url('system/moduleadd?id='.$module['module_id']) ?>">修改</a>
				<a class="warning" data-reload="true" data-id="<?php echo $module['module_id'] ?>" data-href="<?php echo base_url('system/moduledel') ?>" data-type="del" href="javascript:void(0)" data-prompt="您确定删除这个模块吗？">移除</a>
			</td>
		</tr>
		<?php if (!empty($module['children'])) { ?>
		<?php foreach ($module['children'] as $child) { ?>
		<tr data-row="<?php echo $child['number'] ?>" data-depth="<?php echo $child['depth'] ?>">
			<td class="menu-expand"><i class="ml2 vbh"></i><input type="text" class="span3" name="sort" data-id="<?php echo $child['action_id'] ?>" value="<?php echo $child['sort'] ?>" data-type="action"/></td>
			<td><?php echo $child['action_key'] ?></td>
			<td></td>
			<td class="left"><?php echo $child['action_name'] ?></td>
			<td>
				<?php if ($child['action_menu'] == 1) { ?>√<?php } ?>
			</td>
			<td>
				<a class="mr5" href="<?php echo base_url('system/moduleactionadd', array('id'=>$module['module_id'],'aid'=>$child['action_id'])) ?>">修改</a>
				<a class="warning" data-reload="true" data-aid="<?php echo $child['action_id'] ?>" data-href="<?php echo base_url('system/moduleactiondel') ?>" data-type="del" href="javascript:void(0)" data-prompt="您确定删除这个功能吗？">移除此功能</a>
			</td>
		</tr>
		<?php } ?>
		<?php } ?>
		<?php } ?>
			</tbody>
			<thead>
				<tr>
					<th>排序</th>
					<th>控制器/功能</th>
					<th>icon</th>
					<th>模块名</th>
					<th>是否是菜单</th>
					<th>操作</th>
				</tr>
			</thead>
		</table>
		<button class="btn btn-info mb20" name="btnSort" data-url="<?php echo base_url('system/ajaxsort') ?>">排序</button>
	</div>
</div>