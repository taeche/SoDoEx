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
define('DB_NAME', 'NECO');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Accord12');

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
define('AUTH_KEY',         'c~a3m|;^Y@{MxIaxLO2|ao5|5E[Xa+=(A7Z^`+vc`y>x*U+M=_4ITKfTEbs0?~!i');
define('SECURE_AUTH_KEY',  'KNW }s.x>Kd5c56$-NvGuF G*Z#q[$:J^|6m/}mF-@Y)(`GotXZrxg.Gr--0WDAU');
define('LOGGED_IN_KEY',    'd-P{UZ!,0*@[fH9Ar<Q@xSMFfCv-@&=AyD.!34FRdHuuoLv5fUw#pf:|9+rHrQGS');
define('NONCE_KEY',        '^`=x2{3cQ4Z&S%MRC <4Q==mIa|nBqvRg97FOzY[o,{L#:tDjHN|f2HbEL|qnyCc');
define('AUTH_SALT',        'u8>szQ6[XtWh@U%CDLcVI#0Vey~!f]HN.nh3SK;f|~T;_nL*u>9VKSv9pF.q=b#`');
define('SECURE_AUTH_SALT', '9leF]Kng,J5(;0igT[SD=Ds#I_0deIbn(lq5*s.c(Qf+#4Qh,Q9xM<andaIf9P-m');
define('LOGGED_IN_SALT',   'dQ?`z!9:|%Ex%Ue<%)SP!9`.JDc#2B3fTEcg];t;e*`)x$?l+Xg1KFN^NLk/>F1_');
define('NONCE_SALT',       'J2Ak?~}]C#W&{H0KL(Ed(IxZq=.hHQqmH|5R|:u=c|xv!32Akhq9r3+FdMlk3R/2');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpdev_';

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

define('WP_CACHE', true);