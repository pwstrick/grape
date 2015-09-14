<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2015-09-13 19:12:19, compiled from D:\htdocs\grape/app/admin/template/layout/main_footer.htm */ ?>
			</section>
        </div>
    </div>
    <!--end-main-container-part-->
	<!--Footer-part-->
    <div class="row-fluid">
      <div id="footer" class="span12"> 2015 &copy; Huali Admin. Brought to you by</div>
    </div>
    <!--end-Footer-part-->
    <!--提示正在载入中的效果-->
    <aside class="mask_layer">
        <p>正在加载中...</p>
        <div class="spinner_rect">
          <div class="rect1"></div>
          <div class="rect2"></div>
          <div class="rect3"></div>
          <div class="rect4"></div>
          <div class="rect5"></div>
        </div>
    </aside>
    
    <!--美化select、checkbox、radio等的样式需要引入下面的四个脚本，可酌情添加-->
    <script src="<?php echo script_url('libs/jquery/jquery.js') ?>" type="text/javascript"></script>
    <script src="<?php echo script_url('libs/uniform/jquery.uniform.js') ?>" type="text/javascript"></script>
    <script src="<?php echo script_url('libs/select2/select2.js') ?>" type="text/javascript"></script>
    <script src="<?php echo script_url('views/util/formView.js') ?>" type="text/javascript"></script>
    <!--每个页面需要引入的脚本，根据页面不同，设置的data-main也不同-->
    <script src="<?php echo script_url('libs/require/require.js') ?>" type="text/javascript" data-main="<?php echo script_url('app/'.$script.'/main') ?>"></script>
</body>
</html>