<?php


	abstract class ELH_AbstractSyncStep implements ELH_ApIUserInterface, ELH_KeychainUserInterface, ELH_StepInterface {

		/**
		 * @var ELH_KeychainInterface
		 */
		protected $keychain;

		/**
		 * @var ELH_ApiInterface
		 */
		protected $api;

		/**
		 * @var ELH_StatusInterface
		 */
		protected $status;

		/**
		 * @var ELH_StepInterface
		 */
		private $next;

		public function set_api( ELH_ApiInterface $api ) {
			$this->api = $api;
		}

		public function set_keychain( ELH_KeychainInterface $keychain ) {
			$this->keychain = $keychain;
		}

		public function set_next( ELH_StepInterface $next ) {
			$this->next = $next;
		}

		public function set_status( ELH_StatusInterface $status ) {
			$this->status = $status;
		}

		abstract public function run();

	}