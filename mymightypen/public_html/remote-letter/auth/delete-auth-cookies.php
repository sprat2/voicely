<?php

session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');

// Delete auth cookies
setcookie( "errorToken", '', 1 );
setcookie( "facebookToken", '', 1 );
setcookie( "twitterToken", '', 1 );

// unset cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}