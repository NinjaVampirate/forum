<?php
    
    namespace Forum\Model;
    
    use Zend\Db\TableGateway\TableGateway;
    
    class PostList{
    
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
        
        public function getThreadPosts($thread_id)
        {
            $resultSet = $this->tableGateway->select(array('thread_id' => $thread_id));
            return $resultSet;
        }
     
        public function getPost($id)
        {
            $id  = (int) $id;
            $rowset = $this->tableGateway->select(array('post_id' => $id));
            $row = $rowset->current();
            if (!$row) {
                throw new \Exception("Could not find post $id");
            }
            return $row;
        }
        
        public function createPost(Post $post, $thread_id)
        {
            $data = array(
                          'thread_id' => $thread_id,
                          'content' => $post->content,
                          'user' => "anonymous",
                          );
            
            $post_id = (int) $post->post_id;
            
            if ($post_id == 0) {
                $this->tableGateway->insert($data);
            }
            
            else {
                if ($this->getPost($post_id)) {
                    $this->tableGateway->update($data, array('post_id' => $post_id));
                }
                else {
                    throw new \Exception('Post id does not exist');
                }
            }
        }
        
        public function deletePost($id)
        {
            $this->tableGateway->delete(array('post_id' => (int) $id));
        }
    
    }