<?php
	spl_autoload_register( 'ELH_autoload' );
	function ELH_autoload( $class ) {
		$map = array(
			'ELH_Main' => '/src/Main.php',
		);
		if ( isset( $map[ $class ] ) && file_exists( $file = dirname( __FILE__ ) . $map[ $class ] ) ) {
			include $file;
		}
	}