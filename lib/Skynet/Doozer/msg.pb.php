<?php
namespace Skynet\Doozer;
/** 
 * PHP Protocol Buffers generated from msg.proto
 *
 * @category   Protobuf
 * @package    Skynet.Doozer
 * @author     Andrew Brampton https://github.com/bramp
 * @author     Jeffrey Sambells <jsambells@wecreate.com>
 * @link       http://github.com/iamamused/protoc-gen-php
 */

/** @see Protobuf */
require_once 'Protobuf.php';
// enum Skynet.Doozer.Request.Verb
class Request_Verb {
  const GET = 1;
  const SET = 2;
  const DEL = 3;
  const REV = 5;
  const WAIT = 6;
  const NOP = 7;
  const WALK = 9;
  const GETDIR = 14;
  const STAT = 16;
  const ACCESS = 99;
  
  public static $_values = array(
    1 => self::GET,
    2 => self::SET,
    3 => self::DEL,
    5 => self::REV,
    6 => self::WAIT,
    7 => self::NOP,
    9 => self::WALK,
    14 => self::GETDIR,
    16 => self::STAT,
    99 => self::ACCESS,
  );
  
  public static function toString($value) {
    if (is_null($value)) return null;
    if (array_key_exists($value, self::$_values))
      return self::$_values[$value];
    return 'UNKNOWN';
  }
}

// message Skynet.Doozer.Request
class Request implements Protobuf_Interface {
  private $_unknown;
  
  function __construct($in = NULL, &$limit = PHP_INT_MAX) {
    if($in !== NULL) {
      if (is_string($in)) {
        $fp = fopen('php://memory', 'r+b');
        fwrite($fp, $in);
        rewind($fp);
      } else if (is_resource($in)) {
        $fp = $in;
      } else {
        throw new Exception('Invalid in parameter');
      }
      $this->read($fp, $limit);
    }
  }
  
  function read($fp, &$limit = PHP_INT_MAX) {
    while(!feof($fp) && $limit > 0) {
      $tag = Protobuf::read_varint($fp, $limit);
      if ($tag === false) break;
      $wire  = $tag & 0x07;
      $field = $tag >> 3;
      //var_dump("Skynet_Doozer_Request: Found $field type " . Protobuf::get_wiretype($wire) . " $limit bytes left");
      switch($field) {
        case 1:
          ASSERT('$wire == 0');
          $tmp = Protobuf::read_varint($fp, $limit);
          if ($tmp === false)
            throw new Exception('Protobuf::read_varint returned false');
          $this->tag_ = $tmp;
          
          break;
        case 2:
          ASSERT('$wire == 0');
          $tmp = Protobuf::read_varint($fp, $limit);
          if ($tmp === false)
            throw new Exception('Protobuf::read_varint returned false');
          $this->verb_ = $tmp;
          
          break;
        case 4:
          ASSERT('$wire == 2');
          $len = Protobuf::read_varint($fp, $limit);
          if ($len === false)
            throw new Exception('Protobuf::read_varint returned false');
          if ($len > 0)
            $tmp = fread($fp, $len);
          else
            $tmp = '';
          if ($tmp === false)
            throw new Exception("fread($len) returned false");
          $this->path_ = $tmp;
          $limit-=$len;
          break;
        case 5:
          ASSERT('$wire == 2');
          $len = Protobuf::read_varint($fp, $limit);
          if ($len === false)
            throw new Exception('Protobuf::read_varint returned false');
          if ($len > 0)
            $tmp = fread($fp, $len);
          else
            $tmp = '';
          if ($tmp === false)
            throw new Exception("fread($len) returned false");
          $this->value_ = $tmp;
          $limit-=$len;
          break;
        case 6:
          ASSERT('$wire == 0');
          $tmp = Protobuf::read_varint($fp, $limit);
          if ($tmp === false)
            throw new Exception('Protobuf::read_varint returned false');
          $this->otherTag_ = $tmp;
          
          break;
        case 7:
          ASSERT('$wire == 0');
          $tmp = Protobuf::read_varint($fp, $limit);
          if ($tmp === false)
            throw new Exception('Protobuf::read_varint returned false');
          $this->offset_ = $tmp;
          
          break;
        case 9:
          ASSERT('$wire == 0');
          $tmp = Protobuf::read_varint($fp, $limit);
          if ($tmp === false)
            throw new Exception('Protobuf::read_varint returned false');
          $this->rev_ = $tmp;
          
          break;
        default:
          $this->_unknown[$field . '-' . Protobuf::get_wiretype($wire)][] = Protobuf::read_field($fp, $wire, $limit);
      }
    }
    if (!$this->validateRequired())
      throw new Exception('Required field is missing [' . $this->_missingField . ']');
  }
  
