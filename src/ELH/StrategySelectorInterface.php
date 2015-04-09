<?php


	interface ELH_StrategySelectorInterface {

		/**
		 * @param Exception  $e
		 * @param mixed|null $data
		 *
		 * @return ELH_StrategyInterface
		 */
		public function get_strategy_for( Exception $e, $data = null );
	}