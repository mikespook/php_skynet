<?php

namespace Skynet\Cache;

class Null implements Cache {

    function __construct($params = array()) {}

    public function has($key) {
        return false;
    }

    public function get($key) {
        return NULL;
    }

    public function set($key, $value) {}

    public function delete($key) {
        return NULL;
    }
}
