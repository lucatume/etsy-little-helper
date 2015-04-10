<?php


	class ELH_UserShopsApiRequest implements ELH_ApiRequestInterface {

		/**
		 * @var ELH_ApiRequestParameter[]
		 */
		private $parameters;

		public function __construct() {
			$this->parameters = array(
				new ELH_ApiRequestParameter( 'user_id', true, false, 'string|int' ),
				new ELH_ApiRequestParameter( 'limit', false, 25, 'int' ),
				new ELH_ApiRequestParameter( 'offset', false, false, 'int' ),
				new ELH_ApiRequestParameter( 'page', false, false, 'int' ),
			);
		}

		public function uri() {
			return '/users/:user_id/shops';
		}

		/**
		 * @return ELH_ApiRequestParameter[]
		 */
		public function parameters() {
			return $this->parameters;
		}

		public function method() {
			return 'findAllUserShops';
		}

		public function http_method() {
			return 'GET';
		}

		public function requires_OAuth() {
			return false;
		}

		public function permission_scope() {
			return 'none';
		}
	}