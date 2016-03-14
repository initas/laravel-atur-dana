<?php

//response
define('BLANK', 0);
define('SUCCESS', 1);
define('INVALID_CREDENTIAL', 2);
define('VALIDATION_ERRORS', 3);
define('API_ERRORS', 4);
define('UNCONFIRMED_EMAIL', 5);
define('UNKNOWN_ERRORS', 10);

//date
date_default_timezone_set('GMT');
define('NOW', time());
define('TODAY', gmdate("Y-m-d"));
define('TODAY_LABEL', gmdate('Y-m-d H:i:s'));

//url
define('CDN_DOMAIN', 'http://cdn1.nomad.id');
define('YOUTUBE_DOMAIN', 'https://youtu.be');
define('YOUTUBE_IMG', 'http://img.youtube.com/vi');

//url type
define('LOCAL', 1);
define('YOUTUBE', 2);
define('DIRECT', 3);

//level
define('ADMIN_LEVEL_ID', 1);
define('MEMBER_LEVEL_ID', 2);

//setting
define('PER_PAGE', 30);
define('INDEX', 1);

//status id
define('DELETED', 0);
define('DRAFT', 10);
define('PUBLISHED', 20);

//url
$serverPort = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80) ? ':'.$_SERVER['SERVER_PORT'] : '';
$serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'].$serverPort : '';
$server = 'http://'.$serverName;

//mf.dev server

$whitelist = array(
    'localhost',
    '127.0.0.1',
    '192.168.1.6',
    '10.0.0.194'
);

if(in_array($serverName, $whitelist)){
	$server = $server.'/zero/public';
}

//aws.dev server

$whitelist = array(
    '52.90.83.253'
);

if(in_array($serverName, $whitelist)){
	$server = $server.'/nomad-zero/dev/public';
}

//development server

$whitelist = array(
    '202.59.166.6'
);

if(in_array($serverName, $whitelist)){
	$server = $server.'/zero/dev';
}

define('SERVER', $server);
define('DOMAIN', $server);


define('M_V1', $server."/m/v1");
define('API_V1', $server."/api/v1");


