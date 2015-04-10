<?php


	class ELH_Api implements ELH_ApiInterface {

		/**
		 * @var string
		 */
		protected $api_key = '';

		public function get_api_key() {
			return $this->api_key;
		}

		public function set_api_key( $api_key ) {
			$this->api_key = $api_key;
		}
	}