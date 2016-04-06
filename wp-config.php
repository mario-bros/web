<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
// define('WPCACHEHOME', '/var/www/clients/client1/web1/web/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('WPCACHEHOME', '/Users/Framma/Work/www/web/wp-content/plugins/wp-super-cache/' );
define('DB_NAME', 'c1pedulidb');
define('WP_MEMORY_LIMIT', '256M');
/** MySQL database username */
// define('DB_USER', 'c1peduliuser');
define('DB_USER', 'root');

/** MySQL database password */
// define('DB_PASSWORD', 's_3c5RqDX');
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'fApI&X%.&s*qSk`gUxm7EwsbO2+`dO>29~s(t#t0?jp,M#x{Il_3&aXsa0a|#gh{');
define('SECURE_AUTH_KEY',  '.P:7y)r$?pAiADl5322,$g|H5:1uqa;k;N8_kA0IDe3cC92sOGob*}>7Mkvt]wUz');
define('LOGGED_IN_KEY',    '*AZcj%zm<,1?Ys4WjqY]BoAde-?9Dz-`d.QGDqvYsD/Uj+k-=ERf:~iWNk/gxH(p');
define('NONCE_KEY',        '&_Yv-4J-[Jm-Nt]`j?@$B4~mM^fM{+rDsrx~zAS[F0fwt7Sr}T@]5fZVG|97{*5^');
define('AUTH_SALT',        ':=VHj1h{A^UN+u.lu6?L~Ed@gxOI1)Z8DmH4 u{-X7]bkD,:x!`o<n+Nw8P.7zR+');
define('SECURE_AUTH_SALT', '4bXLmVUcWTv1}(81e):66T[op`1&E?`[H[EV8$.+n:qmd-X vXe9+&ghH$YFQr[E');
define('LOGGED_IN_SALT',   'w)b-B:xX;DaB[zh91|v_ #NOKt 7BD<ExB}.(K7/~Vaa]EG[Lg+:h^A|Z[As;]k9');
define('NONCE_SALT',       'A9d1aj-ra%f:2EN|Ve8fSYXC4UH#9 k:ebLO+IfN9e;BT;wy+R2!,M.Q/hQbsp|7');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */

define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
