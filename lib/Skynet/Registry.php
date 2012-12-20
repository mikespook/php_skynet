<?php
namespace Skynet;
/**
 *  Skynet Registry
 */
class Registry {
    // Doozer services path, `*` as a wildcard
    const DOOZER_SERVICES_PATH = '/services';

    // Doozer client
    private $_doozer = null;

    // connect to Doozer
    function __construct($host = '127.0.0.1', $port = '8046', $params = array()) {
        $this->_doozer = new \Doozer();
        $this->_doozer->connect($host, $port, $params);
    }

    // close the connection
    function __destruct() {
        $this->_doozer->close();
    }

    // get all services
    public function getServices() {
        $rst = array();
        $services = $this->_doozer->walk(self::DOOZER_SERVICES_PATH);
        foreach($services as $key => $service) {
            $rst[$key] = json_decode($service, true);
        }
        return $rst;
    }
}
