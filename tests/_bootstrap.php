<?php
	// This is global bootstrap for autoloading
<<<<<<< HEAD
	use tad\FunctionMocker\FunctionMocker as Test;

	require dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php';

	Test::init();
=======
	use tad\FunctionMocker\FunctionMocker;

	require dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php';

	FunctionMocker::init();
>>>>>>> 825db8a79a04feb6f8c31fe4b2ef6b4f0657835d
