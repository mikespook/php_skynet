<?php
namespace Skynet;
/**
 *  Skynet Socket
 */
class Socket {
    // raw socket handle
    private $_socket = null;  
    // Skynet host
    private $_host = '127.0.0.1';
    // Skynet port
    private $_port = '9001';
    // Socket params, see @http://www.php.net/manual/en/function.socket-get-option.php
    private $_params = array(
        SO_RCVTIMEO => array("sec"=>0, "usec"=>10),
        SO_SNDTIMEO => array("sec"=>10, "usec" => 0)
    );

    function __construct($host = '127.0.0.1', $port = '9001', $params = array()) {
        $nport = getservbyname($port, 'tcp');
        $this->_port = empty($nport) ? $port : $nport;
        $this->_host = gethostbyname($host);
        $this->_params += $params;
        $this->_connect();
    }

    function __destruct() {
        socket_close($this->_socket);
    }

    private function _connect() {
        $this->_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($this->_socket === false) {
            throw new Exception(socket_strerror(socket_last_error()));
        }
        foreach($this->_params as $key => $val) {
            if (!socket_set_option($this->_socket, SOL_SOCKET, $key, $val)) {
                throw new Exception(socket_strerror(socket_last_error()));
            }
        }
        if (!socket_connect($this->_socket, $this->_host, $this->_port)) {
            throw new Exception(socket_strerror(socket_last_error($this->_socket)));
        }
    }

    public function readBsonDoc() {
        $bsonlen = socket_read($this->_socket, 4);
        if ($bsonlen === false) {
            throw new Exception(socket_strerror(socket_last_error($this->_socket)));
        }
        $l = unpack('V', $bsonlen);
        $bsondata = socket_read($this->_socket, $l[1] - 4);
        return bson_decode($bsonlen . $bsondata);
    }

    public function writeBsonDoc($doc) {
        $bsondata = bson_encode($doc);
        $bsonlen = strlen($bsondata); 
        while(true) { 
            $sent = socket_write($this->_socket, $bsondata, $bsonlen); 
            if($sent === false) { 
                throw new Exception(socket_strerror(socket_last_error($this->_socket)));
            } 
            if($sent < $bsonlen) { 
                $bsondata = substr($bsondata, $sent); 
                $bsonlen -= $sent; 
            } else { 
                break; 
            } 
        }
    }

    public function handshake() {
        $serviceHandshake = $this->readBsonDoc();
        $clientHandshake = array(
            'clientid' => $serviceHandshake['clientid'],
        ); 
        $this->writeBsonDoc($clientHandshake);
        return $serviceHandshake; 
    }

    public function close() {
        socket_shutdown($this->_socket);
    }
}
