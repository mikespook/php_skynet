<?php

namespace Skynet\Cache;

interface Cache {
    public function has($key);
    public function get($key);
    public function set($key, $value);
    public function delete($key);
}
