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

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'formatDateInterval' => 'Greenhouse\View\Helper\FormatDateInterval',
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'gh_database_adapter' => 'Zend\Db\Adapter\Adapter',
            ),
            'factories' => array(
                'gh_sensor_mapper' => function ($sm) {
                    $mapper = new Mapper\Sensor();
                    $mapper->setDbAdapter($sm->get('gh_database_adapter'));
                    $mapper->setEntityPrototype(new Entity\Sensor);
                    $mapper->setHydrator(new Mapper\SensorHydrator());
                    return $mapper;
                },
                'gh_sensor_service' => function ($sm) {
                    $service = new Service\Sensor();
                    $service->setSensorMapper($sm->get('gh_sensor_mapper'));
                    $service->setSensorReadingMapper($sm->get('gh_sensor_reading_mapper'));
                    return $service;
                },
                'gh_sensor_reading_mapper' => function ($sm) {
                    $mapper = new Mapper\SensorReading();
                    $mapper->setDbAdapter($sm->get('gh_database_adapter'));
                    $mapper->setEntityPrototype(new Entity\SensorReading);
                    $mapper->setHydrator(new Mapper\SensorReadingHydrator());
                    return $mapper;
                },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
