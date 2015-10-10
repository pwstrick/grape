<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2015-10-09 18:26:09, compiled from D:\htdocs\grape/app/admin/template/public/login.htm */ ?>
<div id="loginbox">
    <article id="loginform" class="form-vertical">
			<div class="control-group normal_text"><h3><img src="/images/logo-org.png" alt="Logo" /></h3></div>
            <div class="control-group">
              <div class="controls">
                  <div class="main_input_box">
                      <span class="add-on bg_lg"><i class="icon-user"></i></span><input type="text" placeholder="请输入用户名"  id="name"/>
                  </div>
              </div>
            </div>
            <div class="control-group">
                <div class="controls">
                  <div class="main_input_box">
                      <span class="add-on bg_ly"><i class="icon-lock"></i></span><input type="password" placeholder="请输入密码" id="pwd"/>
                  </div>
                </div>
            </div>
            <div class="form-actions">
                <span class="pull-right"><a href="javascript:void(0)" class="btn btn-success" id="btnLogin">登录</a></span>
            </div>
    </article>
	<div class="alert alert-error hide" id="prompt"></div>
</div>