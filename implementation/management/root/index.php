<?php

global $_SERVER;
global $Campsite;
global $DEBUG;

// initialize needed global variables
$_SERVER['DOCUMENT_ROOT'] = getenv("DOCUMENT_ROOT");

require_once($_SERVER['DOCUMENT_ROOT'].'/configuration.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/parser_utils.php');

// read server parameters
$env_vars["HTTP_HOST"] = getenv("HTTP_HOST");
$env_vars["DOCUMENT_ROOT"] = getenv("DOCUMENT_ROOT");
$env_vars["REMOTE_ADDR"] = getenv("REMOTE_ADDR");
$env_vars["PATH_TRANSLATED"] = getenv("PATH_TRANSLATED");
$env_vars["REQUEST_METHOD"] = getenv("REQUEST_METHOD");
$env_vars["REQUEST_URI"] = getenv("REQUEST_URI");

// read parameters
$parameters = read_parameters($query_string);
$cookies = read_cookies($cookies_string);

debug_msg("request method: " . getenv("REQUEST_METHOD"));
debug_msg("query string: $query_string");
debug_msg("cookies string: $cookies_string");

$msg = create_url_request_message($env_vars, $parameters, $cookies);
read_parser_output(send_message_to_parser($msg));

?>