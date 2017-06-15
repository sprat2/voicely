<?php

session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');

// Delete auth cookies
setcookie( "errorToken", '', 1 );
setcookie( "facebookToken", '', 1 );
setcookie( "twitterToken", '', 1 );

// Failed examples: (Issues specific to old implementation - likely to be successful fur any future cookie removal) 

// Note: this next line will only work if the frontend and backend are on the same domain
// document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/; domain=.mymightypen.org"); });

// unset cookies
// if (isset($_SERVER['HTTP_COOKIE'])) {
//     $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
//     foreach($cookies as $cookie) {
//         $parts = explode('=', $cookie);
//         $name = trim($parts[0]);
//         setcookie($name, '', time()-1000);
//         setcookie($name, '', time()-1000, '/');
//         //setcookie($name, '', time()-1000, './');
//     }
// }

// $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
// setcookie( "facebookToken", '', 1, '/', $domain );

//setcookie( "facebookToken", '', 1, '/', 'mymightypen.org' );

// setcookie('facebookToken', '', 1);
// setcookie('twitterToken', '', 1);
// setcookie('errorToken', '', 1);