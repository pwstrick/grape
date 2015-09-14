<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2015-09-13 18:32:34, compiled from D:\htdocs\grape/app/admin/template/public/login.htm */ ?>
<div id="loginbox">
		<header class="control-group normal_text">
			<h3><img src="/images/logo.png" alt="Logo" width="182"/></h3>
		</header>
		<article id="loginform" class="form-vertical">
	        	<div class="control-group">
	              <div class="controls">
	                  <div class="main_input_box">
	                  		<label>用户名</label>
	                      	<input type="text" placeholder="请输入用户名" id="name"/>
	                  </div>
	              </div>
	        	</div>
	            <div class="control-group">
	                <div class="controls">
	                  <div class="main_input_box">
	                      	<label>密码</label>
							<input type="password" placeholder="请输入密码" id="pwd"/>
	                  </div>
	                </div>
	            </div>
	            <div class="control-group">
	                <div class="controls">
	                  <a href="javascript:void(0)" class="btn btn-inverse" id="btnLogin">登录</a>
	                </div>
	            </div>
	            <div class="control-group">
	                <div class="controls">
	            		<div class="alert alert-error hide" id="prompt"></div>
	            	</div>
				</div>
		</article>
</div>