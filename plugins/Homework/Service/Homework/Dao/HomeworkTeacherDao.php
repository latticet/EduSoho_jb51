<?php
/**
 * 
 * @authors 赵昌 (80330582@163.com)
 * @date    2015-11-22 14:59:31
 * @version $Id$
 */

namespace Homework\Service\Homework\Dao;

interface HomeworkTeacherDao
{
	public function getHomeworkMember($id);
	public function createHomeworkMember($data);
	
	public function findTeacherHomeworkByUserId($user_id,$homework_id,$homework_member_id);
	public function findHomeworkByUserIdAndLessonId($user_id,$lesson_id);
	public function searchHomeworkMembers($conditions, $orderBy, $start, $limit);
    public function searchHomeworkMembersCount(array $conditions);
    public function updateHomeworkMember($id,$homeworkMember);
    public function findHomeworkMembersByLessonId($lessonId);

}