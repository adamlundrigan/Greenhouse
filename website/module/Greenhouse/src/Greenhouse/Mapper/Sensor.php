<?php
namespace Greenhouse\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class Sensor extends AbstractdbMapper
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
}
