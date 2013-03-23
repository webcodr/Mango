<?php

namespace Mango\Types;

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
        if($value instanceof \DateTime) {
            $value = $value->getTimestamp();
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
        return new \DateTime("@{$this->value}");
    }
}