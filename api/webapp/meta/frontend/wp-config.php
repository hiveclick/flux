<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '[MYSQLDB]');

/** MySQL database username */
define('DB_USER', '[MYSQLUSERNAME]');

/** MySQL database password */
define('DB_PASSWORD', '[MYSQLPASSWORD]');

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
define('AUTH_KEY',         'R9j3H9Js|mK#Dj>3&e9so`*%:[xOhE|ZETU;kep*Iampu,e$JEa-*L|N,X*B)vRG');
define('SECURE_AUTH_KEY',  'q<MM<|%H,<^Sr5@fRtf_Z/>.hAnZtMU+ +HcUk+0|y9%pZ,IZFi.}e%=D|j8oU1m');
define('LOGGED_IN_KEY',    '8W`a;[{Vn_oK%x2J>kar#o>cQJ|LoM-L[--50A|5z|BC4{Q})4(g?~FwfnJWhb~Z');
define('NONCE_KEY',        'u*TFC.EFiY;p$1@qgE,,ed&--AvB+>c`?GRH?x/l^vT$*<KT}*W,+v{1])/,AHd>');
define('AUTH_SALT',        '-/#J!G,#=L0`-=AteE{ VnL,na,C,A[( (dq].}ZbQ}`NO#oNvm:3)$L?f`j<v&8');
define('SECURE_AUTH_SALT', 't-`~f-a3.G,_gUDgB[^@luyn8eoGTAtrj:~uMHr+D@?9dwusSvn2=T?uQn+J+AXt');
define('LOGGED_IN_SALT',   'Mv=TPt#D1;x^3JrzIb*X^[JfF!JOd@.K(*P@Jf45>k0SccKN4O,NR^<b~|mN0Wn_');
define('NONCE_SALT',       'i=@DdiOs%k69U;$6X)6%FL9.K??|F9Q!26J2,-7Zvfb($q?Yd-,H7|lf_<(m!H?r');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = '[TABLEPREFIX]';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

