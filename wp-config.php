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
define( 'DB_NAME', 'aeterea1_toptoursmx' );

/** MySQL database username */
define( 'DB_USER', 'aeterea1_gorki' );

/** MySQL database password */
define( 'DB_PASSWORD', 'El-poder3' );

/** MySQL hostname */
define( 'DB_HOST', '162.241.60.245' );

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
define( 'AUTH_KEY',         'L`js2e+koN4&creL0]B-6#)+6}b@pB)swO#QVQ*D8_8Jw&=&<e65JoDOAa`G_l^r' );
define( 'SECURE_AUTH_KEY',  '?,))nc>n#C>h(:L ki5q@kQ(CG@WpN$ufp_7x2#b8Q)EIx;mJIYw%vcC](9#_j,(' );
define( 'LOGGED_IN_KEY',    'E;V#Vu0HAimJ!^_%!FfKZ6Mg~SBipvrOj8@!*K-6T?#*0{l6^.C/+[3F[y$5;He|' );
define( 'NONCE_KEY',        ';Dwra+_gpKgTH]>=Lo8;8 W4$/zM$Lu)wE ~1A.E6$jr}_Ds`iJ45Pch[qm-(iy2' );
define( 'AUTH_SALT',        'TMXpG!OB9M|R`F4q;^0sEm-1s?U)8mB@1MhgBVeV%qOHqpA:tWldl^IzUwGMWDQ}' );
define( 'SECURE_AUTH_SALT', 'O/aSWxiS@)-^ed{mZO)Q`3%1PiN]PPR? `({mU4zVqBc4s@ZMQ+_CHIs*>80X~)P' );
define( 'LOGGED_IN_SALT',   ' k:w<O[PUw|pc9a*Tz&H.ARV?n^/H_&q@b^ZEA(GH@#p``m?+iwT&je7s(7f}8E^' );
define( 'NONCE_SALT',       '1AzPg_.PT%!rW2j}.Hi.1eDBu0(NYb-U?5it-7=&k]1<*$wc:,B[!WtUVyC(!Pli' );

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
