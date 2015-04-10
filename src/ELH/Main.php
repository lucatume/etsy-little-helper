<?php


	class ELH_Main {

		const SYNC_HOOK = 'elh_daily_sync';

		/**
		 * @var ELH_DI
		 */
		protected $di_container;

		public static function instance( ELH_DI $di_container ) {
			$instance = new self;

			$instance->set_di_container( $di_container );

			return $instance;
		}

		public function hook() {
			register_activation_hook( ELH_PLUGIN_FILE, array( $this, 'activate' ) );
			register_deactivation_hook( ELH_PLUGIN_FILE, array( $this, 'deactivate' ) );
			add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );

			$synchronizer = ELH_DI::instance()->make( 'synchronizer' );

			add_action( self::SYNC_HOOK, array( $synchronizer, 'sync' ) );
		}

		public function activate() {
			wp_schedule_event( time(), 'hourly', self::SYNC_HOOK );
		}

		public function deactivate() {

		}

		public function load_text_domain() {
			load_plugin_textdomain( 'elh', false, dirname( plugin_basename( ELH_PLUGIN_FILE ) ) . '/languages' );
		}

		private function set_di_container( ELH_DI $di_container ) {
			$this->di_container = $di_container;
		}
	}