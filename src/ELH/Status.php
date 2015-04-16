<?php


	class ELH_Status implements ELH_StatusInterface {

		/** @var  array */
		protected $data = array();

		public function set( $key, $value ) {
			$this->data[ $key ] = $value;
		}

		public function get( $key ) {
			return isset( $this->data[ $key ] ) ? $this->data[ $key ] : null;
		}
	}