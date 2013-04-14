<?php

namespace Mango\Type;

class Date implements TypeInterface
{
    private $value;

    /**
     * Constructor
     *
     * @param null $value
     */

    public function __construct($value = null)
    {
        if ($value instanceof \DateTime) {
            $value = $value->getTimestamp();
        } elseif ($value instanceof \MongoDate) {
            $value = $value->sec;
        } else {
            $timestamp = strtotime($value);

            if ($timestamp !== false) {
                $value = $timestamp;
            }
        }

        $this->value = $value;
    }

    /**
     * Get MongoDate object
     *
     * @return \MongoDate
     */

    public function getMongoType()
    {
        return new \MongoDate($this->value);
    }

    /**
     * Get DateTime object
     *
     * @return \DateTime
     */

    public function getValue()
    {
        if ($this->value !== null) {
            return new \DateTime("@{$this->value}");
        }

        return null;
    }
}