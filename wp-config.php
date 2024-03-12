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
#define( 'WPCACHEHOME', 'D:\wamp\www\dev\hosana\wp-content\plugins\wp-super-cache/' ); //Added by WP-Cache Manager
#define('WP_CACHE', true); //Added by WP-Cache Manager
define('DB_NAME', 'travel');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         ';u*xMA`CcgM2n$_zV)@6X*,dfWQv1y+^O,?P>~CUJ)C[iATrrtt7rw1q.?uN2/VR');
define('SECURE_AUTH_KEY',  ')I_jS2j@kOEAO#VN~UV!MV9)3@,NPb;H:_CSUD#NWf;WtE?#JLBYS!:9hNuRj;SL');
define('LOGGED_IN_KEY',    '+|=ndfj0-Yt!KpSEvjhX=j&/bbbp*``SYW1W%Y8#;!lq{@>o/5b xD+uyMeGD#J]');
define('NONCE_KEY',        '|I9m?!{!0pEGv1}i27z(*8GKOW}: v,lxP^l-juaLDMj;}YZYCF[,X{Z?OK3j-[e');
define('AUTH_SALT',        '_V5oO#n~Hwz) 9d;ay8qzK0C6),EqJ,G;iC/<i$l;9M)DYZ257.cN`1r!B3Zvb_/');
define('SECURE_AUTH_SALT', 'wC}Q{<1$-T|LVI?(q#Dy(9P-xoY-%W@f51a{3:OAoEACt`JgF}#qI{ XOSV!|b>~');
define('LOGGED_IN_SALT',   'w[jKsJ)lWc6>PSw@eU,!{HD`*wV}?bvzU`8o-ymII t;5u$:_d*iGstFo;C%Js8P');
define('NONCE_SALT',       'Y`OS.dPZi|;jb94$ SR@@gy|cW<Ez}Z[|zF^yoG8xYoIUrwlqqCHB__%w}C/XS=B');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'hsn_';

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
if (!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
