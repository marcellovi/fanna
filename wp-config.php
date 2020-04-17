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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'xxx' );

/** MySQL database username */
define( 'DB_USER', 'xxx' );

/** MySQL database password */
define( 'DB_PASSWORD', 'xxx' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/** This will disable automatic updates for WordPress, themes, and plugins.**/
define( 'AUTOMATIC_UPDATER_DISABLED', true );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '^7+Je|-$_N5C/[j*7v4@]VQ/}J#Wmz{in. $vIhK9+%?wES%o.io{~w[(t,WHyKd' );
define( 'SECURE_AUTH_KEY',   ';9z[ORD6=9DxFA~s`;QQfFHjOW?Z8d9E1Bvjz k26myEbp6`uv$Em9#zvw^F`$iu' );
define( 'LOGGED_IN_KEY',     '6MC*{0=XtGKS==bH]9s#)2eiJMjSuT{*ANJ]d~iNtoCOoX5kZ(#&cag~hAhI&4+x' );
define( 'NONCE_KEY',         'LUe10d.e{wqp]vR|IuX$@CCS$JE,[@A}n.fNraFz6 23w!nv>`4_GLWjsSG+yUQI' );
define( 'AUTH_SALT',         'O?mZdI+Y>zRs<U*R~&a:802tbSU_;MH)ZB#5cOTj3W!~ITeS3F]<coa1>}c2y>^W' );
define( 'SECURE_AUTH_SALT',  'Ic~JBloIk1xSsdTQzLJp+T(15fw$ALp2H6!7L&(D*!JIhF}<Dl*V{*{%Y8lAJF%=' );
define( 'LOGGED_IN_SALT',    ':MFSjpt6]LQAhQh.zhCKRpAedo!^V%M I>}s}uWcgpdx`-XtV+G&9Z0,qC^^|l;s' );
define( 'NONCE_SALT',        ')E/<p2469rlP/FNN/jel-RM^A12DIH40+U?0Zh#3)qXh/cu&xZyUZi6ng8*-4C)[' );
define( 'WP_CACHE_KEY_SALT', '6q;w54[VeP[:Sbz7CNg9k9%M`,7/4jTg67RHEqh%&bU2buNw~TS}xwF47FE,F)#W' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
