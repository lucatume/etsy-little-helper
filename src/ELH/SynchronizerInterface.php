<?php


	interface ELH_SynchronizerInterface {

		public function set_first_step( ELH_StepInterface $step );

		public function sync();

		public function set_strategy_selector( ELH_StrategySelectorInterface $strategy_selector );
	}