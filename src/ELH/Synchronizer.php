<?php


	class ELH_Synchronizer implements ELH_SynchronizerInterface {

		protected $retry_attempts_option = '__elh_sync_attempts';

		/**
		 * @var ELH_SyncStepInterface[]
		 */
		protected $steps = array();

		/**
		 * @var int
		 */
		protected $retry_interval = 3600;

		/**
		 * @var int
		 */
		protected $retry_attempts = 3;

		/**
		 * @var string
		 */
		protected $hook;

		public static function instance() {
			return new self;
		}

		public function add_step( ELH_SyncStepInterface $step ) {
			$this->steps[] = $step;

			return $this;
		}

		public function sync() {
			if ( empty( $this->steps ) ) {
				return;
			}
			$first = $this->steps[0];
			try {
				$first->sync_or_throw();
			} catch ( ELH_SyncException $e ) {
				if ( $this->should_reschedule() ) {
					wp_schedule_single_event( time() + $this->retry_interval, $this->hook );
					$this->increase_reschedule_attempts();
				} else {
					$this->reset_reschedule_attempts();
				}
				$status = array( 'did_sync' => false, 'message' => $e->getMessage() );

				return (object) $status;
			}
		}

		public function set_retry_interval( $retry_interval ) {
			if ( ! is_numeric( $retry_interval ) ) {
				return;
			}
			$this->retry_interval = intval( $retry_interval );
		}

		public function set_retry_attempts( $retry_attempts ) {
			if ( ! is_numeric( $retry_attempts ) ) {
				return;
			}
			$this->retry_attempts = $retry_attempts;
		}

		public function set_hook( $hook ) {
			$this->hook = $hook;
		}

		private function should_reschedule() {
			$attempts = get_option( $this->retry_attempts_option, 0 );

			return $attempts <= $this->retry_attempts;
		}

		private function increase_reschedule_attempts() {
			update_option( $this->retry_attempts_option, get_option( $this->retry_attempts_option, 0 ) + 1 );
		}

		private function reset_reschedule_attempts() {
			update_option( $this->retry_attempts_option, 0 );
		}
	}