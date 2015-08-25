<?php
namespace Forum\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Forum\Model\Thread;
use Forum\Form\ThreadForm;
use Forum\Model\Post;
use Forum\Form\PostForm;

class ForumController extends AbstractActionController
{
    protected $ThreadList;
    protected $PostList;
    // Homepage
    public function indexAction()
    {
        return new ViewModel(array(
                                   'threadlist' => $this->getThreadList()->fetchAll(),
                                   ));
    }
    // Add a thread
    public function addAction()
    {
        // create thread and set title
        $form = new ThreadForm();
        $form->get('submit')->setValue('Create new thread');
        
        $request = $this->getRequest();
        if($request->isPost()){
            $thread = new Thread();
            // getInputFilter()
            $form->setData($request->getPost());
            
            if($form->isValid()){
                $thread->exchangeArray($form->getData());
                $this->getThreadList()->createThread($thread);
                // to get the max id
                $conn = mysqli_connect('localhost', 'root', 'root', 'scotchbox');
                if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error) . ".<br>"; }
                else { echo "Connection successful.<br>"; }
                $result = mysqli_query($conn, "SELECT MAX(id) FROM forum");
                $row = mysqli_fetch_row($result);
                $max_id = $row[0];
                return $this->redirect()->toRoute('thread', array('id' => $max_id));
            }
        }
        return array('form' => $form);
    }
    
    // Goto thread based on ID and make posts
    public function threadAction()
    {
        $id  = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            echo "Thread not found.<br>";
            return $this->redirect()->toRoute('forum', array(
                                                             'action' => 'index'
                                                             ));
        }
        
        $form = new PostForm();
        $form->get('submit')->setValue('Reply');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = new Post();
            // getInputFilter()
            $form->setData($request->getPost());
            
            if($form->isValid()){
                $post->exchangeArray($form->getData());
                $this->getPostList()->createPost($post, $id);
                // next line needed or text stays in box - must be better way?
                return $this->redirect()->toRoute('thread', array('id' => $id));
        
            }
        }
        return array(
                                   'thread' => $this->getThreadList()->getThread($id),
                                    'posts' => $this->getPostList()->getThreadPosts($id),
                                    'form' => $form,
                                    );
    }
    
    // Delete a thread
    public function deleteAction()
    {
        $id  = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            echo "Thread not found.<br>";
            return $this->redirect()->toRoute('forum');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            echo "Request successful <br>";
            $del = $request->getPost('del', 'No');
            
            if ($del == 'Yes') {
                echo "Deleting <br>";
                $id = (int) $request->getPost('id');
                $this->getThreadList()->deleteThread($id);
            }
             return $this->redirect()->toRoute('forum');
        }
        return new viewModel(array('thread' => $this->getThreadList()->getThread($id),));
    }
    
    
    // Clear all threads
    public function clearAction(){
        $request=$this->getRequest();
        // maybe reset id?
        if ($request->isPost())  {
            $del = $request->getPost('del','No');
            
            if($del == 'Yes') {
                $threads = $this->getThreadList()->fetchAll();
            
                foreach($threads as $thread){
                    $id = $thread->id;
                    $this->getThreadList()->deleteThread($id);
                }
                return $this->redirect()->toRoute('forum');
            }
            if($del == 'No') { return $this->redirect()->toRoute('forum'); }
        }
        return new viewModel();
    }
    
    // Create forum/posts tables in database
    public function createAction()
    {
        // connect to database
        echo "Attempting to connect to database... ";
        $conn = mysqli_connect('localhost', 'root', 'root', 'scotchbox');
        // Check connection
        if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error) . ".<br>"; }
        else { echo "Connection successful.<br>"; }
        
        // reset the forum
        $request=$this->getRequest();
        if ($request->isPost())  {
            $del = $request->getPost('del', null);
            if($del == 'Yes') {
                //delete forum
                echo "Deleting original table...";
                $sql = 'DROP TABLE forum';
                if ($conn->query($sql) === TRUE) { echo "Original table deleted.<br>"; }
                else {
                    echo "Error deleting table: " . $conn->error . ".<br>";
                }
                //delete posts
                echo "Deleting posts...";
                $sql = 'DROP TABLE posts';
                if ($conn->query($sql) === TRUE) { echo "Posts deleted.<br>"; }
                else {
                    echo "Error deleting posts " . $conn->error . ".<br>";
                }
                // create forum
                $sql = 'CREATE TABLE forum(id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(30) NOT NULL, OP VARCHAR(15) NOT NULL)';
                echo "Attempting to create forum table... ";
                if ($conn->query($sql) === TRUE) { echo "Table created successfully.<br>"; }
                else {
                    echo "Error creating table: " . $conn->error . ".<br>";
                }
                // create posts
                $sql = 'CREATE TABLE posts(post_id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY, thread_id INT(3) NOT NULL, content TEXT(100) NOT NULL, user VARCHAR(15) NOT NULL)';
                echo "Attempting to create posts table... ";
                if ($conn->query($sql) === TRUE) { echo "Table created successfully.<br>"; }
                else {
                    echo "Error creating table: " . $conn->error . ".<br>";
                }
                
                return $this->redirect()->toRoute('forum');
            }
            if($del == 'No') { return $this->redirect()->toRoute('forum'); }
        }
            
        $conn->close();
        
        return new viewModel();
    }

    // Function to return threads from database
    public function getThreadList()
    {
        if (!$this->ThreadList) {
            $sm = $this->getServiceLocator();
            $this->ThreadList = $sm->get('Forum\Model\ThreadList');
        }
        return $this->ThreadList;
    }
    
    public function getPostList()
    {
        if (!$this->PostList) {
            $sm = $this->getServiceLocator();
            $this->PostList = $sm->get('Forum\Model\PostList');
        }
        return $this->PostList;
    }
}