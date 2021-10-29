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
define( 'DB_NAME', 'pocsite' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'Va;#{qp}U9,aU<W%363_{97o^oxt!L5Q_e wXmE$!.1`|p>P*`IzLXoZ1f-[@Xi<' );
define( 'SECURE_AUTH_KEY',  'A_&d0[?oMb_a82k;0yTw!i4P_>Ty9pLjTOr=_3We!dk;Z48xtLWI.6~Ca]$l<4uW' );
define( 'LOGGED_IN_KEY',    '/0S>we-=XYpRVjHMmV*a91NzT%#Ob(1sVs,;)av&k/J{c8WI^$[mLZy6o W55T> ' );
define( 'NONCE_KEY',        'piNqAdW:6QX;|?^trncUvlIp}J(Uv<_;G5:glErrthx=0@JaovsL;]ufe|hiw&><' );
define( 'AUTH_SALT',        '(q`{(3N>=S[)uw)9e((p8Z5+;ab*zJF;hOCf|/~gQU P_KzADP:^H|Z3pdRhi;AR' );
define( 'SECURE_AUTH_SALT', '$&,/8-WN9Ju[C;N!^;]C0>A42hIj1$1cYH0GUm>kNZ]G-RZj?c#%vs`H6xZi}15N' );
define( 'LOGGED_IN_SALT',   '}uspOaR7zSStlc]`Sfhy#+YkHmH] Kir7T,*&.qEsoyI-H*oQ~VyWeE9eRH3VJlD' );
define( 'NONCE_SALT',       'iv4b0Ugn}ek3Go~Ns<.6j:dmf[&Y.Z4Drw(YzSEQsG%`8 1-;pEmNenfIv~[pd3{' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
