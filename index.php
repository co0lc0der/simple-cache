<?php
require_once 'Cache/Cache.php';

$cache = Cache::getInstance('./runtime');

if (!$info = $cache->get('server_info')) {
	$cache->set('server_info', $_SERVER);
}

echo '<pre>' . print_r($info, true) . '</pre>';
die;
