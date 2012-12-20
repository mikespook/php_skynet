<?php
define('ROOT', '../lib/');
require(ROOT . 'Skynet/Registry.php');
require(ROOT . 'Skynet/Cache/Cache.php');
require(ROOT . 'Skynet/Cache/Null.php');
require(ROOT . 'Skynet/Cache/File.php');
require(ROOT . 'Skynet/Exception.php');
require(ROOT . 'Skynet/Socket.php');
require(ROOT . 'Skynet/Client.php');


$client =  new \Skynet\Client('TestService', array('cache' => '\Skynet\Cache\Null'));
echo $client->getId();
$out = $client->Call('Upcase', array('data' => 'Upcase me!'));
var_dump($out);
