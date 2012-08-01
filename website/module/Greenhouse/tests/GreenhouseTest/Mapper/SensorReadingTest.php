<?php
namespace GreenhouseTest\Mapper;

use PHPUnit_Framework_TestCase;
use GreenhouseTest\Bootstrap;
use Greenhouse\Mapper\SensorReading as SensorReadingMapper;
use Greenhouse\Entity\SensorReading;

class SensorReadingTest extends PHPUnit_Framework_TestCase
{
    protected $mapper;

    public function setup()
    {
        $this->mapper = Bootstrap::getServiceManager()->get('gh_sensor_reading_mapper');
        $sql = file_get_contents('data/sql/sensor_reading.sql');
        $this->mapper->getDbAdapter()->query($sql)->execute();
    }

    protected function insertDummySensorReading()
    {
        $reading = new SensorReading;
        $reading->getSensorId('1')
               ->setValue(22.22)
               ->setDateTime(new \DateTime());
        $this->mapper->insert($reading);
        return $reading;
    }

    public function testCanInsertSensorReading()
    {
        $result = $this->mapper->findLatestBySensorId(1);
        $this->assertFalse($result);

        $reading = $this->insertDummySensorReading();
        $this->assertEquals(1, $reading->getId());

        $result = $this->mapper->findLatestBySensorId(1);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf('Greenhouse\Entity\SensorReading', $result->current());
    }
}
