<?php

session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');

// Delete auth cookies
setcookie( "errorToken", '', 1 );
setcookie( "facebookToken", '', 1 );
setcookie( "twitterToken", '', 1 );