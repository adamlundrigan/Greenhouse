<?php

namespace Greenhouse\Mapper;

use Zend\Stdlib\Hydrator\ClassMethods;
use Greenhouse\Entity\Sensor as Entity;

class SensorHydrator extends ClassMethods
{
    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object)
    {
        if (!$object instanceof Entity) {
            throw new Exception\InvalidArgumentException('$object must be an instance of Greenhouse\Entity\Sensor');
        }
        /* @var $object Entity*/
        $data = parent::extract($object);
        unset($data['latest_reading']);
        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return Entity
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Entity) {
            throw new Exception\InvalidArgumentException('$object must be an instance of Greenhouse\Entity\Sensor');
        }
        unset($data['latest_reading']);
        return parent::hydrate($data, $object);
    }
}
