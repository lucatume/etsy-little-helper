<?php


	interface ELH_StepInterface {

		public function run();

		public function set_next( ELH_StepInterface $next );

		public function set_status( ELH_StatusInterface $status );
	}