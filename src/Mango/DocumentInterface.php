<?php

namespace Mango;

interface DocumentInterface {
    public function getDatabase();
    public function getCollectionName();
    public function getProperties();
}