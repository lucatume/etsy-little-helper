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

	require 'autoload.php';

	define( 'ELH_PLUGIN_FILE', __FILE__ );

	ELH_Main::instance()->hook();
