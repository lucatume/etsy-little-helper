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

	/**
	 * @test
	 * it should check string enums
	 */
	public function it_should_check_string_enums() {
		Test::assertTrue( ELH_TypeUtils::is_a( 'foo', '{foo,baz}' ) );
		Test::assertTrue( ELH_TypeUtils::is_a( 'baz', '{foo,baz}' ) );
		Test::assertFalse( ELH_TypeUtils::is_a( 'bar', '{foo,baz}' ) );
	}

	/**
	 * @test
	 * it should check int enums
	 */
	public function it_should_check_int_enums() {
		Test::assertTrue( ELH_TypeUtils::is_a( 1, '{1,2,3}' ) );
		Test::assertTrue( ELH_TypeUtils::is_a( 2, '{1,2,3}' ) );
		Test::assertTrue( ELH_TypeUtils::is_a( 3, '{1,2,3}' ) );
		Test::assertFalse( ELH_TypeUtils::is_a( 4, '{1,2,3}' ) );
	}

	public function hsvValues() {
		return [
			[ '50;40;30', true ],
			[ '360;100;100', true ],
			[ '0;0;0', true ],
			[ '360;0;0', true ],
			[ '0;100;0', true ],
			[ '0;0;100', true ]
		];
	}

	/**
	 * @test
	 * it should check HSV
	 * @dataProvider hsvValues
	 */
	public function it_should_check_hsv( $in, $out ) {
		Test::assertEquals( $out, ELH_TypeUtils::is_a( $in, 'hsv' ) );
	}

	public function rgbValues() {
		return [
			[ '#333', true ],
			[ '#111333', true ],
			[ '#AAA', true ],
			[ '#FFF', true ],
			[ '#AAAFFF', true ],
			[ '#DGG', false ],
			[ '#11', false ],
			[ '11', false ],
			[ 'foo', false ],
			[ '111', true ]
		];
	}

	/**
	 * @test
	 * it should allow checking for RGB colors
	 * @dataProvider rgbValues
	 */
	public function it_should_allow_checking_for_rgb_colors( $in, $out ) {
		Test::assertEquals( $out, ELH_TypeUtils::is_a( $in, 'rgb' ) );
	}

	public function intervals() {
		return [
			[ 1, '[1-2]', true ],
			[ 3, '[1-2]', false ],
			[ 3, '[3-20]', true ],
			[ 21, '[3-20]', false ],
			[ 'foo', '[3-20]', false ],
			[ 0, '[1-20]', false ],
			[ '0', '[1-20]', false ],
			[ 0, '[0-20]', true ],
			[ '0', '[0-20]', true ]
		];
	}

	/**
	 * @test
	 * it should allow checking for intervals
	 * @dataProvider intervals
	 */
	public function it_should_allow_checking_for_intervals( $in, $interval, $out ) {
		Test::assertEquals( $out, ELH_TypeUtils::is_a( $in, $interval ) );
	}

	public function arrayOfValues() {
		return [
			[ '0', '[int]', true ],
			[ '0,1', '[int]', true ],
			[ 0, '[int]', false ],
			[ '', '[int]', true ],
			[ 'foo', '[string]', true ],
			[ 'foo,bar', '[string]', true ],
			[ '', '[string]', true ],
			[ '1.5,2', '[float]', true ],
			[ '1.5', '[float]', true ],
			[ 1.5, '[float]', false ]
		];
	}

	/**
	 * @test
	 * it should allow checking for arrays of values
	 * @dataProvider arrayOfValues
	 */
	public function it_should_allow_checking_for_arrays_of_values( $in, $array, $out ) {
		Test::assertEquals( $out, ELH_TypeUtils::is_a( $in, $array ) );
	}

	public function bools() {
		return [
			[ '1', true ],
			[ 1, true ],
			[ '0', true ],
			[ 0, true ],
			[ 0, true ],
			[ array( 1 ), false ],
			[ new stdClass(), false ]
		];
	}

	/**
	 * @test
	 * it should allow checking for booleans
	 * @dataProvider bools
	 */
	public function it_should_allow_checking_for_booleans( $in, $out ) {
		Test::assertEquals( $out, ELH_TypeUtils::is_a( $in, 'bool' ) );

	}
}