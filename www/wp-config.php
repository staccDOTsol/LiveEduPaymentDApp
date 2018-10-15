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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'TeamEthereum138@');

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
define('AUTH_KEY',         'Wctvp#vxRn6Kbi?>lc|AYIbB^0-n>Vngl3_&%bg.tYI/ *n/8I4k t$1|NV!>Pw$');
define('SECURE_AUTH_KEY',  '62Us34qE+bGS25{96Mq*)0$C.=I#:HplcK}7}.r,8+x9wehEklh<Yr3>c-=G~Gq2');
define('LOGGED_IN_KEY',    'y_b#oZ+AJ6h=NW{5`*Uk]>VG<d!1K-9]coRJ:h*M=+,|r_]1M__n~LX$^TqbtDYg');
define('NONCE_KEY',        '^-Gat>k?oY43BhSZ~d6K %%ErVoV^}$z, <[u`Hs2va@X)dEQ~I!?CSI12*}*b X');
define('AUTH_SALT',        'w?e{i+dg]LdMHZdj&Bj9Ncn[%BjzVBJ(7p~OAaNh3Ruft%F/sV>[k$#<Q^lp;0 a');
define('SECURE_AUTH_SALT', 'a04-+Pw1W318;OgcpJ0)CU83FDzBSaj~mF1S4T40&{Ag$l+XkAmfxB/</QkH7fAu');
define('LOGGED_IN_SALT',   'TK{X;&ZADb/#d/+=LN?7*]<weGk8eMXg_Y9~h|EHu5#h(Ql$~mC5lhnk8B~cra$#');
define('NONCE_SALT',       '<}&hfJ@Q`~w7yXVOE[T75gNn;3#wZ~cn`V9$=}Y*Q&/~}p#zwu}pQsUjKJ_9E+W^');

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
