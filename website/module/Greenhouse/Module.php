<?php
namespace Greenhouse;

use Zend\ModuleManager\ModuleManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface
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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'gh_sensor_hydrator' => function ($sm) {
                    $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
                    return $hydrator;
                },
                'gh_sensor_mapper' => function ($sm) {
                    $mapper = new Mapper\Sensor();
                    $mapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                    $mapper->setHydrator($sm->get('gh_sensor_hydrator'));
                    return $mapper;
                },
                'gh_sensor_service' => function ($sm) {
                    $service = new Sevice\Sensor();
                    $service->setSensorMapper($sm->get('gh_sensor_mapper'));
                    return $service;
                },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
