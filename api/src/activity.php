<?php
use Topxia\Service\Common\ServiceKernel;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Topxia\Common\ArrayToolkit;
$api = $app['controllers_factory'];
//获取一个活动(文章)及所有评论
$api->get('/{id}', function (Request $request, $id) {
    $start = $request->query->get('start', 0);
    $limit = $request->query->get('limit', 10);
    $orderBy = array(
        'id',
        'DESC'
    );
    $conditions = array();
    $conditions['targetId'] = $id;
    $conditions['threadId'] = 0;
    $activity = convert($id, 'activity');
    $threadService = ServiceKernel::instance()->createService('Thread.ThreadService');
    $threads = $threadService->searchPosts($conditions, $orderBy, $start, $limit);
    $count = $threadService->searchPostsCount($conditions);
    $data = array();
    $data['activity'] = filter($activity, 'activity');
    $data['threads'] = $threads;
    $data['count'] = $count;
    
    return $data;
});

//4.1参与活动
$api->get('/{articleId}/like', function (Request $request, $articleId) {
    $user = getCurrentUser();
    $userId = $request->query->get('userId', $user['id']);
    $ArticleService = ServiceKernel::instance()->createService('Article.ArticleService');
    $result = $ArticleService->likeArticleByUserId($articleId, $userId);
    
    return $result;
});
//4.2取消参与
$api->get('/{articleId}/cancelLike', function (Request $request, $articleId) {
    $user = getCurrentUser();
    $userId = $request->query->get('userId', $user['id']);
    $ArticleService = ServiceKernel::instance()->createService('Article.ArticleService');
    $result = $ArticleService->cancelLikeArticleByUserId($articleId, $userId);
    $status=array();
    $status['code']=$result;
    return $status;
});
//5(发表一个评论)
$api->post('/{id}/post', function (Request $request, $id) {
    $fields = $request->request->all();
    $post['content'] = $fields['content'];
    $post['targetType'] = 'article';
    $post['targetId'] = $id;
    $threadService = ServiceKernel::instance()->createService('Thread.ThreadService');
    $post = $threadService->createPost($post);
    
    return $post;
});
//6 获得当前用户参与的活动
$api->get('/{id}/likes', function (Request $request, $id) {
    $user = getCurrentUser();
    $userId = $request->query->get('userId', $user['id']);
    $ArticleService = ServiceKernel::instance()->createService('Article.ArticleService');
    $articleLikes = $ArticleService->findArticleLikesByUserId($userId);
    $articles = array();
    if(!empty($articleLikes)){
     $articleIds = ArrayToolkit::column($articleLikes, 'id');  

     if(!empty($articleIds)){
      
       $articles = $ArticleService->findArticlesByIds($articleIds);
       $fields=array();
       $fields[]='id';
       $fields[]='title';
       $fields[]='categoryId';
       $fields[]='userId';
       if(!empty($articles)){
        foreach ($articles as $key => $article) {
           $articles[$key] = ArrayToolkit::parts($article, $fields);
        }
       }
         
     } 
    }
    

    return $articles;
});
return $api;
