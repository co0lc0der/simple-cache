<?php
require_once 'Cache/Cache.php';

$cache = Cache::getInstance('./runtime');

if (!$info = $cache->get('server_info')) {
	$info = $_SERVER;
	$cache->set('server_info', $info);
}

echo '<pre>' . print_r($info, true) . '</pre>';

$abc = $cache->getOrSet('abc', function() {
	return ['a', 'b', 'c'];
});

echo '<pre>' . print_r($abc, true) . '</pre>';

//$cache->clear();
die;
