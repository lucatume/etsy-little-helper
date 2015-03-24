<?php


	class ELH_Main {

		const SYNC_HOOK = 'elh_daily_sync';

		public static function instance() {
			return new self;
		}

		public function hook() {
			register_activation_hook( ELH_PLUGIN_FILE, array( $this, 'activate' ) );
			register_deactivation_hook( ELH_PLUGIN_FILE, array( $this, 'deactivate' ) );
			add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
			wp_schedule_event( time(), 'hourly', self::SYNC_HOOK );

			$synchronizer = ELH_DI::instance()->make( 'synchronizer' );

			add_action( self::SYNC_HOOK, array( $synchronizer, 'sync' ) );
		}

		public function activate() {

		}

		public function deactivate() {

		}

		public function load_text_domain() {
			load_plugin_textdomain( 'elh', false, dirname( plugin_basename( ELH_PLUGIN_FILE ) ) . '/languages' );
		}
	}