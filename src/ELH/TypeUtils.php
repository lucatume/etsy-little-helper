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
						$check = $check || is_string( $value );
						break;
					case 'int':
						$check = $check || ( is_numeric( $value ) && intval( $value ) == floatval( $value ) );
						break;
					case 'float':
						$check = $check || is_numeric( $value ) && ( is_float( floatval( $value ) ) || is_int( intval( $value ) ) );
						break;
					case 'bool':
						$check = true;
						break;
					case 'hsv';
						$m = array();
						if ( preg_match( '/^(\\d+);(\\d+);(\\d+)$/', $value, $m ) ) {
							$check = $check || $m[1] >= '0' && $m[2] >= '0' && $m[3] >= '0' && $m[1] <= '360' && $m[2] <= '100' && $m[3] <= '100';
						}
						break;
					case 'rgb';
						$check = $check || preg_match( '/^#*(?:[0-9a-fA-F]{3}){1,2}$/', $value );
						break;
					default:
//	new ELH_ApiRequestParameter('sort_on', false, 'created', '{created,price,score}'),
//	new ELH_ApiRequestParameter('sort_order', false, 'down', '{up,down}'),
//	new ELH_ApiRequestParameter('min_price', false, false, 'float'),
//	new ELH_ApiRequestParameter('max_price', false, false, 'float'),
//	new ELH_ApiRequestParameter('color', false, false, 'color'),
//	new ELH_ApiRequestParameter('color_accuracy', false, 0, '[0-30]'),
//	new ELH_ApiRequestParameter('tags', false, false, '[string]'),
//	new ELH_ApiRequestParameter('category', false, false, 'string'),
//	new ELH_ApiRequestParameter('translate_keywords', false, 0, 'bool'),
//	new ELH_ApiRequestParameter('include_private', false, 0, 'bool'),
						$m = array();
						// enum
						if ( preg_match( '/^\\{(.+)\\}$/', $_type, $m ) ) {
							$legit = $m[1];
							$check = $check || in_array( $value, explode( ',', $legit ) );
							break;
						}
						if ( preg_match( '/^\\[(.+)-(.+)\\]$/', $_type, $m ) ) {
							$check = $check || in_array( $value, range( $m[1], $m[2] ) );
							break;
						}
						if ( preg_match( '/^\\[(\w+)\\]$/', $_type, $m ) ) {
							if ( is_string( $value ) ) {
								$check = true;
								if ( ! strpos( $value, ',' ) ) {
									break;
								}
								foreach ( explode( ',', $value ) as $v ) {
									if ( ! self::is_a( $v, $m[1] ) ) {
										$check = false;
										break;
									}
								}

							}
							break;
						}
						$check = $check || false;
						break;
				}
			}
		} catch ( Exception $e ) {
		}

		return $check;
	}
}