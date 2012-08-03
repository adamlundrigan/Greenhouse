<?php
namespace GreenhouseTest\TestAsset;

use Greenhouse\Entity\Sensor as SensorEntity;
use Greenhouse\Entity\SensorReading as SensorReadingEntity;
use Greenhouse\Mapper\SensorReading as SensorReadingMapper;

class GenerateFakeSensorReadings
{
    protected $sensor;

    public function __construct(SensorEntity $s)
    {
        $this->setSensor($s);
    }

    public function setSensor(SensorEntity $s)
    {
        $this->sensor = $s;
        return $this;
    }

    public function getSensor()
    {
        return $s;
    }

    public function generate($mapper = NULL)
    {
        $resultset = array();
        for ( $i = 0 ; $i < (60 * 24 * 180) ; $i++ )
        {
            $datetime = new \DateTime();
            $entity = new SensorReadingEntity();
            $entity->setSensorId($this->getSensor()->getId())
                   ->setValue( mt_rand(1200,3500) / 100 )
                   ->setDateTime( $datetime->sub(new \DateInterval('PT'.$i.'M')));
            $resultset[] = $entity;
            if ( $mapper instanceof SensorReadingMapper ) 
            {
                $mapper->insert($entity);
            }
        }
        return $resultset;
    }
}
