<?php
	/**
	 * Plugin Name: Etsy Little Helper
	 * Plugin URI: http://theAverageDev.com
	 * Description: An Etsy tool for WordPress websites.
	 * Version: 1.0
	 * Author: theAverageDev
	 * Author URI: http://theAverageDev.com
	 * License: GPL2
	 */

	require 'vendor/autoload_52.php';

	define( 'ELH_PLUGIN_FILE', __FILE__ );

	$di_container = ELH_DI::instance();
	$di_container->set_dependencies();
	ELH_Main::instance( $di_container )
		->hook_base()
		->hook_synchronizer();
