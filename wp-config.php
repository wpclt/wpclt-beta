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

if ($_SERVER['SERVER_NAME'] == 'localhost.wpclt.com') 
{
	// production settings
	if (isset($_GET['debug']) && $_GET['debug'] == 'debug') {
		define('WP_DEBUG', true);
	} else {
		define('WP_DEBUG', false);
	}

	// edit hosts file to include this:
	//127.0.0.1	localhost.wpclt.com
	//::1		localhost.wpclt.com

	// for localhost development
    define('WP_DEBUG', true);
	/*
	define('DB_NAME', 'wpclt');
	define('DB_USER', 'jbwebservices');
	define('DB_PASSWORD', 'jbwebservices');
	define('DB_HOST', 'localhost');
	*/

	// redoma.digital (has remote access to mysql)
	define('DB_NAME', 'redoma_wpclt');
	define('DB_USER', 'redoma_mysql');
	define('DB_PASSWORD', 'bND@pT*d{9r3');
	define('DB_HOST', 'avenger.websitewelcome.com');

    define( 'WP_SITEURL', 'http://localhost.wpclt.com' );
    define( 'WP_HOME', 'http://localhost.wpclt.com' );

    define('WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '\wp-content');
    define('WP_CONTENT_URL', 'http://localhost.wpclt.com/wp-content');

    define('WP_PLUGIN_DIR', $_SERVER['DOCUMENT_ROOT'] . '\wp-content/plugins');
    define('WP_PLUGIN_URL', 'http://localhost.wpclt.com/wp-content/plugins');    
} else {
	define('WP_DEBUG', false);
	
	// redoma.digital (has remote access to mysql)
/*
	// Please be careful with this configuration, if this is enabled it blows up my inbox
	// thanks.
	// matt
	define('DB_NAME', 'redoma_wpclt');
	define('DB_USER', 'redoma_mysql');
	define('DB_PASSWORD', 'bND@pT*d{9r3');
	define('DB_HOST', 'avenger.websitewelcome.com');
*/
	// chris daley's server (Actuall Production)
	
	define('DB_NAME', 'wpcomclt_adminz');
	define('DB_USER', 'wpcomclt_adminz');
	define('DB_PASSWORD', '92sziEKNgDkHw');
	define('DB_HOST', 'localhost');
    
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

// hacked WP accounts result in hacked WP, nuke that.
define('DISALLOW_FILE_EDIT',true);

// clean up trash
define('EMPTY_TRASH_DAYS', 90);

#define('FORCE_SSL_LOGIN', true);
#define('FORCE_SSL_ADMIN', true);
#define('DISABLE_WP_CRON', true);
#define('WP_MEMORY_LIMIT', '96M');
define( 'WP_AUTO_UPDATE_CORE', false );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

define('AUTH_KEY',         'CLz}O(_b8> p>B* (Tfp0-v&tqzbX~<W.tJKu-|)/-cu===[;nXCDS6%FIu5W uY');
define('SECURE_AUTH_KEY',  'Qv) +fZEsHn{QIyky0Py.i]Ky/fHe45+DC,,g*+v9Bk(?SP8?1?.gJM8&fM1_x3D');
define('LOGGED_IN_KEY',    'u[D36s>dIe[cdAS._|[TFG.QK-Z /!i/pZLt,i]BO6{ZE1DNA]t@M:hTTx~0[<Md');
define('NONCE_KEY',        'Dj;lCs+,QYyZ%q@z(7*v`UL_O+;u%`)]C$|kqq{yZr(R-GQhE_>S0aB1o7XbUpAz');
define('AUTH_SALT',        'Zn,%N5NZ;gF|#WZ:L&!YC|-[]?{KRq^zj#wMIG)K*I]Jb|RC.Oc`fS *F[-:FZxs');
define('SECURE_AUTH_SALT', ' tX1U&+WP1D aHhY6og(S$b{-8P|Kdu|Yh5Y{ !J J>9/8R@:RPv*qFE66$wgg7^');
define('LOGGED_IN_SALT',   'x2Le[<?@ze!-fnJZ` sIDz5P5^u#>-M%4e$:!RnndIFT%LQ|$*+_-yfe&s}-u<t/');
define('NONCE_SALT',       '{tnqEYlcHp)[&$2}Rn:.WI-k`RyDD~t2Br:>bIMBK!SK;Z/:485L[Sg?JD*$giEw');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'bm14_';

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
//if ( isset($_GET['debug']) && $_GET['debug'] == 'debug')
//  define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
