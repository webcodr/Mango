<?php

namespace Mango;

interface DocumentInterface {
    public static function getCollectionName();
    public function getProperties();
}