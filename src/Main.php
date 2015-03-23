<?php


	class ELH_Main {

		public static function instance() {
			return new self;
		}

		public function hook() {
			register_activation_hook( ELH_PLUGIN_FILE, array( $this, 'activate' ) );
			register_deactivation_hook( ELH_PLUGIN_FILE, array( $this, 'deactivate' ) );
			add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
		}

		public function activate() {

		}

		public function deactivate() {

		}

		public function load_text_domain() {
			load_plugin_textdomain( 'elh', false, dirname( plugin_basename( ELH_PLUGIN_FILE ) ) . '/languages' );
		}
	}