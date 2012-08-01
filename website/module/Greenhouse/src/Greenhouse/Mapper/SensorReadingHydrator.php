<?php

namespace Greenhouse\Mapper;

use Zend\Stdlib\Hydrator\ClassMethods;
use Greenhouse\Entity\SensorReading as Entity;

class SensorReadingHydrator extends ClassMethods
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
            throw new Exception\InvalidArgumentException('$object must be an instance of Greenhouse\Entity\SensorReading');
        }
        /* @var $object Entity*/
        $data = parent::extract($object);
        if ( $data['date_time'] instanceof \DateTime ) {
            $data['date_time'] = $data['date_time']->format('Y-m-d H:i:s');
        }
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
            throw new Exception\InvalidArgumentException('$object must be an instance of Greenhouse\Entity\SensorReading');
        }
        $data['date_time'] = new \DateTime($data['date_time']);
        return parent::hydrate($data, $object);
    }
}
