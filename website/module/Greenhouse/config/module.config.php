<?php
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Greenhouse\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'sensor' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/sensor[/:code]',
                    'constraints' => array(
                        'code' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Greenhouse\Controller\Sensor',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'graph' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/graph',
                            'defaults' => array(
                                'controller' => 'Greenhouse\Controller\Sensor',
                                'action'     => 'graph',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Greenhouse\Controller\Index' => 'Greenhouse\Controller\IndexController',
            'Greenhouse\Controller\Sensor' => 'Greenhouse\Controller\SensorController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'greenhouse/index/index' => __DIR__ . '/../view/greenhouse/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