  function write($fp) {
    if (!$this->validateRequired())
      throw new Exception('Required field is missing [' . $this->_missingField . ']');
    if (!is_null($this->tag_)) {
      fwrite($fp, "\x08");
      Protobuf::write_varint($fp, $this->tag_);
    }
    if (!is_null($this->verb_)) {
      fwrite($fp, "\x10");
      Protobuf::write_varint($fp, $this->verb_);
    }
    if (!is_null($this->path_)) {
      fwrite($fp, "\"");
      Protobuf::write_varint($fp, strlen($this->path_));
      fwrite($fp, $this->path_);
    }
    if (!is_null($this->value_)) {
      fwrite($fp, "*");
      Protobuf::write_varint($fp, strlen($this->value_));
      fwrite($fp, $this->value_);
    }
    if (!is_null($this->otherTag_)) {
      fwrite($fp, "0");
      Protobuf::write_varint($fp, $this->otherTag_);
    }
    if (!is_null($this->offset_)) {
      fwrite($fp, "8");
      Protobuf::write_varint($fp, $this->offset_);
    }
    if (!is_null($this->rev_)) {
      fwrite($fp, "H");
      Protobuf::write_varint($fp, $this->rev_);
    }
  }
  
  function getPbString() {
    $fp = fopen('php://memory', 'w+b');
    $this->write($fp);
    rewind($fp);
    $out = stream_get_contents($fp);
    fclose($fp);
    return $out;
  }
  
  public function size() {
    $size = 0;
    if (!is_null($this->tag_)) {
      $size += 1 + Protobuf::size_varint($this->tag_);
    }
    if (!is_null($this->verb_)) {
      $size += 1 + Protobuf::size_varint($this->verb_);
    }
    if (!is_null($this->path_)) {
      $l = strlen($this->path_);
      $size += 1 + Protobuf::size_varint($l) + $l;
    }
    if (!is_null($this->value_)) {
      $l = strlen($this->value_);
      $size += 1 + Protobuf::size_varint($l) + $l;
    }
    if (!is_null($this->otherTag_)) {
      $size += 1 + Protobuf::size_varint($this->otherTag_);
    }
    if (!is_null($this->offset_)) {
      $size += 1 + Protobuf::size_varint($this->offset_);
    }
    if (!is_null($this->rev_)) {
      $size += 1 + Protobuf::size_varint($this->rev_);
    }
    return $size;
  }
  
  private $_missingField;public function validateRequired() {
    $this->_missingField = '';
    return true;
  }
  
  public function __toString() {
    return ''
         . Protobuf::toString('unknown', $this->_unknown)
         . Protobuf::toString('tag_', $this->tag_)
         . Protobuf::toString('verb_', Request_Verb::toString($this->verb_))
         . Protobuf::toString('path_', $this->path_)
         . Protobuf::toString('value_', $this->value_)
         . Protobuf::toString('otherTag_', $this->otherTag_)
         . Protobuf::toString('offset_', $this->offset_)
         . Protobuf::toString('rev_', $this->rev_);
  }
  
  // optional int32 tag = 1;

  private $tag_ = null;
  public function clearTag() { $this->tag_ = null; }
  public function hasTag() { return $this->tag_ !== null; }
  public function getTag() { if($this->tag_ === null) return 0; else return $this->tag_; }
  public function setTag($value) { $this->tag_ = $value; }
  
  // optional .Skynet.Doozer.Request.Verb verb = 2;

  private $verb_ = null;
  public function clearVerb() { $this->verb_ = null; }
  public function hasVerb() { return $this->verb_ !== null; }
  public function getVerb() { if($this->verb_ === null) return Skynet_Doozer_Request_Verb::GET; else return $this->verb_; }
  public function setVerb($value) { $this->verb_ = $value; }
  
  // optional string path = 4;

  private $path_ = null;
  public function clearPath() { $this->path_ = null; }
  public function hasPath() { return $this->path_ !== null; }
  public function getPath() { if($this->path_ === null) return ""; else return $this->path_; }
  public function setPath($value) { $this->path_ = $value; }
  
  // optional bytes value = 5;

  private $value_ = null;
  public function clearValue() { $this->value_ = null; }
  public function hasValue() { return $this->value_ !== null; }
  public function getValue() { if($this->value_ === null) return ""; else return $this->value_; }
  public function setValue($value) { $this->value_ = $value; }
  
