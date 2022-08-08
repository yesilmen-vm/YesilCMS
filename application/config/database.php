<?php

defined('BASEPATH') or exit('No direct script access allowed');

$active_group  = 'default';
$query_builder = true;

$db['default'] = [
    'dsn'          => '',
    'hostname'     => '%CMS_HOST%',
    'username'     => '%CMS_USER%',
    'password'     => '%CMS_PASS%',
    'database'     => '%CMS_DB%',
    'dbdriver'     => 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => false,
    'db_debug'     => (ENVIRONMENT !== 'production'),
    'cache_on'     => false,
    'cachedir'     => '',
    'char_set'     => 'utf8',
    'dbcollat'     => 'utf8_general_ci',
    'swap_pre'     => '',
    'encrypt'      => false,
    'compress'     => false,
    'stricton'     => false,
    'failover'     => array(),
    'save_queries' => true
];

$db['auth'] = [
    'dsn'          => '',
    'hostname'     => '%AUTH_HOST%',
    'username'     => '%AUTH_USER%',
    'password'     => '%AUTH_PASS%',
    'database'     => '%AUTH_DB%',
    'dbdriver'     => 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => false,
    'db_debug'     => (ENVIRONMENT !== 'production'),
    'cache_on'     => false,
    'cachedir'     => '',
    'char_set'     => 'utf8',
    'dbcollat'     => 'utf8_general_ci',
    'swap_pre'     => '',
    'encrypt'      => false,
    'compress'     => false,
    'stricton'     => false,
    'failover'     => array(),
    'save_queries' => true
];

$db['world'] = [
    'dsn'          => '',
    'hostname'     => '%WORLD_HOST%',
    'username'     => '%WORLD_USER%',
    'password'     => '%WORLD_PASS%',
    'database'     => '%WORLD_DB%',
    'dbdriver'     => 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => false,
    'db_debug'     => (ENVIRONMENT !== 'production'),
    'cache_on'     => false,
    'cachedir'     => '',
    'char_set'     => 'utf8',
    'dbcollat'     => 'utf8_general_ci',
    'swap_pre'     => '',
    'encrypt'      => false,
    'compress'     => false,
    'stricton'     => false,
    'failover'     => array(),
    'save_queries' => true
];
