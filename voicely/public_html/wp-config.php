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
define('AUTH_KEY',         '{8.%xS@4LHGgp7t!*c{BNQ/wVv-}Cstt(9b%/A&f,4p?%T3xnxp7kKzyg7{k0o)D');
define('SECURE_AUTH_KEY',  ':|r}mw.NWDCu+&&4,+(>F-ZAg>^L=]h> < ts_=l:OX[7ZK=B+S27C]>1D{k--Vf');
define('LOGGED_IN_KEY',    'A%$lE)NSqFa]I]!^nlkac0R!azbrhn+%O@{>LGG34TmiO7rGF?eOIU($KBxnOhxY');
define('NONCE_KEY',        'YTh(*s>M:7KzP+I,;&qD_#Z5x(Fx=~`ga@d]&Qy;Fm3_{bL53mBNMy7*al3~z~(F');
define('AUTH_SALT',        '=U+`1X/J(||Q7djZ/P@6y1@8D N%Q`U3uepB6r>Gwi%h~E9p:h@1~--N gRI<L?0');
define('SECURE_AUTH_SALT', 'c7VT}9_sTxIP=W~%V/NyH4GsXHuX)3sTE-u;Auy)C cVSe#=<m?eG_OXg_^%a?e=');
define('LOGGED_IN_SALT',   'Sc#a`?n/^AxtwPJCIXPxX>RP@hXHPcix?FxTK&Ue/evD9Yl)Q_=vgegTCUcU/nj&');
define('NONCE_SALT',       'EOE`/I]3scg82QQzl2vDImrvje!K6mfBSwxDAj414z^G2;dA=0t~SFll-)&CDlD.');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = '_';

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
