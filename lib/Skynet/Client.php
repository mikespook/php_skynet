<?php
namespace Skynet;
/**
 * Client for Skynet
 */
class Client {
    // sockets
    private $_socket = null;
    // Service name
    private $_serviceName;
    // Service version, '*' by default, means all versions are allowed
    private $_version = '*';
    // Service region, 'development' by default
    private $_region = 'development';
    private $_registered = false;
    private $_clientId = '';
    private $_services = array();
    private $_doozerHost = '127.0.0.1';
    private $_doozerPort = '8046';
    private $_cache = '\Skynet\Cache\File';
    private $_params = array();

    /**
     * New a client
     * @serviceName 
     * @params version, region for Skynet, and socket params
     */
    function __construct($serviceName='skydaemon', $params = array()) {
        $this->_serviceName = $serviceName;
        if (isset($params['version'])) {
            $this->_version = $params['version'];
            unset($params['version']);
        }        
        if (isset($params['region'])) {
            $this->_region = $params['region'];
            unset($params['region']);          
        }
        if (isset($params['doozer_host'])) {
            $this->_doozerHost = $params['doozer_host'];
            unset($params['doozer_host']);          
        }
        if (isset($params['doozer_port'])) {
            $this->_doozerPort = $params['doozer_port'];
            unset($params['doozer_port']);          
        }
        if (isset($params['cache'])) {
            $this->_cache = $params['cache'];
            unset($params['cache']);          
        }
        // Get cache
        $this->_loadServicesCache();
        // Select a service by serviceName, version, region
        $sky = $this->_selectSkynet();
        $this->_socket = new Socket($sky['host'], $sky['port'], $params);
        $handshake = $this->_socket->handshake();
        $this->_registered = $handshake['registered'];
        $this->_clientId = $handshake['clientid'];
        $this->_params = $params;
    }

    private function _selectSkynet() {
        $service = $this->_services[array_rand($this->_services)];
        return array(
            'host' => $service['Config']['ServiceAddr']['IPAddress'],
            'port' => $service['Config']['ServiceAddr']['Port'],
        );
    }

    private function _loadServicesCache() {
        $cache = new $this->_cache;
        if ($cache->has('services')) {
            $this->_services = $cache->get('services');
        } else {
            // Connect to Doozer
            $registry = new Registry($this->_doozerHost, $this->_doozerPort, $this->_params);
            $this->_services = $registry->getServices();
            $cache->set('services', $this->_services);
        }
    }

    public function Call($methodName, $params) {
        $reqId = new MongoId(); 
        $header = array(
            'servicemethod' => "{$this->_serviceName}.Forward",
            'seq' => $seq,
        );
        $this->_socket->writeBsonDoc($header);
        $request = array(
            'clientid'  => (string)$reqId,
            'in'        => bson_encode($params),
            'method'    => $methodName,
            'requestinfo'   =>  array(
                'requestid' => reqId,
                'retrycount' => 0,
                'originaddress' => '',
            ),
        );
        $this->_socket->writeBsonDoc($request);
        $header = $this->_socket->readBsonDoc();
        $response = $this->_socket->readBsonDoc();
        if (isset($header['seq'])) {
            if (seq != $header['seq']) {
                throw Skynet_Exception("Incorrect Response received, expected seq={$seq}, received: {$header['inspect']}");
            }
        }else {
            throw Skynet_Exception("Invalid Response header, missing 'seq': {$header['inspect']}");
        } 
        // socket.user_data += 1
        # If an error is returned from Skynet raise a Skynet exception
        if (!empty($header['error'])) {
            throw Skynet_Exception($header['error']);
        }

        # If an error is returned from the service raise a Service exception
        if (!empty($response['error'])) {
            throw Skynet_Exception($response['error']);
        }
        # The return value is inside the response object, it's a byte array of it's own and needs to be deserialized
        return bson_decode($response['out']);
    }
}
