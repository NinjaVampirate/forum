<?php

namespace Forum\Model;

use Zend\Db\TableGateway\TableGateway;

    class ThreadList{
    
        protected $tableGateway;
        
        public function __construct(TableGateway $tableGateway)
        {
            $this->tableGateway = $tableGateway;
        }
        
        public function fetchAll()
        {
            $resultSet = $this->tableGateway->select();
            return $resultSet;
        }
        
        public function getThread($id)
        {
            $id  = (int) $id;
            $rowset = $this->tableGateway->select(array('id' => $id));
            $row = $rowset->current();
            if (!$row) {
                throw new \Exception("Could not find thread $id");
            }
            return $row;
        }
        
        public function createThread(Thread $thread)
        {
            $data = array(
                          'title' => $thread->title,
                          'OP' => "anonymous",
                          );

            $id = (int) $thread->id;
            
            if ($id == 0) {
                $this->tableGateway->insert($data);
            }
            
            else {
                if ($this->getThread($id)) {
                    $this->tableGateway->update($data, array('id' => $id));
                }
                else {
                    throw new \Exception('Thread id does not exist');
                }
            }
        }
    
        public function deleteThread($id)
        {
            $this->tableGateway->delete(array('id' => (int) $id));
        }
        
    }