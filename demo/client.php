<?php
define('ROOT', '../lib/');
require(ROOT . 'Skynet/Doozer/Exception.php');
require(ROOT . 'Skynet/Doozer/Client.php');
require(ROOT . 'Skynet/Registry.php');
require(ROOT . 'Skynet/Cache/File.php');
require(ROOT . 'Skynet/Exception.php');
require(ROOT . 'Skynet/Socket.php');
require(ROOT . 'Skynet/Client.php');

$client =  new \Skynet\Client('TestService', array('cache' => '\Skynet\Cache\File'));
echo $client->getId();
$out = $client->Call('Upcase', array('data' => 'Upcase me!'));
var_dump($out);
