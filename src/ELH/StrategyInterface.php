<?php


	interface ELH_StrategyInterface {

		public function set( $key, $value );

		public function run();
	}