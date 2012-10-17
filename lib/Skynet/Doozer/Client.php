<?php
namespace Skynet\Doozer;

if (!defined('DRSLUMP_PROTOBUF')) {
    require('DrSlump/Protobuf.php');
    \DrSlump\Protobuf::autoload(); 
    define('DRSLUMP_PROTOBUF', true);
}
if (!defined('SKYNET_DOOZER_MSG')) {
    require('msg.pb.php');
    define('SKYNET_DOOZER_MSG', true);
}

class Client {
    // raw socket handle
    private $_socket = null;
    // Doozerd host
    private $_host = '127.0.0.1';
    // Doozerd port
    private $_port = '8046';
    // Socket params, see @http://www.php.net/manual/en/function.socket-get-option.php
    private $_params = array();
    
    function __construct($host = '127.0.0.1', $port = '8046', $params = array()) {
        $nport = getservbyname($port, 'tcp');
        $this->_port = empty($nport) ? $port : $nport;
        $this->_host = gethostbyname($host);
        $this->_params = $params;
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

    private function _read() {
        $data = '';
        while ($out = socket_read($this->_socket, 512)) {
            $data .= $out;
        }
        $response = new Response();
        $response->parse($data);
        return $response;
    }

    private function _write($request) {
        $request->setTag(0);
        $data = $request->serialize();
        $len = strlen($data);
        $data .= pack('N', $len);
        while(true) { 
            $sent = socket_write($this->_socket, $data, $len); 
            if($sent === false) { 
                throw new Exception(socket_strerror(socket_last_error($this->_socket)));
            } 
            if($sent < $len) { 
                $data = substr($data, $sent); 
                $len -= $sent; 
            } else { 
                break; 
            } 
        }
    }

    public function close() {
        socket_shutdown($this->_socket);
    }

    public function currentVersion() {
        $request = new Request();
        $request->setVerb(Request\Verb::REV);
        $this->_write($request);
        $response = $this->_read();
        return $response->rev;
    }
}

$c = new Client();
echo $c->currentVersion();
