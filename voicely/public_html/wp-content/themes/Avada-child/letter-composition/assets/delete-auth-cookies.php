<?php

session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');

// Delete auth cookies
setcookie( "errorToken", '', 1 );
setcookie( "facebookToken", '', 1 );
setcookie( "twitterToken", '', 1 );
setcookie( "instagramToken", '', 1 );
setcookie( "googleToken", '', 1 );
setcookie( "googleContacts", '', 1 );
setcookie( "errorContacts", '', 1 );