<?php
return array(
   
       /*      'db' => array(
                           'driver'         => 'Pdo',
                           'username'       => 'admin',  //edit this
                           'password'       => 'root',  //edit this
                           'dsn'            => 'mysql:dbname=zend;host=localhost',
                           'driver_options' => array(
                                                     \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                                                     )
                           ),
             */
             
     //'service_manager' => array( /** ServiceManager Config */ ),
             
             
     'controllers' => array(
            'invokables' => array(
                  'Forum\Controller\Forum' => 'Forum\Controller\ForumController',
                  ),
            ),
             
             
     'router' => array(
           'routes' => array(
                 'album' => array(
                      'type'    => 'segment',
                      'options' => array(
                         'route'    => '/forum[/:action][/:id]',
                         'constraints' => array(
                                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id'     => '[0-9]+',
                                    ),
                         'defaults' => array(
                                     'controller' => 'Forum\Controller\Forum',
                                     'action'     => 'index',
                                     ),
                              ),
                      ),
              ),
       ),
             
             
     'view_manager' => array(
             'template_path_stack' => array(
                  'album' => __DIR__ . '/../view',
                  ),
             ),
     );