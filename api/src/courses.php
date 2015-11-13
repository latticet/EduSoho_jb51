<?php
use Topxia\Service\Common\ServiceKernel;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
$api = $app['controllers_factory'];
//根据id获取一个课程信息
$api->get('/{id}', function ($id) {
   
    $course = convert($id, 'course');
    
    return filter($course, 'course');
});
//收藏课程
/*
** 参数 **

| 名称  | 类型  | 必需   | 说明 |
| ---- | ----- | ----- | ---- |
| method | string | 否 | 值为delete,表明当前为delete方法 |

** 响应 **

```
{
    "xxx": "xxx"
}
```
*/
$api->post('/{id}/favorite', function (Request $request, $id) {
    $course = convert($id, 'course');
    $method = $request->request->get('method', 'post');
    if ($method == 'delete') {
        $result = ServiceKernel::instance()->createService('Course.CourseService')->unFavoriteCourse($course['id']);
    } else {
        $result = ServiceKernel::instance()->createService('Course.CourseService')->favoriteCourse($course['id']);
    }
  
    return array(
        'success' => $result
    );
});



//根据分类编码获取课程
$api->get('/category/{code}', function (Request $request, $code) {

    $start = $request->query->get('start', 0);
    $limit = $request->query->get('limit', 10);
    $conditions=array();
    if($code=='all'){

    }else{
        $categoryArray = ServiceKernel::instance()->createService('Taxonomy.CategoryService')->getCategoryByCode($code);
    $childrenIds = ServiceKernel::instance()->createService('Taxonomy.CategoryService')->findCategoryChildrenIds($categoryArray['id']);
    $categoryIds = array_merge($childrenIds, array(
        $categoryArray['id']
    ));
    $conditions['categoryIds'] = $categoryIds;
    }
    
    $conditions['parentId']=0;
    $courses = ServiceKernel::instance()->createService('Course.CourseService')->searchCourses($conditions, array(
        'id',
        'DESC'
    ) , $start, $limit);
    $count = ServiceKernel::instance()->createService('Course.CourseService')->searchCourseCount($conditions);
    $data = array();
    $data['course_list'] = $courses;
    $data['count'] = $count;
    
    return $data;
});
//根据id获取一个课程Lesson信息
$api->get('/{courseId}/lesson/{lessonId}', function ($courseId,$lessonId) {
    $lesson = ServiceKernel::instance()->createService('Course.CourseService')->getCourseLesson($courseId, $lessonId);
     
    return $lesson;
});

//获取评论
$api->get('/{courseId}/reviews', function (Request $request,$courseId) {
    $start = $request->query->get('start', 0);
    $limit = $request->query->get('limit', 10);
    $reviewService = ServiceKernel::instance()->createService('Course.ReviewService');
    $reviews = $reviewService->findCourseReviews($courseId, $start,$limit );
    $count = $reviewService->getCourseReviewCount($courseId);
    $data=array();
    $data['data_list']=filters($reviews,'course');
    $data['count']=$count;
    return $data;
});
//获取个人评论
return $api;
