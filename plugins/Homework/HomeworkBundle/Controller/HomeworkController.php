<?php
namespace Homework\HomeworkBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Topxia\Common\ArrayToolkit;
use Topxia\Common\FileToolkit;
use Topxia\Common\Paginator;

class HomeworkController extends \Topxia\WebBundle\Controller\BaseController {
    public function indexAction(Request $request, $courseId) {
        $course = $this->getCourseService()->tryManageCourse($courseId);
        $homeworks=array();
        $homeworkService = $this->getHomeworkService();
        $conditions = array();
        $conditions['course_id'] = $course['id'];
        $orderBy=array();
        $orderBy[]='create_at';
        $orderBy[]='DESC';
$count = $homeworkService->searchHomeworksCount($conditions);
        $page_size =20;
        $paginator = new Paginator($request, $count, $page_size);
        
        $start =$paginator->getOffsetCount();
        $limit =$paginator->getPerPageCount();

         $homeworks = $homeworkService->searchHomeworks($conditions,$orderBy,$start,$limit);   
        
        $storageSetting = $this->getSettingService()->get("storage");

        $tpl = 'HomeworkBundle:Homework:index.html.twig';
        $assignBox = array();
        $assignBox['course'] = $course;
        $assignBox['homeworks'] = $homeworks;
        
        $assignBox['paginator'] = $paginator;
        $assignBox['now'] = $_SERVER['REQUEST_TIME'];
        $assignBox['storageSetting'] = $storageSetting;
        
        return $this->render($tpl, $assignBox);
    }
    public function listAction(Request $request) {
        $homeworkService = $this->getHomeworkService();
        $lessonId = $request->query->get('lessonId');
        $homework = $homeworkService->findHomeworkByLessonId($lessonId);
        $name = $homework['content'];
        
        return $this->render('HomeworkBundle:Default:index.html.twig', array(
            'name' => $name
        ));
    }
    public function addAction(Request $request, $courseId, $lessonId) {
        $course = $this->getCourseService()->tryManageCourse($courseId);
        $lesson = $this->getCourseService()->getCourseLesson($courseId, $lessonId);
        $homeworkService = $this->getHomeworkService();
        if ($request->getMethod() == 'POST') {
            $detail = $request->request->all();
            if (isset($detail['homework_id']) && $detail['homework_id'] > 0) {
                $homework_id = $detail['homework_id'];
                $data = array();
                $data['content'] = $detail['content'];
                $homework = $homeworkService->updateHomework($homework_id, $data);
            } else {
                unset($detail['homework_id']);
                $detail['course_id'] = $courseId;
                $detail['lesson_id'] = $lessonId;
                $homework = $homeworkService->createHomework($detail);
            }
        } else {
            $homework = $homeworkService->findHomeworkByLessonId($lessonId);
        }
        if (empty($homework)) {
            $homework = array();
            $homework['id'] = 0;
            $homework['content'] = '';
        }
        $assignBox = array();
        $assignBox['homework'] = $homework;
        $assignBox['course'] = $course;
        $assignBox['lesson'] = $lesson;
        $assignBox['targetType'] = 'homeworkPic';
        $assignBox['targetId'] = $course['id'];
        $assignBox['storageSetting'] = $this->setting('storage');
        
        return $this->render('HomeworkBundle:Homework:add-homework-modal.html.twig', $assignBox);
    }
    public function isHomeworkExists() {
    }
    public function saveAction(Request $request) {
        
        return $this->render('HomeworkBundle:Homework:add.html.twig', array(
            'name' => $name
        ));
    }
    public function uploadHomeworkFileAction(Request $request, $courseId) {
        $course = $this->getCourseService()->tryManageCourse($courseId);
        $paginator = new Paginator($request, 100, 20);
        $assignBox = array();
        $assignBox['site']['logo'] = '/files/system/2015/11-15/112251b78d20251961.jpg';
        $assignBox['type'] = '';
        $assignBox['course'] = $course;
        $assignBox['courseLessons'] = '';
        $assignBox['users'] = $course;
        $assignBox['paginator'] = $paginator;
        $assignBox['now'] = time();
        $assignBox['storageSetting'] = '';
        
        return $this->render('HomeworkBundle:Homework:homework-list.html.twig', $assignBox);
    }
    public function logoUploadAction(Request $request) {
        $fileId = $request->request->get('id');
        $objectFile = $this->getFileService()->getFileObject($fileId);
        if (!FileToolkit::isImageFile($objectFile)) {
            throw $this->createAccessDeniedException('图片格式不正确！');
        }
        $file = $this->getFileService()->getFile($fileId);
        $parsed = $this->getFileService()->parseFileUri($file["uri"]);
        $response = array(
            'path' => '/files/system/2015/11-15/112251b78d20251961.jpg',
            'url' => '/files/system/2015/11-15/112251b78d20251961.jpg',
        );
        
        return $this->createJsonResponse($response);
    }
    protected function getHomeworkService() {
        
        return $this->getServiceKernel()->createService('Homework:Homework.HomeworkService');
    }
    protected function getCourseService() {
        
        return $this->getServiceKernel()->createService('Course.CourseService');
    }
    protected function getFileService() {
        
        return $this->getServiceKernel()->createService('Content.FileService');
    }
    protected function getSettingService() {
        
        return $this->getServiceKernel()->createService('System.SettingService');
    }
}
