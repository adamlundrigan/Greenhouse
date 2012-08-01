<?php
namespace Greenhouse\Entity;

class Sensor
{
    protected $id;
    protected $code;
    protected $type;
    protected $position_x;
    protected $position_y;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; return $this; }

    public function getCode() { return $this->code; }
    public function setCode($code) { $this->code = $code; return $this; }

    public function getType() { return $this->type; }
    public function setType($type) { $this->type = $type; return $this; }

    public function getPositionX() { return $this->position_x; }
    public function setPositionX($x) { $this->position_x = $x; return $this; }

    public function getPositionY() { return $this->position_y; }
    public function setPositionY($y) { $this->position_y = $y; return $this; }

}
