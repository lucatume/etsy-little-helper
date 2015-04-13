<?php


	class ELH_RequestCompiler {

		/**
		 * @var ELH_ApiRequestInterface
		 */
		protected $request;

		public function set_request( ELH_ApiRequestInterface $request ) {
			$this->request = $request;
		}

		public function get_compiled_request( array $data = array() ) {
			$compiled = $this->request->uri();
			$vars     = array();
			foreach ( $data as $key => $value ) {
				if ( strpos( $compiled, ':' . $key ) ) {

					$compiled = str_replace( ':' . $key, $value, $compiled );
				} else {
					$vars[] = sprintf( '%s=%s', $key, $value );
				}
			}

			$compiled .= '?' . implode( '&', $vars );

			return $compiled;
		}
	}