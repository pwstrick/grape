<?php

class testInterceptor implements interceptorInterface {
	
	/**
	 * 前置拦截器，在所有Action运行全会进行拦截
	 * 如果返回true，则拦截通过;如果返回false，则拦截
	 * @return boolean 返回布尔类型，如果返回false，则截断
	 */
	public function preHandle() {
		$config = InitPHP::getConfig();
		if($config['is_xhprof']) {
			xhprof_enable();
		}
		
		return true;
	}

	/**
	 * 后置拦截器，在所有操作进行完毕之后进行拦截
	 */
	public function postHandle() {
		$config = InitPHP::getConfig();
		if($config['is_xhprof']) {
			$xhprof_data = xhprof_disable();
			// save raw data for this profiler run using default
			// implementation of iXHProfRuns.
			$xhprof_runs = new XHProfRuns_Default();
			// save the run under a namespace "xhprof_foo"
			$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
		}
	}
}