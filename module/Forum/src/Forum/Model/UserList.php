<?php
    
namespace Forum\Model;
    
use Zend\Db\TableGateway\TableGateway;

class UserList{
    
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
    
    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('user_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find user $id");
        }
        return $row;
    }
    
    public function createUser(User $user)
    {
        $data = array(
                      'username' => $user->username,
                      'password' => $user->password,
                      'level' => 0,
                      );
        
        $user_id = (int) $user->user_id;
        
        if ($user_id == 0) {
            $this->tableGateway->insert($data);
        }
        
        else {
            if ($this->getUser($user_id)) {
                $this->tableGateway->update($data, array('user_id' => $user_id));
            }
            else {
                throw new \Exception('User id does not exist');
            }
        }
    }
    
    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('user_id' => (int) $id));
    }

}