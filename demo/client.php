<?php
define('ROOT', '../lib/');
require(ROOT . 'Skynet/Registry.php');
require(ROOT . 'Skynet/Cache/Cache.php');
require(ROOT . 'Skynet/Cache/Null.php');
require(ROOT . 'Skynet/Cache/File.php');
require(ROOT . 'Skynet/Exception.php');
require(ROOT . 'Skynet/Socket.php');
require(ROOT . 'Skynet/Client.php');


$socket = new \Skynet\Socket('127.0.0.1', '9002');
echo $socket->getClientId() . "\n";
echo $socket->getRegistered() . "\n";
