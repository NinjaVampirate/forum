<?php
return array(
     //'service_manager' => array( /** ServiceManager Config */ ),
     'controllers' => array(
            'invokables' => array(
                  'Forum\Controller\Forum' => 'Forum\Controller\ForumController',
                  'Forum\Controller\User' => 'Forum\Controller\UserController',
                  ),
            ),
             
             
     'router' => array(
           'routes' => array(
                 'forum' => array(
                      'type'    => 'segment',
                      'options' => array(
                         'route'    => '/forum[/:action][/:id]',
                         'defaults' => array(
                                     'controller' => 'Forum\Controller\Forum',
                                     'action'     => 'index',
                                     ),
                        ),
       ),
                             'thread' => array(
                                   'type' => 'segment',
                                   'options' => array(
                                          'route'    => '/forum/thread/:id',
                                          'defaults' => array(
                                                      'controller' => 'Forum\Controller\Forum',
                                                      'action' => 'thread'
                                                      ),
                                           ),

                                ),
                             'user' => array(
                                    'type' => 'segment',
                                    'options' => array(
                                                'route' => '/user[/:action]',
                                                       'defaults' => array(
                                                                           'controller' => 'Forum\Controller\User',
                                                                           //'action'     => 'index',
                                                                            ),
                                                       ),
                             
                             
                                             ),
                             ),
                    ),
             
     'view_manager' => array(
             'template_path_stack' => array(
                  'forum' => __DIR__ . '/../view',
                  'user' => __DIR__ . '/../view',
                  ),
             ),
     );