<?php
namespace Forum;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Forum\Model\Thread;
use Forum\Model\ThreadList;
use Forum\Model\Post;
use Forum\Model\PostList;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
                     'Zend\Loader\ClassMapAutoloader' => array(
                                                  __DIR__ . '/autoload_classmap.php',
                                                ),
                     'Zend\Loader\StandardAutoloader' => array(
                                                'namespaces' => array(
                                                                        __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                                                                        ),
                                                ),
                    );
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
                     'factories' => array(
                                          'Forum\Model\ThreadList' =>  function($sm) {
                                          $tableGateway = $sm->get('ThreadListGateway');
                                          $table = new ThreadList($tableGateway);
                                          return $table;
                                          },
                                          'ThreadListGateway' => function ($sm) {
                                          $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                                          $resultSetPrototype = new ResultSet();
                                          $resultSetPrototype->setArrayObjectPrototype(new Thread());
                                          return new TableGateway('forum', $dbAdapter, null, $resultSetPrototype);
                                          },
                                          
                                          'Forum\Model\PostList' =>  function($sm) {
                                          $tableGateway = $sm->get('PostListGateway');
                                          $table = new PostList($tableGateway);
                                          return $table;
                                          },
                                          'PostListGateway' => function ($sm) {
                                          $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                                          $resultSetPrototype = new ResultSet();
                                          $resultSetPrototype->setArrayObjectPrototype(new Post());
                                          return new TableGateway('posts', $dbAdapter, null, $resultSetPrototype);
                                          },
                                          
                                          ),
                     );
    }
}