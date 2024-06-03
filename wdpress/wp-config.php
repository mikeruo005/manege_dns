<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wbpress' );

/** Database username */
define( 'DB_USER', 'mike' );

/** Database password */
define( 'DB_PASSWORD', '123456' );

/** Database hostname */
define( 'DB_HOST', '192.168.254.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '1-00T*d|9>q!]$!3I=MOtg/$?9gL1vt(q)0_,&YM64#/4n;b]?bQx&bM5 Ever}+' );
define( 'SECURE_AUTH_KEY',  ']Pnu6hCl;g7U6)1)X.7}hdT*8Is).U>[Sow/t CM%Eo}1!0eKZ=XnXoS=e|f9)X(' );
define( 'LOGGED_IN_KEY',    '4%[ HzT-(77e.CUIC]cz5O}K5#n^@|1j41&#2|4]A&CHdi[IXXmzMXx/dn]&0@g&' );
define( 'NONCE_KEY',        ':7P[7s&Q7x6a*f^lcu<*I`J~g84JZA>e3KaR<!@tTpbeYUy__%ag4}^UEnAnu>)E' );
define( 'AUTH_SALT',        ').;%a/4hhh2J/:MoRsB7h,OyB,T!!~5aj!N_xd:7<u7oYLM-|c{@E](swzJx*n|.' );
define( 'SECURE_AUTH_SALT', 'kK,X@<yhX6,*(I-}u0K})cdK}%~r+R*@Wm]NF5V9)Q$&rF}<Oe|.O-|.f%ShDF=H' );
define( 'LOGGED_IN_SALT',   '!Vi1s:NTj}7qKPn`zQ6B:NZoPqNS|[4t<nl]6.O[k?@n!;kY-xBOE%f2(V^3RJSy' );
define( 'NONCE_SALT',       '}]it5EHxCWK?*hv 0oLcb43gjsmX!=xOyWMa7S/Bkg0^8^sA^aN5f -aCRzoc_J;' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
