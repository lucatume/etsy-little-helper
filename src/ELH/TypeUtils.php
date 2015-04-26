<?php


class ELH_TypeUtils {

//	new ELH_ApiRequestParameter('sort_on', false, 'created', '{created,price,score}'),
//	new ELH_ApiRequestParameter('sort_order', false, 'down', '{up,down}'),
//	new ELH_ApiRequestParameter('min_price', false, false, 'float'),
//	new ELH_ApiRequestParameter('max_price', false, false, 'float'),
//	new ELH_ApiRequestParameter('color', false, false, 'color'),
//	new ELH_ApiRequestParameter('color_accuracy', false, 0, 'int[0-30]'),
//	new ELH_ApiRequestParameter('tags', false, false, '[string]'),
//	new ELH_ApiRequestParameter('category', false, false, 'string'),
//	new ELH_ApiRequestParameter('translate_keywords', false, 0, 'bool'),
//	new ELH_ApiRequestParameter('include_private', false, 0, 'bool'),

	public static function is_a( $value, $type ) {
		if ( ! is_scalar( $value ) ) {
			return false;
		}
		$check = false;
		try {
			$types = strpos( $type, '|' ) ? explode( '|', $type ) : array( $type );
			foreach ( $types as $_type ) {
				switch ( $_type ) {
					case 'string':
						$check = $check || self::is_string( $value );
						break;
					case 'int':
						$check = $check || self::is_int( $value );
						break;
					case 'float':
						$check = $check || self::is_float( $value );
						break;
					case 'bool':
						$check = true;
						break;
					case 'hsv';
						$check = $check || self::is_hsv( $value );
						break;
					case 'rgb';
						$check = $check || self::is_rgb( $value );
						break;
					default:
						$check = $check || self::is_in_enum( $value, $_type ) || self::is_in_interval( $value, $_type ) || self::is_in_list( $value, $_type );
						break;
				}
			}
		} catch ( Exception $e ) {
		}

		return $check;
	}

	public static function is_string( $value ) {
		return is_string( $value );
	}

	public static function is_int( $value ) {
		return ( is_numeric( $value ) && intval( $value ) == floatval( $value ) );
	}

	public static function is_float( $value ) {
		return is_numeric( $value ) && ( is_float( floatval( $value ) ) || is_int( intval( $value ) ) );
	}

	public static function is_hsv( $value ) {
		$m = array();
		if ( preg_match( '/^(\\d+);(\\d+);(\\d+)$/', $value, $m ) ) {
			return $m[1] >= '0' && $m[2] >= '0' && $m[3] >= '0' && $m[1] <= '360' && $m[2] <= '100' && $m[3] <= '100';
		}

		return false;
	}

	public static function is_rgb( $value ) {
		return preg_match( '/^#*(?:[0-9a-fA-F]{3}){1,2}$/', $value );
	}

	public static function is_in_enum( $value, $type ) {
		$m = array();
		if ( preg_match( '/^\\{(.+)\\}$/', $type, $m ) ) {
			$legit = $m[1];

			return in_array( $value, explode( ',', $legit ) );
		}

		return false;
	}

	public static function is_in_interval( $value, $_type ) {
		$m = array();
		if ( preg_match( '/^\\[(.+)-(.+)\\]$/', $_type, $m ) ) {
			return in_array( $value, range( $m[1], $m[2] ) );
		}

		return false;
	}

	public static function is_in_list( $value, $_type ) {
		$m = array();
		if ( preg_match( '/^\\[(\w+)\\]$/', $_type, $m ) ) {
			if ( is_string( $value ) ) {
				if ( ! strpos( $value, ',' ) ) {
					return true;
				}
				foreach ( explode( ',', $value ) as $v ) {
					if ( ! self::is_a( $v, $m[1] ) ) {
						return false;
					}

					return true;
				}
			}

			return false;
		}

		return false;
	}
}