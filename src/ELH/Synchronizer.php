<?php


	class ELH_Synchronizer implements ELH_SynchronizerInterface {

		/**
		 * @var ELH_SyncStepInterface
		 */
		protected $first_step;

		/**
		 * @var ELH_StrategySelectorInterface
		 */
		protected $strategy_selector;

		public static function instance( ELH_StrategySelectorInterface $strategy_selector ) {
			$instance = new self;

			$instance->set_strategy_selector( $strategy_selector );

			return $instance;
		}

		public function set_first_step( ELH_SyncStepInterface $step ) {
			$this->first_step = $step;

			return $this;
		}

		public function sync() {
			if ( empty( $this->first_step ) ) {
				return;
			}
			try {
				$status = new ELH_Status();
				$this->first_step->set_status( $status );
				$this->first_step->sync_or_throw();
			} catch ( ELH_SyncException $e ) {
				$handler = $this->strategy_selector->get_strategy_for( $e, $status );
				$handler->set( 'exception', $e );
				$handler->set( 'status', $status );
				$handler->run();
			}
		}

		public function set_strategy_selector( ELH_StrategySelectorInterface $strategy_selector ) {
			$this->strategy_selector = $strategy_selector;
		}
	}