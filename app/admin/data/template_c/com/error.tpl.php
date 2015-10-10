<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2015-10-09 18:23:20, compiled from D:\htdocs\grape/app/admin/template/com/error.htm */ ?>
<div class="widget-box">
<div class="widget-title">
	<span class="icon"> <i class="icon-info-sign"></i> </span>
	<h5>Error <?php echo $code ?></h5>
</div>
<div class="widget-content" style="min-height:400px">
	<div class="error_ex">
		<h1><?php echo $code ?></h1>
		<h3><?php echo $message ?></h3>
		<a href="<?php echo base_url('index/index') ?>" class="btn btn-warning btn-big">回到主页</a>
	</div>
</div>
</div>