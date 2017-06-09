<?php

session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');


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