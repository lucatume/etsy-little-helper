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
			$compiled                    = $this->request->uri();
			$required_and_default_values = $this->get_required_and_defaults();
			$data                        = array_merge( $required_and_default_values, $data );

			$this->check_parameters( $data );

			$vars = array();
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

		private function get_required_and_defaults() {
			$list = array();

			foreach ( $this->request->parameters() as $parameter ) {
				if ( $parameter->is_required() && ! is_null( $parameter->default_value() ) ) {
					$list[ $parameter->name() ] = $parameter->default_value();
				}
			}

			return $list;
		}

		private function check_parameters( array $data ) {
			foreach ( $this->request->parameters() as $parameter ) {
				$this->check_parameter_value( $data, $parameter );
				if ( isset( $data[ $parameter->name() ] ) ) {
					$value = $data[ $parameter->name() ];
					$this->check_parameter_type( $value, $parameter );
				}
			}
		}

		/**
		 * @param array $data
		 * @param       $parameter
		 *
		 * @throws ELH_RequestParameterException
		 */
		private function check_parameter_value( array $data, $parameter ) {
			if ( $parameter->is_required() && ! array_key_exists( $parameter->name(), $data ) ) {
				throw new ELH_RequestParameterException( sprintf( 'Required parameter %s has no value.', $parameter->name() ) );
			}
		}

		/**
		 * @param $value
		 * @param $parameter
		 *
		 * @throws ELH_RequestParameterException
		 */
		private function check_parameter_type( $value, $parameter ) {
			if ( ! ELH_TypeUtils::is_a( $value, $parameter->type() ) ) {
				throw new ELH_RequestParameterException( sprintf( 'Parameter %s must be of type %s.', $parameter->name(), $parameter->type() ) );
			}
		}
	}