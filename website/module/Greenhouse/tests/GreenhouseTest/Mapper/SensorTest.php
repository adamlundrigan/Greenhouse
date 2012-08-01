<?php
namespace GreenhouseTest\Mapper;

use PHPUnit_Framework_TestCase;
use GreenhouseTest\Bootstrap;
use Greenhouse\Mapper\Sensor as SensorMapper;
use Greenhouse\Entity\Sensor;

class SensorTest extends PHPUnit_Framework_TestCase
{
    protected $mapper;

    public function setup()
    {
        $this->mapper = Bootstrap::getServiceManager()->get('gh_sensor_mapper');
        $sql = file_get_contents('data/sql/sensor.sql');
        $this->mapper->getDbAdapter()->query($sql)->execute();
    }

    protected function insertDummySensor()
    {
        $sensor = new Sensor;
        $sensor->setCode('TEMP1')
               ->setType('TEMP')
               ->setPositionX(10)
               ->setPositionY(25);
        $this->mapper->insert($sensor);
        return $sensor;
    }

    public function testCanInsertSensor()
    {
        $result = $this->mapper->findByCode('TEMP1');
        $this->assertFalse($result);

        $sensor = $this->insertDummySensor();
        $this->assertEquals('TEMP1', $sensor->getCode());

        $result = $this->mapper->findByCode('TEMP1');
        $this->assertInstanceOf('Greenhouse\Entity\Sensor', $result);
    }
}
