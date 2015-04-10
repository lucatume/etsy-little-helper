<?php


	interface ELH_SyncStepInterface {

		public function sync_or_throw();

		public function set_next( ELH_SyncStepInterface $next );
	}