<?php
/**
 * 
 * @authors 赵昌 (80330582@163.com)
 * @date    2015-11-18 15:20:47
 * @version $Id$
 */

use Topxia\Service\Common\ServiceKernel;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

$api = $app['controllers_factory'];

//显示一条订单信息
$api->get('/show', function ($id) {
    $order = convert($id,'order');
    
    return filter($order, 'order');
});
//创建一条订单信息
$api->get('/create', function ($id) {
    $order = convert($id,'order');
    
    return filter($order, 'order');
});
//显示一条订单信息
$api->get('/detail', function ($id) {
    $order = convert($id,'order');
    
    return filter($order, 'order');
});
//显示我的订单列表
$api->get('/list', function ($id) {
    $order = convert($id,'order');
    
    return filter($order, 'order');
});


return $api;