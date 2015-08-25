<?php
    namespace Forum\Controller;
    
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;
    use Forum\Model\User;
    use Forum\Form\UserForm;
    // use Forum\Model\Post;
    // use Forum\Form\PostForm;
    
class UserController extends AbstractActionController
{
    protected $UserList;
    
    public function registerAction()
    {
        $form = new UserForm();
        $form->get('submit')->setValue('Register');
        
        $request = $this->getRequest();
        if($request->isPost()){
           $user = new User();
           $form->setData($request->getPost());
           
            if($form->isValid()){
                $user->exchangeArray($form->getData());
                $this->getUserList()->createUser($user);
            }
        }
        return array('form' => $form);
    }
    
    public function loginAction()
    {
        $form = new UserForm();
        $form->get('submit')->setValue('Sign in');
        return new Viewmodel(array('form' => $form));
    }
    
    public function logoutAction()
    {
        session_start();
        session_destroy();
        return $this->redirect()->toRoute('forum');
    }
    
    public function createAction()
    {
        // connect to database
        echo "Attempting to connect to database... ";
        $conn = mysqli_connect('localhost', 'root', 'root', 'scotchbox');
        // Check connection
        if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error) . ".<br>"; }
        else { echo "Connection successful.<br>"; }
        
        $request=$this->getRequest();
        if ($request->isPost())  {
            $del = $request->getPost('del', null);
            if($del == 'Yes') {
                echo "Deleting original table...";
                $sql = 'DROP TABLE users';
                if ($conn->query($sql) === TRUE) { echo "Original table deleted.<br>"; }
                else {
                    echo "Error deleting table: " . $conn->error . ".<br>";
                }
        
            $sql = 'CREATE TABLE users(user_id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY, username VARCHAR(15) NOT NULL UNIQUE, password VARCHAR(15) NOT NULL, level BOOLEAN)';
            echo "Attempting to create forum table... ";
            if ($conn->query($sql) === TRUE) { echo "Table created successfully.<br>"; }
            else {
                echo "Error creating table: " . $conn->error . ".<br>";
                 }
            }
        }
        $conn->close();

        return new ViewModel();
    }
    
    public function listAction()
    {
        return new ViewModel(array(
                                   'userlist' => $this->getUserList()->fetchAll(),
                                   ));
    }
    
    public function getUserList()
    {
        if (!$this->UserList) {
            $sm = $this->getServiceLocator();
            $this->UserList = $sm->get('Forum\Model\UserList');
        }
        return $this->UserList;
    }
}