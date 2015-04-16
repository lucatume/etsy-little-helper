<?php

	use tad\FunctionMocker\FunctionMocker as Test;

	class ELH_TypeUtilsTest extends \PHPUnit_Framework_TestCase {

		protected function setUp() {
			Test::setUp();
		}

		protected function tearDown() {
			Test::tearDown();
		}

		public function properValueTypePairs() {
			return [
				[ 'foo', 'string' ],
				[ 23, 'int' ],
				[ '23', 'int' ],
				[ 1.5, 'float' ],
				[ '1.5', 'float' ]
			];
		}

		/**
		 * @test
		 * it should return true for proper value and type pairs
		 * @dataProvider properValueTypePairs
		 */
		public function it_should_return_true_for_proper_value_and_type_pairs( $value, $type ) {
			Test::assertTrue( ELH_TypeUtils::is_a( $value, $type ) );
		}

		public function notProperValueTypePairs() {
			return [
				[ 23, 'string' ],
				[ 1.5, 'string' ],
				[ 'foo', 'int' ],
				[ 1.5, 'int' ],
				[ '1.5', 'int' ],
				[ 23, 'float' ],
				[ '23', 'float' ],
				[ 'foo', 'float' ]
			];

		}

		/**
		 * @test
		 * it should return false for not proper value and type pairs
		 * @dataProvider notProperValueTypePairs
		 */
		public function it_should_return_false_for_not_proper_value_and_type_pairs( $value, $type ) {
			Test::assertFalse( ELH_TypeUtils::is_a( $value, $type ) );
		}

		public function orProperValueTypePairs() {
			return [
				[ 'foo', 'string|int' ],
				[ '12', 'string|int' ],
				[ 23, 'string|int' ],
				[ 23, 'int|float' ],
				[ '23', 'int|float' ],
				[ '23.23', 'int|float' ],
				[ 23.23, 'int|float' ],
				[ 'foo', 'float|string' ],
				[ '23.23', 'float|string' ],
				[ 23.23, 'float|string' ],
				[ 'foo', 'string|int|float' ],
				[ 23, 'string|int|float' ],
				[ '23', 'string|int|float' ],
				[ 1.5, 'string|int|float' ],
				[ '1.5', 'string|int|float' ]
			];
		}

		/**
		 * @test
		 * it should allow specifying more than one allowed type
		 * @dataProvider orProperValueTypePairs
		 */
		public function it_should_allow_specifying_more_than_one_allowed_type( $value, $type ) {
			Test::assertTrue( ELH_TypeUtils::is_a( $value, $type ) );
		}

		public function nonScalarValues() {
			return [
				[ array( 23 ) ],
				[ new stdClass() ]
			];

		}

		/**
		 * @test
		 * it should return false for non scalar values
		 * @dataProvider nonScalarValues
		 */
		public function it_should_return_false_for_non_scalar_values( $value ) {
			Test::assertFalse( ELH_TypeUtils::is_a( $value, 'not relevant' ) );
		}

		/**
		 * @test
		 * it should fail on non valid types
		 */
		public function it_should_fail_on_non_valid_types() {
			Test::assertFalse( ELH_TypeUtils::is_a( 'foo', 'not a type' ) );
		}
	}