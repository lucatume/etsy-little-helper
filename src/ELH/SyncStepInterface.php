<?php


	interface ELH_SyncStepInterface {

		public function go();

		public function set_next( ELH_SyncStepInterface $next );

		public function set_status( ELH_StatusInterface $status );
	}