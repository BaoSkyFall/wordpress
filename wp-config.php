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
define( 'DB_NAME', 'wordpressmysql' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '%r[)NEbV`u4/!}t!#0Bv0d0SGd[hk+$phTaJ:d]7(V*P*Z_wAm|>bhCk>l y!yKk' );
define( 'SECURE_AUTH_KEY',  'c:p=Q)l9C7NOM[x!W*_;4p-):Oz7&{rO7uIF}5RjmEQl]kQmau8d&FzY&Y$WL:[I' );
define( 'LOGGED_IN_KEY',    '4P0E*uf_,Ze$ttNM}Ao>+GrfW&2FCL&X`ED%51$/(Cq0-,]S9<wq&.B< /a>eh[f' );
define( 'NONCE_KEY',        'N5K^dQ}MxGzElnlQWU:X/67rtA$GgKBp>UN,PXo+y:d&k}@i>X-S{avgFm1OJUhE' );
define( 'AUTH_SALT',        'w.q./V|lgMa<Kog:OXM%p7.sP0ElX~;O6Im?mz(lV%B[=UwslIq)^OHfNDBN^cz4' );
define( 'SECURE_AUTH_SALT', '*V,VDw$@jaNSivO_DZ-Y0Thi/q36L6ArfQbHgrR/UGWuN-/DBmOmRTal&@3f{ VE' );
define( 'LOGGED_IN_SALT',   'T@u`o[N0H?B81NM<TwTx~DweUL%s{QJS+b{}y5#<tPR;~.x9Y;~C|mV(?CUcHg-^' );
define( 'NONCE_SALT',       'Csw)zS=;D~o*EMCmD0#(MJI19?~dC9CIE^Kp|T%Kpj6XR[([Weo Ff&t@LCz_a|_' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
