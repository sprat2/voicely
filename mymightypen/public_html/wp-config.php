<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'voicely');

/** MySQL database username */
define('DB_USER', 'wp_voicely');

/** MySQL database password */
define('DB_PASSWORD', '8j5aQaxOVqTLwkXvR3xy');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '.t?)/n2nbKYB@BnejYM+waEoCY!.2:{OTiD*lD8WiGN7;72oxYNL) cy ybBB[Wz');
define('SECURE_AUTH_KEY',  'H(.<|m}+p2lMlngygK,e3L!@Acy28fgY+Ge%X~,M:b,D.Gy4{:}?OuJp0<|ua/ak');
define('LOGGED_IN_KEY',    'y]{7I`[O1L>QuE $N>Yy8|G63Q~]gmAg0rz~8z,c1H.Ki[b49AF]/DX(zh9Vh741');
define('NONCE_KEY',        'jBe;tnwzRqcuW[?&PMPu(3}zU-vE`wpkn$2iL,fK2W!0(.IyFJq$ D:,JuWtT:Q9');
define('AUTH_SALT',        'H|k?pzOq82-v}e4lL^?O1#^%Ma!*TA{GVl&$#BlbIFl;?3+NElEVkrU=tV g89T|');
define('SECURE_AUTH_SALT', '$s0nu/`3nS26Y*=V*2=EFXy]W1vBC5AlLushT:|~ R>`^|>7Md2}4jCVXwC8Q,cr');
define('LOGGED_IN_SALT',   'V6)P}i}-~z0hTVY_HkBd]R4ozHHAAoX5LbT~4l<UK/z3FqX3`EhK%<H !9=*lGU&');
define('NONCE_SALT',       'qL4FDEPmuV2-w#9ra!rf~mtO>*e2Gb!Qd)Tcw)(i7XkMWS/iwwpp5wQej|Z3f.s:');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mmp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
