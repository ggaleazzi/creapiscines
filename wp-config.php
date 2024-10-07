<?php//Begin Really Simple Security key
define('RSSSL_KEY', '5QTC7ViymnbODUOTAHU1BqkXBPNjI3vt1lIhutn0ePSnXg1pvEZxlBTXb4t2iWy4');
//END Really Simple Security key

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL cookie settings

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
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
define( 'DB_NAME', 'creapiscines_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
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
define( 'AUTH_KEY',         'YMK_m$*sv?!~ao2L1,~.?!K:5/Rg/|i6rFn-eV>-Wk+#OLzax^<<zDB,U{Lp_H}B' );
define( 'SECURE_AUTH_KEY',  'b=+miuzP./{_oWDYatXPb0wP=_qKAA}Z]0;l0g1F2$U&8#tJ$r>jOlp]1wTJT(?(' );
define( 'LOGGED_IN_KEY',    'leTR<@l983/89?bEJk{~!|[ePA{j@Pgz%wBZ-g|D:~LAqC/`X~kV7C<9@F?6B*E{' );
define( 'NONCE_KEY',        'sB+OK6ECbU0}o$OH/t.NY}5mcc0,F)y!X0H<G9_47~Qmnqj_lB)q|?Zj*EAWETeR' );
define( 'AUTH_SALT',        '9ycx7TS,t{[b/bkfEl.c=yjFZCg/;VC @/:iCaICVrN9L&4J)Qg<CL4!xqcD)g&;' );
define( 'SECURE_AUTH_SALT', ':04ceaV1:i9FzjP=I?=7x%uesF3/3f4PxIO{`/Xb|9>[m+{p2G=tzUs>/d8O#7N,' );
define( 'LOGGED_IN_SALT',   'I&y([x{u|;v[+VSi}F+B$n$H_+8$T[y +ksr-j3-[iEdg*/d4{=epnNJ#NwJjd2W' );
define( 'NONCE_SALT',       'S/jdT;Ha(t07}q_ }]f_FGT; ,Y>Pr{W3-ZxsVNV,4wH`|&-@DANx.b[JWq{UX{E' );

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
