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

	$di_container = ELH_DI::instance();

	define( 'ELH_ROOT', __FILE__ );
	define( 'ELH_URL', plugins_url( '/', __FILE__ ) );

	$di_container->set_consts()->set_dependencies();

	ELH_Main::instance( $di_container )->hook_base()->hook_synchronizer();
