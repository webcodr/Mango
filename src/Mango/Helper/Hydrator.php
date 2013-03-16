<?php

namespace Mango\Helper;

class Hydrator
{
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

    private function getId($value)
    {
        if ($value instanceof \MongoId) {
            $value = (string)$value;
        }

        return $value;
    }

    private function getDate($value)
    {
        if ($value instanceof \MongoDate) {
            $value = new \DateTime("@{$value->sec}");
        }

        return $value;
    }
}