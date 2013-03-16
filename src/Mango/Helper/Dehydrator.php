<?php

namespace Mango\Helper;

/**
 * Class Dehydrator
 * @package Mango\Helper
 */

class Dehydrator
{
    /**
     * Dehydrate a value on the basis of its type
     *
     * @param $value
     * @param null $type
     * @param null $default
     * @return mixed
     */

    public function dehydrate($value, $type = null, $default = null)
    {
        switch ($type) {
            case 'DateTime':
                $value = $this->getMongoDate($value, $default);
                break;

            case 'Id':
                $value = $this->getMongoId($value);
                break;
        }

        return $value;
    }

    /**
     * Get a MongoId object of the value
     *
     * @param $value
     * @return \MongoId
     */

    private function getMongoId($value)
    {
        if (!$value instanceof \MongoId) {
            $value = new \MongoId($value);
        }

        return $value;
    }

    /**
     * Get a MongoDate object from the value (DateTime object) or set the default value from the field config
     *
     * @param $value
     * @param $default
     * @return \MongoDate
     */

    private function getMongoDate($value, $default)
    {
        if (!$value instanceof \MongoDate) {
            if ($value instanceof \DateTime) {
                $value = $value->getTimestamp();
            }

            if (empty($value)) {
                $date = new \DateTime($default);
                $value = $date->getTimestamp();
            }

            $value = new \MongoDate($value);
        }

        return $value;
    }
}