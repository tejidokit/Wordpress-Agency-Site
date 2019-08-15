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
define( 'DB_NAME', 'divi_wordpress' );

/** MySQL database username */
define( 'DB_USER', 'divi_wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'computer219' );

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
define( 'AUTH_KEY',         'G>_~5=FXtr(MIAt;1k :y05i~2@O!}Ir@+LXn|RA@~Qx?U(0^~(;v4CBnLa`_4#{' );
define( 'SECURE_AUTH_KEY',  'B.sn.yKiVEl5-E0?kV,R5]S/_2ntEQmA9:HE2U/Kn(TiB!:7[P)s:A!jtp+WO{F5' );
define( 'LOGGED_IN_KEY',    'g~h+q_|vi$N.%6?J85?c<nD%g`!6G+%]3m3Fh-o@X7c&kU37L/D;pFNHq6Ycxp ^' );
define( 'NONCE_KEY',        '=+[-NPXMJ)fkSrfD#m]L(`z5m%G>hx%w_G31yf-N3c?J`l-&DpBNMmS;H.HmWzc%' );
define( 'AUTH_SALT',        'hv{TTOqD21Fyxs6dme_@#=?f;l^7 ut5eolRa)7bvKt{%ZtCm`V8hWJnK7TCL@6S' );
define( 'SECURE_AUTH_SALT', '!}VRes9K=N+;EX0^ G*I4ze[PJ+C8TBBL+5KQ{,4XW*`mS3,~<R`2xo-FL)byv9Y' );
define( 'LOGGED_IN_SALT',   'h<e1t_o_g:Z|pAtzKjg%r:oq#(Qlfu>e+IcaSyC>}(LUWM1{_/oo-P3^5M)p^Ye:' );
define( 'NONCE_SALT',       '^E47{1{k:qR937d3Uu|<Z?o^`gQAF{yqwS(k7yZb$S.Gz-T>x#F+%<hW(,Le3qM|' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
