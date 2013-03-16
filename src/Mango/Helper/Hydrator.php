<?php

namespace Mango\Helper;

/**
 * Class Hydrator
 * @package Mango\Helper
 */

class Hydrator
{
    /**
     * Hydrate a value on the basis of its type
     *
     * @param $value
     * @param null $type
     * @return mixed
     */

    public function hydrate($value, $type = null)
    {
        switch ($type) {
            case 'DateTime':
                $value = $this->getDate($value);
                break;

            case 'Id':
                $value = $this->getId($value);
                break;
        }

        return $value;
    }

    /**
     * Get the string representation of a MongoId object
     *
     * @param $value
     * @return string
     */

    private function getId($value)
    {
        if ($value instanceof \MongoId) {
            $value = (string)$value;
        }

        return $value;
    }

    /**
     * Convert a MongoDate object in a DateTime object
     *
     * @param $value
     * @return \DateTime
     */

    private function getDate($value)
    {
        if ($value instanceof \MongoDate) {
            $value = new \DateTime("@{$value->sec}");
        }

        return $value;
    }
}