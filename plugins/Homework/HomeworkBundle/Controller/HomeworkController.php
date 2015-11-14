<?php
namespace Homework\HomeworkBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Topxia\Common\ArrayToolkit;

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
    protected function getHomeworkService() {
        
        return $this->getServiceKernel()->createService('Homework:Homework.HomeworkService');
    }
    protected function getCourseService() {
        
        return $this->getServiceKernel()->createService('Course.CourseService');
    }
}
