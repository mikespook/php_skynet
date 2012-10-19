<?php
namespace Skynet;
/**
 *  Skynet Registry
 */
class Registry {
    // Doozer services path, `*` as a wildcard
    const DOOZER_SERVICES_PATH = '/services/*/*/*/*/*';

    // Doozer client
    private $_doozer = null;

    // connect to Doozer
    function __construct($host = '127.0.0.1', $port = '8046', $params = array()) {
        $this->_doozer = new Doozer\Client($host, $port, $params);
    }

    // close the connection
    function __destruct() {
        $this->_doozer->close();
    }

    // get all services
    public function getServices() {
        $rst = array();
        $services = $this->_doozer->walk(self::DOOZER_SERVICES_PATH);
        foreach($services as $service) {
            $rst[$service->getPath()] = json_decode($service->getValue(), true);
        }
        return $rst;
    }
}
require("Doozer/Exception.php");
require("Doozer/Client.php");

try {
    $r = new Registry();
    var_dump($r->getServices());
} catch (Skynet\Doozer\Exception $e) {
    var_dump($e);
}
