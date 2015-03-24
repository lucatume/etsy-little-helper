<?php


	class ELH_ShopRetriever implements ELH_SyncStepInterface, ELH_ApiUser {

		protected $keychain;
		protected $api;

		public static function instance( ELH_KeychainInterface $keychain, ELH_ApiInterface $api ) {
			$instance           = new self();
			$instance->keychain = $keychain;
			$instance->api      = $api;
		}

		public function set_keychain( ELH_KeychainInterface $keychain ) {
			$this->keychain = $keychain;
		}

		public function set_api( ELH_ApiInterface $api ) {
			$this->api = $api;
		}

		public function sync_or_throw() {
			// TODO: Implement sync_or_throw() method.
		}

		public function set_next( ELH_SyncStepInterface $next ) {
			// TODO: Implement set_next() method.
		}
	}