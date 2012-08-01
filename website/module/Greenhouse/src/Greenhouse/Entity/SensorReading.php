<?php
namespace Greenhouse\Entity;

class SensorReading
{
    protected $id;
    protected $sensor_id;
    protected $value;
    protected $date_time;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; return $this; }

    public function getSensorId() { return $this->sensor_id; }
    public function setSensorId($sid) { $this->sensor_id = $sid; return $this; }

    public function getValue() { return $this->value; }
    public function setValue($value) { $this->value = $value; return $this; }

    public function getDateTime() { return $this->date_time; }
    public function setDateTime(\DateTime $value) { $this->date_time = $value; return $this; }
}
