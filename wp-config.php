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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'equityprftsky' );

/** MySQL database username */
define( 'DB_USER', 'equityprftsky' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Equity2009' );

/** MySQL hostname */
define( 'DB_HOST', 'equityprftsky.mysql.db' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Vf4UR{24l;&:go,l|1;CwtbA[-vLniN2io.{& 7;0nUhh(eT[0rKc`}~Qu ;<AZ|' );
define( 'SECURE_AUTH_KEY',  'Eh# J!UX;i6E`n0Ds>d6xlINo+DCw_ex[RJB0,(x5A0Ccwvno22w_~XD+(F1J_a6' );
define( 'LOGGED_IN_KEY',    'hAcN`vE9`?Lc0Qq!FA{ZU7in*?8sk,R3sy)?r$2b+82-)dVm[tRU `yqCM0t-@b3' );
define( 'NONCE_KEY',        '&Hpk:w.(hBONLhcx!`48h)~#w&, <L%!ogbL4G*wJ[3x?cvPzSBVvkDLO/jv[6>_' );
define( 'AUTH_SALT',        'Km@=!>u1M) nkE|H!pc`)*xCA&63l?|DRvRk,=62qgAa=mGI1O2,[ON4XJcl2as ' );
define( 'SECURE_AUTH_SALT', ']`jz)kb}L=ZEun@Yp&v4=n:51rbcgLnaYjp;$z0N|3QHIiE7*0B]&f~c O12nbq}' );
define( 'LOGGED_IN_SALT',   '{n#/4Vt&c)i2Ca`?_EeQeEoUaZCVpex+n_3`^5Ty1t,srI>A2X!?e|85L&|}]2SV' );
define( 'NONCE_SALT',       'fXBlA([N-&xY^:[=lnaIDwAo]%D6Gd(6Nm[l3.`)!m3IJp3t-$0P/^QeliD]E8O_' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'uc_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', true );
define( 'WP_DEBUG_LOG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