  // optional int32 other_tag = 6;

  private $otherTag_ = null;
  public function clearOtherTag() { $this->otherTag_ = null; }
  public function hasOtherTag() { return $this->otherTag_ !== null; }
  public function getOtherTag() { if($this->otherTag_ === null) return 0; else return $this->otherTag_; }
  public function setOtherTag($value) { $this->otherTag_ = $value; }
  
  // optional int32 offset = 7;

  private $offset_ = null;
  public function clearOffset() { $this->offset_ = null; }
  public function hasOffset() { return $this->offset_ !== null; }
  public function getOffset() { if($this->offset_ === null) return 0; else return $this->offset_; }
  public function setOffset($value) { $this->offset_ = $value; }
  
  // optional int64 rev = 9;

  private $rev_ = null;
  public function clearRev() { $this->rev_ = null; }
  public function hasRev() { return $this->rev_ !== null; }
  public function getRev() { if($this->rev_ === null) return 0; else return $this->rev_; }
  public function setRev($value) { $this->rev_ = $value; }
  
     function __get( $key ) {
         $m = 'get' . ucfirst($key);
         if (method_exists($this,$m)) return $this->$m();
         return null;
     }
     
     
     function __set( $key, $value ) {
         $m = 'set' . ucfirst($key);
         if (method_exists($this,$m)) return $this->$m($value);
     }
     
     
     function __call( $method, $args ) {
         $m = 'get' . ucfirst($method);
         if (method_exists($this,$m)) return $this->$m($args[0]);
         throw new Exception('Invalid method ' . $method);
     }
     
     
  // @@protoc_insertion_point(class_scope:Skynet.Doozer.Request)
}

// enum Skynet.Doozer.Response.Err
class Response_Err {
  const OTHER = 127;
  const TAG_IN_USE = 1;
  const UNKNOWN_VERB = 2;
  const READONLY = 3;
  const TOO_LATE = 4;
  const REV_MISMATCH = 5;
  const BAD_PATH = 6;
  const MISSING_ARG = 7;
  const RANGE = 8;
  const NOTDIR = 20;
  const ISDIR = 21;
  const NOENT = 22;
  
  public static $_values = array(
    127 => self::OTHER,
    1 => self::TAG_IN_USE,
    2 => self::UNKNOWN_VERB,
    3 => self::READONLY,
    4 => self::TOO_LATE,
    5 => self::REV_MISMATCH,
    6 => self::BAD_PATH,
    7 => self::MISSING_ARG,
    8 => self::RANGE,
    20 => self::NOTDIR,
    21 => self::ISDIR,
    22 => self::NOENT,
  );
  
  public static function toString($value) {
    if (is_null($value)) return null;
    if (array_key_exists($value, self::$_values))
      return self::$_values[$value];
    return 'UNKNOWN';
  }
}

// message Skynet.Doozer.Response
class Response implements Protobuf_Interface {
  private $_unknown;
  
  function __construct($in = NULL, &$limit = PHP_INT_MAX) {
    if($in !== NULL) {
      if (is_string($in)) {
        $fp = fopen('php://memory', 'r+b');
        fwrite($fp, $in);
        rewind($fp);
      } else if (is_resource($in)) {
        $fp = $in;
      } else {
        throw new Exception('Invalid in parameter');
      }
      $this->read($fp, $limit);
    }
  }
  
