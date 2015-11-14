<?php
namespace Homework\Service\Homework\Impl;
use Topxia\Service\Common\BaseService;
use Homework\Service\Homework\HomeworkService;
use Topxia\Common\ArrayToolkit;

class HomeworkServiceImpl extends BaseService implements HomeworkService {
    protected function getHomeworkDao() {
        
        return $this->createDao('Homework:Homework.HomeworkDao');
    }
    public function findHomeworksByCourseIdAndLessonIds($courseId, $lessonIds) {
        
        return array(
            'homework'
        );
    }
    public function findExercisesByLessonIds($lessonIds) {
    }
    public function getHomework($id) {
        if (empty($id)) {
            
            return;
        }
        
        return $this->getHomeworkDao()->getHomework($id);
    }
    public function findHomeworksByIds(array $ids) {
        
        return ArrayToolkit::index($this->getHomeworkDao()->findHomeworksByIds($ids) , 'id');
    }
    public function findAllHomeworks() {
        
        return $this->getHomeworkDao()->findAllHomeworks();
    }
    public function createHomework(array $Homework) {
        $Homework = ArrayToolkit::parts($Homework, array(
            'content',
            'course_id',
            'lesson_id'
        ));
        if (!ArrayToolkit::requireds($Homework, array(
            'content',
            'course_id',
            'lesson_id'
        ))) {
            throw $this->createServiceException("缺少必要参数，，添加作业失败");
        }
        $this->_filterHomeworkFields($Homework);
        $Homework['create_at'] = time();
        $Homework = $this->getHomeworkDao()->addHomework($Homework);
        $this->getLogService()->info('Homework', 'create', "添加作业 {$Homework['content']}(#{$Homework['id']})", $Homework);
        
        return $Homework;
    }
    public function updateHomework($id, array $fields) {
        $Homework = $this->getHomework($id);
        if (empty($Homework)) {
            throw $this->createNoteFoundException("作业(#{$id})不存在，更新作业失败！");
        }
        $fields = ArrayToolkit::parts($fields, array(
            'id',
            'content'
        ));
        if (empty($fields)) {
            throw $this->createServiceException('参数不正确，更新作业失败！');
        }
        $this->_filterHomeworkFields($fields);
        $this->getLogService()->info('Homework', 'update', "编辑作业 {$fields['content']}(#{$id})", $fields);
        
        return $this->getHomeworkDao()->updateHomework($id, $fields);
    }
    public function deleteHomework($id) {
        $Homework = $this->getHomework($id);
        if (empty($Homework)) {
            throw $this->createNotFoundException();
        }
        $ids = $this->findHomeworkChildrenIds($id);
        $ids[] = $id;
        
        foreach ($ids as $id) {
            $this->getHomeworkDao()->deleteHomework($id);
        }
        $this->getLogService()->info('Homework', 'delete', "删除作业{$Homework['name']}(#{$id})");
    }
    protected function _filterHomeworkFields($fields) {
        
        return $fields;
    }
    protected function getLogService() {
        
        return $this->createService('System.LogService');
    }
    public function searchHomeworks(array $conditions, $sort, $start, $limit) {
    }
    public function findHomeworkByLessonId($lessonId){
$homework = $this->getHomeworkDao()->findHomeworkByLessonId($lessonId);

return $homework;

    }
    public function seachHomeworks(array $conditions, $sort, $start, $limit){}
    public function getHomeworkByLessonId($lessonId){
        
    }
        public function searchResults(){
            return array();
        }

}
