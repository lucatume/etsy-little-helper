<?php


	class ELH_Synchronizer implements ELH_SynchronizerInterface {

		/**
		 * @var ELH_StepInterface
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

		public function set_strategy_selector( ELH_StrategySelectorInterface $strategy_selector ) {
			$this->strategy_selector = $strategy_selector;
		}

		public function set_first_step( ELH_StepInterface $step ) {
			$this->first_step = $step;

			return $this;
		}

		public function sync() {
			if ( empty( $this->first_step ) ) {
				return;
			}
			$status = new ELH_Status();
			try {
				$this->first_step->set_status( $status );
				$this->first_step->run();
			} catch ( ELH_SyncException $e ) {
				$strategy = $this->strategy_selector->get_strategy_for( $e, $status );
				$strategy->set( 'exception', $e );
				$strategy->set( 'status', $status );
				$strategy->run();
			}
		}
	}