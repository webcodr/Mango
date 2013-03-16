<?php

namespace Mango\Helper;

class Dehydrator
{
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

    private function getMongoId($value)
    {
        if (!$value instanceof \MongoId) {
            $value = new \MongoId($value);
        }

        return $value;
    }

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