<?php
namespace Forum\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Forum\Model\Thread;
use Forum\Form\ThreadForm;

class ForumController extends AbstractActionController
{
    protected $ThreadList;
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
        $form = new ThreadForm();
        $form->get('submit')->setValue('Create new thread');
        
        $request = $this->getRequest();
        if($request->isPost()){
            $thread = new Thread();
            // getInputFilter()
            $form->setData($request->getPost());
            
            if($form->isValid()){
                // create the thread
                $thread->exchangeArray($form->getData());
                $this->getThreadList()->createThread($thread);
                // connect to database
                $conn = mysqli_connect('localhost', 'root', 'root', 'scotchbox');
                if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error) . ".<br>"; }
                else { echo "Connection successful.<br>"; }
                // get max id
                $result = mysqli_query($conn, "SELECT MAX(id) FROM forum");
                $row = mysqli_fetch_row($result);
                $max_id = $row[0];
                // redirect to new thread
                return $this->redirect()->toRoute('thread', array('id' => $max_id));
            }
        }
        return array('form' => $form);
    }
    
    // Goto thread based on ID
    public function threadAction()
    {
        $id  = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            echo "Thread not found.<br>";
            return $this->redirect()->toRoute('forum', array(
                                                             'action' => 'index'
                                                             ));
        }
        return new ViewModel(array(
                                   'thread' => $this->getThreadList()->getThread($id),
                                   ));
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
        
       // return $this->redirect()->toRoute('forum', array('thread' => $this->getThreadList()->getThread($id),));
        return new viewModel(array('thread' => $this->getThreadList()->getThread($id),));
    }
    // Create forum table in database
    public function createAction()
    {
        echo "Attempting to connect to database... ";
        $conn = mysqli_connect('localhost', 'root', 'root', 'scotchbox');
        // Check connection
        if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error) . ".<br>"; }
        else { echo "Connection successful.<br>"; }
        
        $sql = 'CREATE TABLE forum(id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(30) NOT NULL)';
        echo "Attempting to create table... ";
        if ($conn->query($sql) === TRUE) { echo "Table created successfully.<br>"; }
        else { echo "Error creating table: " . $conn->error . ".<br>"; }
        
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
}