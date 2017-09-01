<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Profiler Sections
| -------------------------------------------------------------------------
| This file lets you determine whether or not various sections of Profiler
| data are displayed when the Profiler is enabled.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/profiling.html
|
*/
$config['benchmarks']= TRUE;        //在各个计时点花费的时间以及总时间 TRUE
$config['config']= TRUE;            //CodeIgniter 配置变量 TRUE
$config['controller_info']= TRUE;   //被请求的控制器类和调用的方法 TRUE
$config['get']= TRUE;               //请求中的所有 GET 数据 TRUE
$config['http_headers']= TRUE;  //本次请求的 HTTP 头部 TRUE
$config['memory_usage']= TRUE;  //本次请求消耗的内存（单位字节） TRUE
$config['post']= TRUE;          //请求中的所有 POST 数据 TRUE
$config['queries']= TRUE;       //列出所有执行的数据库查询，以及执行时间 TRUE
$config['uri_string']= TRUE;    //本次请求的 URI TRUE
$config['session_data']= TRUE;  //当前会话中存储的数据 TRUE
$config['query_toggle_count']= TRUE;    //指定显示多少个数据库查询，剩下的则默认折叠起来 25
