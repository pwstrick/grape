<?php

/**
 * 站点URL配置
 * 必选参数
 */
$InitPHP_conf['url'] = 'http://admin.grape.net/';

/*********************************Controller配置*****************************************/
/**
 * Controller控制器配置参数
 * 1. 你可以配置控制器默认的文件夹，默认的后缀，Action默认后缀，默认执行的Action和Controller
 * 2. 一般情况下，你可以不需要修改该配置参数
 * 3. $InitPHP_conf['ismodule']参数，当你的项目比较大的时候，可以选用module方式，
 * 开启module后，你的URL种需要带m的参数，原始：index.php?c=index&a=run, 加module：
 * index.php?m=user&c=index&a=run , module就是$InitPHP_conf['controller']['path']目录下的
 * 一个文件夹名称，请用小写文件夹名称
 */
$InitPHP_conf['ismodule'] = false; //开启module方式
$InitPHP_conf['controller']['path']                  = 'controller';
$InitPHP_conf['controller']['controller_postfix']    = 'Controller'; //控制器文件后缀名
$InitPHP_conf['controller']['action_postfix']        = ''; //Action函数名称后缀
$InitPHP_conf['controller']['default_controller']    = 'index'; //默认执行的控制器名称
$InitPHP_conf['controller']['default_action']        = 'index'; //默认执行的Action函数
$InitPHP_conf['controller']['module_list']           = array(); //module白名单
$InitPHP_conf['controller']['default_module']        = 'index'; //默认执行module
$InitPHP_conf['controller']['default_before_action'] = 'before'; //默认前置的ACTION名称
$InitPHP_conf['controller']['default_after_action']  = 'after'; //默认后置ACTION名称


/*********************************拦截器配置*****************************************/
/**
 * 拦截器配置
 */
$InitPHP_conf['interceptor']['path'] = 'interceptor'; //拦截器文件夹目录
$InitPHP_conf['interceptor']['postfix'] = 'Interceptor'; //拦截器类和文件的后缀名
$InitPHP_conf['interceptor']['rule'] = array( //拦截器规则
		'test' => array(
		  'file' => 'test', //文件名称 例如：testInterceptor,则file值为：test
		  'regular' =>  array(
		  		'm' => '*',
		  		'c' => '/^(index)$/', //正则表达式
		  		'a' => '/^(hello)$/' //只有indexController的方法interceptor被拦截器拦截
		  )//正则表达式
		)
);