  function read($fp, &$limit = PHP_INT_MAX) {
    while(!feof($fp) && $limit > 0) {
      $tag = Protobuf::read_varint($fp, $limit);
      if ($tag === false) break;
      $wire  = $tag & 0x07;
      $field = $tag >> 3;
      //var_dump("Skynet_Doozer_Response: Found $field type " . Protobuf::get_wiretype($wire) . " $limit bytes left");
      switch($field) {
        case 1:
          ASSERT('$wire == 0');
          $tmp = Protobuf::read_varint($fp, $limit);
          if ($tmp === false)
            throw new Exception('Protobuf::read_varint returned false');
          $this->tag_ = $tmp;
          
          break;
        case 2:
          ASSERT('$wire == 0');
          $tmp = Protobuf::read_varint($fp, $limit);
          if ($tmp === false)
            throw new Exception('Protobuf::read_varint returned false');
          $this->flags_ = $tmp;
          
          break;
        case 3:
          ASSERT('$wire == 0');
          $tmp = Protobuf::read_varint($fp, $limit);
          if ($tmp === false)
            throw new Exception('Protobuf::read_varint returned false');
          $this->rev_ = $tmp;
          
          break;
        case 5:
          ASSERT('$wire == 2');
          $len = Protobuf::read_varint($fp, $limit);
          if ($len === false)
            throw new Exception('Protobuf::read_varint returned false');
          if ($len > 0)
            $tmp = fread($fp, $len);
          else
            $tmp = '';
          if ($tmp === false)
            throw new Exception("fread($len) returned false");
          $this->path_ = $tmp;
          $limit-=$len;
          break;
        case 6:
          ASSERT('$wire == 2');
          $len = Protobuf::read_varint($fp, $limit);
          if ($len === false)
            throw new Exception('Protobuf::read_varint returned false');
          if ($len > 0)
            $tmp = fread($fp, $len);
          else
            $tmp = '';
          if ($tmp === false)
            throw new Exception("fread($len) returned false");
          $this->value_ = $tmp;
          $limit-=$len;
          break;
        case 8:
          ASSERT('$wire == 0');
          $tmp = Protobuf::read_varint($fp, $limit);
          if ($tmp === false)
            throw new Exception('Protobuf::read_varint returned false');
          $this->len_ = $tmp;
          
          break;
        case 100:
          ASSERT('$wire == 0');
          $tmp = Protobuf::read_varint($fp, $limit);
          if ($tmp === false)
            throw new Exception('Protobuf::read_varint returned false');
          $this->errCode_ = $tmp;
          
          break;
        case 101:
          ASSERT('$wire == 2');
          $len = Protobuf::read_varint($fp, $limit);
          if ($len === false)
            throw new Exception('Protobuf::read_varint returned false');
          if ($len > 0)
            $tmp = fread($fp, $len);
          else
            $tmp = '';
          if ($tmp === false)
            throw new Exception("fread($len) returned false");
          $this->errDetail_ = $tmp;
          $limit-=$len;
          break;
        default:
          $this->_unknown[$field . '-' . Protobuf::get_wiretype($wire)][] = Protobuf::read_field($fp, $wire, $limit);
      }
    }
    if (!$this->validateRequired())
      throw new Exception('Required field is missing [' . $this->_missingField . ']');
  }
  
  function write($fp) {
    if (!$this->validateRequired())
      throw new Exception('Required field is missing [' . $this->_missingField . ']');
    if (!is_null($this->tag_)) {
      fwrite($fp, "\x08");
      Protobuf::write_varint($fp, $this->tag_);
    }
    if (!is_null($this->flags_)) {
      fwrite($fp, "\x10");
      Protobuf::write_varint($fp, $this->flags_);
    }
    if (!is_null($this->rev_)) {
      fwrite($fp, "\x18");
      Protobuf::write_varint($fp, $this->rev_);
    }
    if (!is_null($this->path_)) {
      fwrite($fp, "*");
      Protobuf::write_varint($fp, strlen($this->path_));
      fwrite($fp, $this->path_);
    }
    if (!is_null($this->value_)) {
      fwrite($fp, "2");
      Protobuf::write_varint($fp, strlen($this->value_));
      fwrite($fp, $this->value_);
    }
    if (!is_null($this->len_)) {
      fwrite($fp, "@");
      Protobuf::write_varint($fp, $this->len_);
    }
    if (!is_null($this->errCode_)) {
      fwrite($fp, "\xa0\x06");
      Protobuf::write_varint($fp, $this->errCode_);
    }
    if (!is_null($this->errDetail_)) {
      fwrite($fp, "\xaa\x06");
      Protobuf::write_varint($fp, strlen($this->errDetail_));
      fwrite($fp, $this->errDetail_);
    }
  }
  
  function getPbString() {
    $fp = fopen('php://memory', 'w+b');
    $this->write($fp);
    rewind($fp);
    $out = stream_get_contents($fp);
    fclose($fp);
    return $out;
  }
  
  public function size() {
    $size = 0;
    if (!is_null($this->tag_)) {
      $size += 1 + Protobuf::size_varint($this->tag_);
    }
    if (!is_null($this->flags_)) {
      $size += 1 + Protobuf::size_varint($this->flags_);
    }
    if (!is_null($this->rev_)) {
      $size += 1 + Protobuf::size_varint($this->rev_);
    }
    if (!is_null($this->path_)) {
      $l = strlen($this->path_);
      $size += 1 + Protobuf::size_varint($l) + $l;
    }
    if (!is_null($this->value_)) {
      $l = strlen($this->value_);
      $size += 1 + Protobuf::size_varint($l) + $l;
    }
    if (!is_null($this->len_)) {
      $size += 1 + Protobuf::size_varint($this->len_);
    }
    if (!is_null($this->errCode_)) {
      $size += 2 + Protobuf::size_varint($this->errCode_);
    }
    if (!is_null($this->errDetail_)) {
      $l = strlen($this->errDetail_);
      $size += 2 + Protobuf::size_varint($l) + $l;
    }
    return $size;
  }
  
