<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2015-10-09 18:26:16, compiled from D:\htdocs\grape/app/admin/template/layout/main_header.htm */ ?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <title><?php echo $page_title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="<?php echo css_url('bootstrap.css') ?>" type="text/css" rel="stylesheet"/>
    <link href="<?php echo css_url('bootstrap-responsive.css') ?>" type="text/css" rel="stylesheet"/>
    <link href="<?php echo css_url('matrix-style.css') ?>" type="text/css" rel="stylesheet"/>
	<link href="<?php echo css_url('matrix-media.css') ?>" type="text/css" rel="stylesheet"/>
    <link href="<?php echo base_url('font-awesome/css/font-awesome.css') ?>" type="text/css" rel="stylesheet"/>
    <link href="<?php echo css_url('google-fonts.css') ?>" type="text/css" rel="stylesheet"/>
    <!-- 美化下拉框的样式,可酌情引用-->
    <link href="<?php echo script_url('libs/select2/css/select2.css') ?>" type="text/css" rel="stylesheet"/>
    <!-- 美化表单中input checkbox radio等控件样式,可酌情引用-->
    <link href="<?php echo script_url('libs/uniform/themes/default/css/uniform.default.css') ?>" type="text/css" rel="stylesheet"/>
    <!-- 上传插件的样式,可酌情引用-->
    <link href="<?php echo script_url('libs/uploadify/css/uploadify.css') ?>" type="text/css" rel="stylesheet"/>
    <!--[if lte IE 8]>
    <script src="<?php echo script_url('libs/respond/respond.js') ?>" type="text/javascript"></script>
    <![endif]-->
    <?php if  (!empty($page_css)) { ?>
    <?php foreach  ($page_css as $key => $value) { ?>
    <?php echo $value ?>
    <?php } ?>
    <?php } ?>
    <!-- 检测CSS3属性支持的插件，让IE8等浏览器支持HTML5的标签-->
    <script type="text/javascript" src="<?php echo script_url('libs/modernizr/modernizr.js') ?>"></script>
    <script type="text/javascript" src="<?php echo script_url('config.js') ?>"></script>
    <script type="text/javascript" src="<?php echo script_url('libs/jquery/jquery.js') ?>"></script>
    <?php if  (!empty($page_scripts)) { ?>
    <?php foreach  ($page_scripts as $key => $value) { ?>
    <?php echo $value ?>
    <?php } ?>
    <?php } ?>
</head>
<body>
    <!--Header-part LOGO背景图片-->
    <div id="header">
      <h1><a href="/">Huali</a></h1>
    </div>
    <!--close-Header-part-->
    <!--top-Header-menu 顶部菜单-->
    <div id="user-nav" class="navbar navbar-inverse">
      <ul class="nav">
        <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon-user"></i>  <span class="text">欢迎<?php echo $page_account ?></span><b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo base_url('system/admininfo') ?>"><i class="icon-info-sign mr5"></i>我的信息</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo base_url('system/adminpwd') ?>"><i class="icon-check mr5"></i>修改密码</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo base_url('public/logout') ?>"><i class="icon-key mr5"></i>退出登录</a></li>
          </ul>
        </li>
        <li><a href="<?php echo base_url('public/logout') ?>"><i class="icon-share-alt"></i> <span class="text">退出登录</span></a></li>
      </ul>
    </div>
    <!--close-top-Header-menu-->
    <!--sidebar-menu，侧边栏菜单-->
    <div id="sidebar">
      <ul>
      <?php foreach  ($page_menu as $menu) { ?>
      	<?php if (isset($menu['sub'])) { ?>
	    <li class="submenu <?php echo $menu['css'] ?>"> <a href="#"><i class="icon-<?php echo $menu['icon'] ?>"></i> <span><?php echo $menu['text'] ?></span></a>
	    	<ul style="<?php echo $menu['style'] ?>">
	      		<?php foreach  ($menu['sub'] as $sub) { ?>
	      		<li class="<?php echo $sub['css'] ?>"><a href="<?php echo $sub['url'] ?>"><?php echo $sub['text'] ?></a></li>
	      		<?php } ?>
	      	</ul>
	    </li>
      	<?php } else { ?>
        <li class="<?php echo $menu['css'] ?>"><a href="<?php echo $menu['url'] ?>" class="<?php echo $menu['css'] ?>"><i class="icon-<?php echo $menu['icon'] ?>"></i> <span><?php echo $menu['text'] ?></span></a> </li>	
      	<?php } ?>
      <?php } ?>
      </ul>
    </div>
    <!--sidebar-menu-->
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs面包屑导航-->
        <div id="content-header">
              <div id="breadcrumb">
              <?php foreach  ($breadcrumbs as $key=>$breadcrumb) { ?>
              	<?php if  ($key == 0) { ?>
              	<a href="<?php echo $breadcrumb['url'] ?>" class="<?php echo $breadcrumb['css'] ?>"><i class="icon-home"></i> <?php echo $breadcrumb['name'] ?></a>
              	<?php } else { ?>
              	<a href="<?php echo $breadcrumb['url'] ?>" class="<?php echo $breadcrumb['css'] ?>"><?php echo $breadcrumb['name'] ?></a>
              	<?php } ?>
              <?php } ?>
              </div>
        </div>
        <!--End-breadcrumbs-->
        <div class="container-fluid">
            <section class="row-fluid">
                  <!--不同页面的输出在这里-->
            
    