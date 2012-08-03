<?php
namespace Greenhouse\Service;

use Greenhouse\Mapper\Sensor as SensorMapper;
use Greenhouse\Mapper\SensorReading as SensorReadingMapper;

class Sensor
{
    protected $sensorMapper;
    protected $sensorReadingMapper;

    public function fetchAll()
    {
        $dbresult  = $this->getSensorMapper()->fetchAll();
        $resultset = array();
        foreach ( $dbresult as $result )
        {
            $readings = $this->getSensorReadingMapper()->findLatestBySensorId($result->getId());
            if ( count($readings) > 0 )
            {        
                $result->setLatestReading($readings->current());
            }
            $resultset[] = $result;
        }
        return $resultset;
    }

    public function setSensorMapper(SensorMapper $mapper)
    {
        $this->sensorMapper = $mapper;
        return $this;
    }

    public function getSensorMapper()
    {
        return $this->sensorMapper;
    }

    public function setSensorReadingMapper(SensorReadingMapper $mapper)
    {
        $this->sensorReadingMapper = $mapper;
        return $this;
    }

    public function getSensorReadingMapper()
    {
        return $this->sensorReadingMapper;
    }

}
