<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2015-10-07 16:36:04, compiled from D:\htdocs\grape/app/admin/template/layout/layer_header.htm */ ?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <title><?php echo $page_title ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo css_url('bootstrap.css') ?>" type="text/css" rel="stylesheet"/>
        <link href="<?php echo css_url('bootstrap-responsive.css') ?>" type="text/css" rel="stylesheet"/>
        <link href="<?php echo css_url('matrix-style.css') ?>" type="text/css" rel="stylesheet"/>
        <link href="<?php echo base_url('font-awesome/css/font-awesome.css') ?>" type="text/css" rel="stylesheet"/>
        <?php if  (!empty($page_css)) { ?>
	    <?php foreach  ($page_css as $key => $value) { ?>
	    <?php echo $value ?>
	    <?php } ?>
	    <?php } ?>
        <script type="text/javascript" src="<?php echo script_url('libs/modernizr/modernizr.js') ?>"></script>
        <!--[if lte IE 8]>
        <script src="<?php echo script_url('libs/respond/respond.js') ?>" type="text/javascript"></script>
        <![endif]-->
        <script type="text/javascript" src="<?php echo script_url('config.js') ?>"></script>
        <?php if  (!empty($page_scripts)) { ?>
	    <?php foreach  ($page_scripts as $key => $value) { ?>
	    <?php echo $value ?>
	    <?php } ?>
	    <?php } ?>
    </head>
    <body>