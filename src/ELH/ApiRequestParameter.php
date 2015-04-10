<?php


	class ELH_ApiRequestParameter {

		/**
		 * @return string
		 */
		public function name() {
			return $this->name;
		}

		/**
		 * @return boolean
		 */
		public function is_required() {
			return $this->required;
		}

		/**
		 * @return mixed
		 */
		public function default_value() {
			return $this->default_value;
		}

		/**
		 * @return array
		 */
		public function type() {
			return $this->type;
		}

		/**
		 * @var string
		 */
		private $name;

		/**
		 * @var bool
		 */
		private $required;

		/**
		 * @var mixed
		 */
		private $default_value;

		/**
		 * @var array
		 */
		private $type;

		public function __construct( $name, $required, $default_value, array $type ) {
			$this->name          = $name;
			$this->required      = $required;
			$this->default_value = $default_value;
			$this->type          = $type;
		}
	}