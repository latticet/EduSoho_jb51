<?php
namespace Homework\HomeworkBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Topxia\Common\ArrayToolkit;
use Topxia\Common\FileToolkit;

class HomeworkController extends \Topxia\WebBundle\Controller\BaseController {
    public function indexAction($name) {
        
        return $this->render('HomeworkBundle:Default:index.html.twig', array(
            'name' => $name
        ));
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
        $assignBox=array();
        $assignBox['homework']=$homework;
        $assignBox['course']=$course;
        $assignBox['lesson']=$lesson;
        $assignBox['targetType']='homeworkPic';
        $assignBox['targetId']=$course['id'];
        $assignBox['storageSetting']=$this->setting('storage');
        
        return $this->render('HomeworkBundle:Homework:add-homework-modal.html.twig', $assignBox);
    }
    public function isHomeworkExists() {
    }
    public function saveAction(Request $request) {
        
        return $this->render('HomeworkBundle:Homework:add.html.twig', array(
            'name' => $name
        ));
    }
    public function uploadHomeworkFileAction(){
         $assignBox=array();        
        $assignBox['site']['logo']='/files/system/2015/11-15/112251b78d20251961.jpg';
        return $this->render('HomeworkBundle:Homework:file-upload-correct-homework.html.twig', $assignBox);
 
    }
        public function logoUploadAction(Request $request)
    {
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
        protected function getFileService()
    {
        return $this->getServiceKernel()->createService('Content.FileService');
    }
}