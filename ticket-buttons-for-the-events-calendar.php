<?php
/**
 * Plugin Name:     Ticket buttons for The Events Calendar
 * Plugin URI:      https://jeero.ooo
 * Description:     Add a tickets button to your events in The Events Calendar to allow ticket sales on external websites. 
 * Author:          Slim & Dapper
 * Author URI:      https://slimndap.com
 * Version:         1.0
 * Text Domain: 	TBTEC
 *
 * @package         TBTEC
 */

/**
 * Bail if called directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    return;
}

define( 'TBTEC\VERSION', '1.20.2' );
define( 'TBTEC\PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'TBTEC\PLUGIN_URI', plugin_dir_url( __FILE__ ) );

include_once \TBTEC\PLUGIN_PATH.'includes/TBTEC.php';