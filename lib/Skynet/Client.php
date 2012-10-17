<?php
namespace Skynet;
/**
 * Client for Skynet
 */
class Client {
    // sockets
    private $_sockets = array();
    // Service name
    private $_serviceName;
    // Service version, '*' by default, means all versions are allowed
    private $_version = '*';
    // Service region, 'development' by default
    private $_region = 'development';
    private $_registered = false;
    private $_clientId = '';

    /**
     * New a client
     * @serviceName 
     * @params version, region for Skynet, and socket params
     */
    function __construct($serviceName, $params) {
        if (isset($params['version'])) {
            $this->_version = $params['version'];
            unset($params['version']);
        }        
        if (isset($params['region'])) {
            $this->_region = $params['region'];
            unset($params['region']);          
        }
        $this->_connect($this->_host, $this->_port);
        $serviceHandshake = $this->_readBsonDoc();
        $this->_registered = $serviceHandshake['registered'];
        $this->_clientId = $serviceHandshake['clientid'];
        $clientHandshake = array(
            'clientid' => $this->_clientId,
        ); 
        this->_writeBsonDoc($clientHandshake);
    }

    public function Call($methodName, $params) {
        $reqId = new MongoId(); 
        $header = array(
            'servicemethod' => "{$this->_serviceName}.Forward",
            'seq' => $seq,
        );
        $this->_writeBsonDoc($header);
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
        $this->_writeBsonDoc($request);
        $header = $this->_readBsonDoc();
        $response = $this->_readBsonDoc();
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
