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
define('DB_NAME', 'halla');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'halla123');

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
define('AUTH_KEY',         '3cciMEsrWexj`!EqT2C@*&{3%^HXgJQqlZwjN`rN5wR;?Jygb6~t]!ybA[mi<Eik');
define('SECURE_AUTH_KEY',  '!z?6SZztlRKW@qnBBp5r]1dkK%&r/k_e TLfJp%`ik=!)-M3I?|:eb5,f6RC4AdD');
define('LOGGED_IN_KEY',    'Q%=ao$@5h,Gv>-DL9oD+rQ_5T?-*7+?loIMQFZ2F=bddqENsBE[YsaytSen0GBkJ');
define('NONCE_KEY',        '24~lLAw^>B:btl6#-|yS=d%&(CKL0s3N8u3<+}:0j3QD.I0.;ub Kq9?yN0|4z7v');
define('AUTH_SALT',        'vZFz*xV/Dwj9.Q/G77C9,S^k;5$K}ii5=2F)19bFJ)^CynJkIC]c`9.*x5/d>Y;z');
define('SECURE_AUTH_SALT', '/d]A]`)fSrf7(RAc&ot&~8&z6zWegxU&@iGR.&<mk/0Pk|2f{o9/(7b,bcqt2mZc');
define('LOGGED_IN_SALT',   'GYInMQl=yb{![ZVevYil$_n,8b+7qVTbq$lj(1H1GP<NdQtHnO&baOg(?taCWxji');
define('NONCE_SALT',       'Uj@co{&fx^B^3t;= *%4~ol&)0t83Ajv:0pRz1%ruwMQ)v]i#((@5Q5VTR&*[EzL');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
