<?php
/**
 * 
 * @authors 赵昌 (80330582@163.com)
 * @date    2015-11-22 14:57:05
 * @version $Id$
 */

namespace Homework\Service\Homework\Impl;
use Topxia\Service\Common\BaseService;
use Homework\Service\Homework\HomeworkTeacherService;
use Topxia\Common\ArrayToolkit;

class HomeworkTeacherServiceImpl extends BaseService implements HomeworkTeacherService {
    protected function getHomeworkMemberDao() {
        
        return $this->createDao('Homework:Homework.HomeworkTeacherDao');
    }
    public function getHomeworkMember($id){
        return $this->getHomeworkMemberDao()->getHomeworkMember($id);
    }
    
    public function findTeacherHomeworkByUserId($user_id,$homework_id,$homework_member_id){
        return $this->getHomeworkMemberDao()->findTeacherHomeworkByUserId($user_id,$homework_id,$homework_member_id);
    }
    public function findHomeworkByUserIdAndLessonId($user_id,$lesson_id)
    {
        return $this->getHomeworkMemberDao()->findHomeworkByUserIdAndLessonId($user_id,$lesson_id);
    }
    public function createHomeworkMember($data){
    	return $this->getHomeworkMemberDao()->createHomeworkMember($data);
    }
    public function searchHomeworkMembers($conditions, $orderBy, $start, $limit)
    {
     return $this->getHomeworkMemberDao()->searchHomeworkMembers($conditions, $orderBy, $start, $limit);   
    }
    public function searchHomeworkMembersCount(array $conditions){

       return $this->getHomeworkMemberDao()->searchHomeworkMembersCount($conditions);
   
    }
    public function updateHomeworkMember($id,$homeworkMember)
    {
        return $this->getHomeworkMemberDao()->updateHomeworkMember($id,$homeworkMember);
    }
    public function findHomeworkMembersByLessonId($lessonId){
        
    }
}