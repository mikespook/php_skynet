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
    private $_params = array(
        SO_RCVTIMEO => array("sec"=>0, "usec"=>10),
        SO_SNDTIMEO => array("sec"=>10, "usec" => 0)
    );

    //Create a resilient client connection to a Doozer server
    function __construct($host = '127.0.0.1', $port = '8046', $params = array()) {
        $nport = getservbyname($port, 'tcp');
        $this->_port = empty($nport) ? $port : $nport;
        $this->_host = gethostbyname($host);
        $this->_params = $this->_params + $params;
        $this->_connect();
    }  

    // Close this client connection to doozer
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
        $data = pack('N', $len) . $data;
        
        while(true) { 
            $sent = socket_write($this->_socket, $data, strlen($data)); 
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

    private function _invoke($request) {
        $this->_write($request);
        $response = $this->_read();
        $errCode =$response->getErrCode();
        if ($errCode != 0) {
            throw new Exception($response->getErrDetail(), $errCode);
        }
        return $response;
    }

    // Shutdown this client connection to doozer
    public function close() {
        socket_shutdown($this->_socket);
    }

    // Returns the current Doozer revision
    public function currentRevision() {
        $request = new Request();
        $request->setVerb(Request\Verb::REV);
        $response = $this->_invoke($request);
        return $response->rev;
    }

    /**
     * Set a value in Doozer
     * @path:  Path to the value to be set
     * @value: Value to set
     * @rev:   Revision at which to set the value
     */
    public function set($path, $value, $rev=null) {
        $request = new Request();
        $request->setVerb(Request\Verb::SET);
        $request->setPath($path);
        $request->setValue($value);
        $request->setRev($rev);
        $response = $this->_invoke($request);
        return $response->rev;       
    }

    // Return the value at the supplied path and revision
    public function get($path, $rev=null) {
        $request = new Request();
        $request->setVerb(Request\Verb::GET);
        $request->setPath($path);
        $request->setRev($rev);
        $response = $this->_invoke($request);
        return $response->value;       
    }

    /**
     * Deletes the file at path if rev is greater than or equal to the file's revision.
     * Returns nil when the file was removed
     * Raises an exception if an attempt to remove the file and its revision
     *  is greater than that supplied
     */
    public function delete($path, $rev=null) {
        $request = new Request();
        $request->setVerb(Request\Verb::DEL);
        $request->setPath($path);
        $request->setRev($rev);
        $this->_write($request);
    }

    /**
     * Returns the directory in the supplied path
     * Use offset to get the next
     * returns nil if no further paths are available
     */
    public function dir($path, $offset=0, $rev=null) {
        $request = new Request();
        $request->setVerb(Request\Verb::GETDIR);
        $request->setPath($path);
        $request->setRev($rev);
        $request->setOffset($offset);
        $response = $this->_invoke($request);
        return $response->path;         
    }

    public function stat($path, $rev=null) {
        $request = new Request();
        $request->setVerb(Request\Verb::STAT);
        $request->setPath($path);
        $request->setRev($rev);
        $response = $this->_invoke($request);
        return $response;    
    }

    public function access($path) {
        $request = new Request();
        $request->setVerb(Request\Verb::ACCESS);
        $request->setPath($path);
        $response = $this->_invoke($request);
        return $response;    
    }

    /**
     * Returns every entry in the supplied path
     * path can also contain wildcard characters such as '*'
     */ 
    public function walk($path, $rev=null, $offset=0) {
        $paths = array();
        $rev = is_null($rev) ? $this->currentVersion() : $rev;
        while(true) {
            $request = new Request();
            $request->setVerb(Request\Verb::WALK);
            $request->setPath($path);
            $request->setRev($rev);
            $request->setOffset($offset);
            $this->_write($request);
            $response = $this->_read();
            $errCode = $response->getErrCode();
            if ($errCode == Response\Err::RANGE) {
                break;
            } else if ($errCode != 0) {
                throw new Exception($response->getErrDetail(), $errCode);
            }
            $paths[] = $response;
        }
        return $paths;
    }

    /**
     * Returns [Array] of hostname [String] with each string
     * representing another Doozer server that can be connected to
     */
    public function doozerHosts() {
        $hosts = array();
        $paths = $this->walk('/ctl/node/*/addr', $this->currentRevision());
        foreach($paths as $path) {
            if (!empty($path.value)) {
                $hosts[] = $path.value;
            }
        }
    }

    /**
     * Wait for changes to the supplied path
     * Returns the next change to the supplied path
     */
    public function wait($path, $rev = null) {
        $rev = is_null($rev) ? $this->currentRevision() : $rev;
        $request = new Request();
        $request->setVerb(Request\Verb::WAIT);
        $request->setPath($path);
        $request->setRev($rev);
        $response = $this->_invoke($request);
        return $response;
    }


}
