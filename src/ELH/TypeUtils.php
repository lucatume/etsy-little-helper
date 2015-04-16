<?php


	class ELH_TypeUtils {

		public static function is_a( $value, $type ) {
			if ( ! is_scalar( $value ) ) {
				return false;
			}

			$types = strpos( $type, '|' ) ? explode( '|', $type ) : array( $type );
			$check = false;
			foreach ( $types as $_type ) {
				switch ( $_type ) {
					case 'string':
						$check = $check || is_string( $value );
						break;
					case 'int':
						$check = $check || ( is_numeric( $value ) && intval( $value ) == floatval( $value ) );
						break;
					case 'float':
						$check = $check || ( is_numeric( $value ) && intval( $value ) != floatval( $value ) );
						break;
					default:
						$check = false;
						break;
				}
			}

			return $check;
		}
	}