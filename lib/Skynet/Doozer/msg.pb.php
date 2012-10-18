<?php
namespace Skynet\Doozer;
class Request_Verb extends \PBEnum
{
  const GET  = 1;
  const SET  = 2;
  const DEL  = 3;
  const REV  = 5;
  const WAIT  = 6;
  const NOP  = 7;
  const WALK  = 9;
  const GETDIR  = 14;
  const STAT  = 16;
  const ACCESS  = 99;

  public function __construct($reader=null)
  {
   	parent::__construct($reader);
 	$this->names = array(
			1 => "GET",
			2 => "SET",
			3 => "DEL",
			5 => "REV",
			6 => "WAIT",
			7 => "NOP",
			9 => "WALK",
			14 => "GETDIR",
			16 => "STAT",
			99 => "ACCESS");
   }
}
class Request extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["Request"]["1"] = "\\PBInt";
    $this->values["1"] = "";
    self::$fieldNames["Request"]["1"] = "tag";
    self::$fields["Request"]["2"] = "\\Skynet\\Doozer\\Request_Verb";
    $this->values["2"] = "";
    self::$fieldNames["Request"]["2"] = "verb";
    self::$fields["Request"]["4"] = "\\PBString";
    $this->values["4"] = "";
    self::$fieldNames["Request"]["4"] = "path";
    self::$fields["Request"]["5"] = "\\PBBytes";
    $this->values["5"] = "";
    self::$fieldNames["Request"]["5"] = "value";
    self::$fields["Request"]["6"] = "\\PBInt";
    $this->values["6"] = "";
    self::$fieldNames["Request"]["6"] = "other_tag";
    self::$fields["Request"]["7"] = "\\PBInt";
    $this->values["7"] = "";
    self::$fieldNames["Request"]["7"] = "offset";
    self::$fields["Request"]["9"] = "\\PBInt";
    $this->values["9"] = "";
    self::$fieldNames["Request"]["9"] = "rev";
  }
  function tag()
  {
    return $this->_get_value("1");
  }
  function set_tag($value)
  {
    return $this->_set_value("1", $value);
  }
  function verb()
  {
    return $this->_get_value("2");
  }
  function set_verb($value)
  {
    return $this->_set_value("2", $value);
  }
  function verb_string()
  {
    return $this->values["2"]->get_description();
  }
  function path()
  {
    return $this->_get_value("4");
  }
  function set_path($value)
  {
    return $this->_set_value("4", $value);
  }
  function value()
  {
    return $this->_get_value("5");
  }
  function set_value($value)
  {
    return $this->_set_value("5", $value);
  }
  function other_tag()
  {
    return $this->_get_value("6");
  }
  function set_other_tag($value)
  {
    return $this->_set_value("6", $value);
  }
  function offset()
  {
    return $this->_get_value("7");
  }
  function set_offset($value)
  {
    return $this->_set_value("7", $value);
  }
  function rev()
  {
    return $this->_get_value("9");
  }
  function set_rev($value)
  {
    return $this->_set_value("9", $value);
  }
}
class Response_Err extends \PBEnum
{
  const OTHER  = 127;
  const TAG_IN_USE  = 1;
  const UNKNOWN_VERB  = 2;
  const READONLY  = 3;
  const TOO_LATE  = 4;
  const REV_MISMATCH  = 5;
  const BAD_PATH  = 6;
  const MISSING_ARG  = 7;
  const RANGE  = 8;
  const NOTDIR  = 20;
  const ISDIR  = 21;
  const NOENT  = 22;

  public function __construct($reader=null)
  {
   	parent::__construct($reader);
 	$this->names = array(
			127 => "OTHER",
			1 => "TAG_IN_USE",
			2 => "UNKNOWN_VERB",
			3 => "READONLY",
			4 => "TOO_LATE",
			5 => "REV_MISMATCH",
			6 => "BAD_PATH",
			7 => "MISSING_ARG",
			8 => "RANGE",
			20 => "NOTDIR",
			21 => "ISDIR",
			22 => "NOENT");
   }
}
class Response extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["Response"]["1"] = "\\PBInt";
    $this->values["1"] = "";
    self::$fieldNames["Response"]["1"] = "tag";
    self::$fields["Response"]["2"] = "\\PBInt";
    $this->values["2"] = "";
    self::$fieldNames["Response"]["2"] = "flags";
    self::$fields["Response"]["3"] = "\\PBInt";
    $this->values["3"] = "";
    self::$fieldNames["Response"]["3"] = "rev";
    self::$fields["Response"]["5"] = "\\PBString";
    $this->values["5"] = "";
    self::$fieldNames["Response"]["5"] = "path";
    self::$fields["Response"]["6"] = "\\PBBytes";
    $this->values["6"] = "";
    self::$fieldNames["Response"]["6"] = "value";
    self::$fields["Response"]["8"] = "\\PBInt";
    $this->values["8"] = "";
    self::$fieldNames["Response"]["8"] = "len";
    self::$fields["Response"]["100"] = "\\Skynet\\Doozer\\Response_Err";
    $this->values["100"] = "";
    self::$fieldNames["Response"]["100"] = "err_code";
    self::$fields["Response"]["101"] = "\\PBString";
    $this->values["101"] = "";
    self::$fieldNames["Response"]["101"] = "err_detail";
  }
  function tag()
  {
    return $this->_get_value("1");
  }
  function set_tag($value)
  {
    return $this->_set_value("1", $value);
  }
  function flags()
  {
    return $this->_get_value("2");
  }
  function set_flags($value)
  {
    return $this->_set_value("2", $value);
  }
  function rev()
  {
    return $this->_get_value("3");
  }
  function set_rev($value)
  {
    return $this->_set_value("3", $value);
  }
  function path()
  {
    return $this->_get_value("5");
  }
  function set_path($value)
  {
    return $this->_set_value("5", $value);
  }
  function value()
  {
    return $this->_get_value("6");
  }
  function set_value($value)
  {
    return $this->_set_value("6", $value);
  }
  function len()
  {
    return $this->_get_value("8");
  }
  function set_len($value)
  {
    return $this->_set_value("8", $value);
  }
  function err_code()
  {
    return $this->_get_value("100");
  }
  function set_err_code($value)
  {
    return $this->_set_value("100", $value);
  }
  function err_code_string()
  {
    return $this->values["100"]->get_description();
  }
  function err_detail()
  {
    return $this->_get_value("101");
  }
  function set_err_detail($value)
  {
    return $this->_set_value("101", $value);
  }
}
?>