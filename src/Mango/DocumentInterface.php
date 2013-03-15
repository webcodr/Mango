<?php

namespace Mango;

interface DocumentInterface {
    public function getDatabase();
    public function getCollection();
    public function getProperties();
}