<?php

namespace Skynet\Cache;

class File {
    private $_base = '';

    function __construct($params = array()) {
        $this->_base = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
    }

    public function has($key) {
        return is_file($this->_base . $key . '.cache');
    }

    public function get($key) {
        return unserialize(file_get_contents($this->_base . $key . '.cache'));
    }

    public function set($key, $value) {
        file_put_contents($this->_base . $key . '.cache', serialize($value));
    }

    public function delete($key) {
        $value = $this->get($key);
        unlink($this->_base . $key . '.cache');
        return $value;
    }
}
