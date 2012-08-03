<?php
namespace Greenhouse\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Zend\Stdlib\Hydrator\HydratorInterface;

class Sensor extends AbstractDbMapper
{
    protected $tableName = 'sensor';

    public function findByCode($code)
    {
        $select = $this->select();
        $select->from($this->tableName)
               ->where(array('code' => $code));
        $entity = $this->selectWith($select)->current();
        return $entity;
    }

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        $result = parent::insert($entity,$tableName,$hydrator);
        $entity->setId($result->getGeneratedValue());
        return $result;
    }
}
