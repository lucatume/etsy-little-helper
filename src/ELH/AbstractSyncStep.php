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
		protected $next;

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

		public function run() {

		}

		/**
		 * @return array
		 */
		protected function get_request_url() {
			$data = $this->get_request_data();

			$this->request_compiler->set_request( $this->request );
			$uri = $this->request_compiler->get_compiled_request( $data );

			return ELH_Main::API_BASE . $uri;
		}

	}