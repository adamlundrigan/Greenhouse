<?php
namespace Greenhouse\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class SensorReading extends AbstractdbMapper
{
    protected $tableName = 'sensor_reading';

    public function findLatestBySensorId($sensor_id, $count = 1)
    {
        $select = $this->select();
        $select->from($this->tableName)
               ->where(array('sensor_id' => $sensor_id))
               ->order('date_time DESC')->limit($count);
        $resultset = $this->selectWith($select);
        return $resultset;
    }

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        $result = parent::insert($entity,$tableName,$hydrator);
        $entity->setId($result->getGeneratedValue());
        return $result;
    }

}
