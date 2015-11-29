<?php

use Topxia\Service\Common\ServiceKernel;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

$api = $app['controllers_factory'];

//获取一个活动(文章)及所有评论
$api->get('/{id}', function (Request $request,$id) {
	$start = $request->query->get('start', 0);
    $limit = $request->query->get('limit', 10);
    $orderBy = array('id','DESC');
    $conditions=array();
    $conditions['targetId']=$id;
    $conditions['threadId']=0;
    $activity = convert($id,'activity');

    
    $threadService = ServiceKernel::instance()->createService('Thread.ThreadService');

    $threads=$threadService->searchPosts($conditions, $orderBy, $start, $limit);
    
    $count = $threadService->searchPostsCount($conditions);
    $data=array();
    $data['activity']=filter($activity, 'activity');
    $data['threads']=$threads;
    $data['count']=$count;
    
    return $data;
});
//(发表一个评论)
$api->post('/{id}/post', function (Request $request,$id) {
	$fields = $request->request->all();

            $post['content'] = $fields['content'];
            $post['targetType'] = 'article';
            $post['targetId'] = $id;
$threadService = ServiceKernel::instance()->createService('Thread.ThreadService');
            $post = $threadService->createPost($post);
            return $post;
});

return $api;