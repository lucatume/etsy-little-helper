<?php


	/**
	 * Created by PhpStorm.
	 * User: Luca
	 * Date: 24/03/15
	 * Time: 18:05
	 */
	interface ELH_SyncStepInterface {

		public function sync_or_throw();

		public function set_next( ELH_SyncStepInterface $next );
	}