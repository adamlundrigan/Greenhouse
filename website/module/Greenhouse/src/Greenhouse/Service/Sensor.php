<?php
namespace Greenhouse\Service;

class Sensor
{
    protected $sensorMapper;

    public function setSensorMapper(SensorMapper $mapper)
    {
        $this->sensorMapper = $mapper;
        return $this;
    }

    public function getSensorMapper()
    {
        return $this->sensorMapper;
    }
}
