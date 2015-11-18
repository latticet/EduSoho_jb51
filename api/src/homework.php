<?php
/**
 * 
 * @authors 赵昌 (80330582@163.com)
 * @date    2015-11-18 15:46:56
 * @version $Id$
 */

use Topxia\Service\Common\ServiceKernel;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

$api = $app['controllers_factory'];

//根据id获取一个课程信息
$api->get('/{id}', function ($id) {
    $homework = convert($id,'homework');
    
    return filter($homework, 'homework');
});

return $api;