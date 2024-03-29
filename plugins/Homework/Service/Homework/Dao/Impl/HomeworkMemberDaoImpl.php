<?php
/**
 * 
 * @authors 赵昌 (80330582@163.com)
 * @date    2015-11-22 15:00:18
 * @version $Id$
 */
namespace Homework\Service\Homework\Dao\Impl;

use Topxia\Service\Common\BaseDao;
use Homework\Service\Homework\Dao\HomeworkMemberDao;

class HomeworkMemberDaoImpl extends BaseDao implements HomeworkMemberDao
{


	protected $table = 'homework_member';
    public function getHomeworkMember($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
        return $this->getConnection()->fetchAssoc($sql, array($id));
    }
	public function createHomeworkMember($data){
		$affected = $this->getConnection()->insert($this->table, $data);
        if ($affected <= 0) {
            throw $this->createDaoException('Insert HomeworkMember error.');
        }
        return $this->getHomeworkMember($this->getConnection()->lastInsertId());
	}
    public function findStudentHomeworkByUserId($user_id,$homework_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? AND homework_id = ?  LIMIT 1";
        $result= $this->getConnection()->fetchAll($sql, array($user_id, $homework_id)) ? : null;
        if(!empty($result)){
            return $result[0];
        }else{
            return $result;
        }
    }

    public function findHomeworkByUserIdAndLessonId($user_id,$lesson_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? AND lesson_id = ?  LIMIT 1";
        $result= $this->getConnection()->fetchAll($sql, array($user_id, $lesson_id)) ? : null;
        if(!empty($result)){
            return $result[0];
        }else{
            return $result;
        }

    }
	
	public function searchHomeworkMembers($conditions, $orderBy, $start, $limit)
    {
        $this->filterStartLimit($start, $limit);
        $builder = $this->_createSearchQueryBuilder($conditions)            
            ->select('*')
            ->orderBy($orderBy[0], $orderBy[1])
            ->setFirstResult($start)
            ->setMaxResults($limit);       
        
        return $builder->execute()->fetchAll() ? : array(); 
    }
    public function searchHomeworkMembersCount(array $conditions){

        $builder = $this->_createSearchQueryBuilder($conditions)
            
            ->select('COUNT(id)');

        return $builder->execute()->fetchColumn(0);
   
    }
    public function updateHomeworkMember($id,$homeworkMember)
    {
        $this->getConnection()->update($this->table, $homeworkMember, array('id' => $id));
        return $this->getHomeworkMember($id);
    }
    public function findHomeworkMembersByLessonId($lessonId){
        $sql = "SELECT * FROM {$this->table} WHERE  lesson_id = ?  LIMIT 1";
        $result= $this->getConnection()->fetchAll($sql, array($lesson_id)) ? : null;
        
        return $result;

    }

        protected function _createSearchQueryBuilder($conditions)
    {
            $builder = $this->createDynamicQueryBuilder($conditions)
            ->from($this->table, 'homework_member')
            ->andWhere('homework_id = :homework_id')
            ->andWhere('user_id = :user_id')
            ->andWhere('lesson_id = :lesson_id');
        return $builder;
    }
    public function delete($id){      
        
        return $this->getConnection()->delete($this->table, array('id' => $id));
    }
        
}