  private $_missingField;public function validateRequired() {
    $this->_missingField = '';
    return true;
  }
  
  public function __toString() {
    return ''
         . Protobuf::toString('unknown', $this->_unknown)
         . Protobuf::toString('tag_', $this->tag_)
         . Protobuf::toString('flags_', $this->flags_)
         . Protobuf::toString('rev_', $this->rev_)
         . Protobuf::toString('path_', $this->path_)
         . Protobuf::toString('value_', $this->value_)
         . Protobuf::toString('len_', $this->len_)
         . Protobuf::toString('errCode_', Response_Err::toString($this->errCode_))
         . Protobuf::toString('errDetail_', $this->errDetail_);
  }
  
  // optional int32 tag = 1;

  private $tag_ = null;
  public function clearTag() { $this->tag_ = null; }
  public function hasTag() { return $this->tag_ !== null; }
  public function getTag() { if($this->tag_ === null) return 0; else return $this->tag_; }
  public function setTag($value) { $this->tag_ = $value; }
  
  // optional int32 flags = 2;

  private $flags_ = null;
  public function clearFlags() { $this->flags_ = null; }
  public function hasFlags() { return $this->flags_ !== null; }
  public function getFlags() { if($this->flags_ === null) return 0; else return $this->flags_; }
  public function setFlags($value) { $this->flags_ = $value; }
  
  // optional int64 rev = 3;

  private $rev_ = null;
  public function clearRev() { $this->rev_ = null; }
  public function hasRev() { return $this->rev_ !== null; }
  public function getRev() { if($this->rev_ === null) return 0; else return $this->rev_; }
  public function setRev($value) { $this->rev_ = $value; }
  
  // optional string path = 5;

  private $path_ = null;
  public function clearPath() { $this->path_ = null; }
  public function hasPath() { return $this->path_ !== null; }
  public function getPath() { if($this->path_ === null) return ""; else return $this->path_; }
  public function setPath($value) { $this->path_ = $value; }
  
  // optional bytes value = 6;

  private $value_ = null;
  public function clearValue() { $this->value_ = null; }
  public function hasValue() { return $this->value_ !== null; }
  public function getValue() { if($this->value_ === null) return ""; else return $this->value_; }
  public function setValue($value) { $this->value_ = $value; }
  
  // optional int32 len = 8;

  private $len_ = null;
  public function clearLen() { $this->len_ = null; }
  public function hasLen() { return $this->len_ !== null; }
  public function getLen() { if($this->len_ === null) return 0; else return $this->len_; }
  public function setLen($value) { $this->len_ = $value; }
  
  // optional .Skynet.Doozer.Response.Err err_code = 100;

  private $errCode_ = null;
  public function clearErrCode() { $this->errCode_ = null; }
  public function hasErrCode() { return $this->errCode_ !== null; }
  public function getErrCode() { if($this->errCode_ === null) return Response_Err::OTHER; else return $this->errCode_; }
  public function setErrCode($value) { $this->errCode_ = $value; }
  
  // optional string err_detail = 101;

  private $errDetail_ = null;
  public function clearErrDetail() { $this->errDetail_ = null; }
  public function hasErrDetail() { return $this->errDetail_ !== null; }
  public function getErrDetail() { if($this->errDetail_ === null) return ""; else return $this->errDetail_; }
  public function setErrDetail($value) { $this->errDetail_ = $value; }
  
     function __get( $key ) {
         $m = 'get' . ucfirst($key);
         if (method_exists($this,$m)) return $this->$m();
         return null;
     }
     
     
     function __set( $key, $value ) {
         $m = 'set' . ucfirst($key);
         if (method_exists($this,$m)) return $this->$m($value);
     }
     
     
     function __call( $method, $args ) {
         $m = 'get' . ucfirst($method);
         if (method_exists($this,$m)) return $this->$m($args[0]);
         throw new Exception('Invalid method ' . $method);
     }
     
     
  // @@protoc_insertion_point(class_scope:Skynet.Doozer.Response)
}

