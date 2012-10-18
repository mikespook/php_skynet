<?php
namespace Skynet;
/**
 *  Skynet Registry
 */
class Registry {
    const DOOZER_SERVICES_PATH = '/services/*/*/*/*/*';

    // Doozer client
    private $_doozer = null;

    function __construct($host = '127.0.0.1', $port = '8046', $params = array()) {
        $this->_doozer = new Doozer\Client($host, $port, $params);
    }

    function __destruct() {
        $this->_doozer->close();
    }

    public function _services() {
        $services = $this->_doozer->walk(self::DOOZER_SERVICES_PATH);
        return $services;
    }
    
}
require("Doozer/Exception.php");
require("Doozer/Client.php");

try {
    $r = new Registry();
    var_dump($r->_services());
} catch (Skynet\Doozer\Exception $e) {
    var_dump($e);
}
