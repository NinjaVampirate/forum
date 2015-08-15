<?php
namespace Forum\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ForumController extends AbstractActionController
{
    public function indexAction()
    {
        return new viewModel();
    }
    
    public function addAction()
    {
        return new viewModel();
    }
    
    public function editAction()
    {
        return new viewModel();
    }
    
    public function deleteAction()
    {
        return new viewModel();
    }
   /*public function createAction()
    {
        return viewModel();
       /* $servername = "localhost:10081";
        $username = "admin";
        $password = "root";
        
        echo "herp";
        // Create connection
        $conn = mysql_connect($servername, $username, $password);
        
        echo "derp";
        // Check connection
        if (!$conn) {
            echo "Connection failed";
        }
        else{
            echo "Connection successful";
        }
    }*/
}