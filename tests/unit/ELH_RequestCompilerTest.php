<?php

	use tad\FunctionMocker\FunctionMocker as Test;

	class ELH_RequestCompilerTest extends \PHPUnit_Framework_TestCase {

		protected function setUp() {
			Test::setUp();
		}

		protected function tearDown() {
			Test::tearDown();
		}

		/**
		 * @test
		 * it should be instantiatable
		 */
		public function it_should_be_instantiatable() {
			Test::assertInstanceOf( 'ELH_RequestCompiler', new ELH_RequestCompiler() );
		}

		/**
		 * @test
		 * it should replace string parameters in the uri
		 */
		public function it_should_replace_string_parameters_in_the_uri() {
			$sut     = new ELH_RequestCompiler();
			$params  = [
				new ELH_ApiRequestParameter( 'foo', true, 'one', 'string' ),
				new ELH_ApiRequestParameter( 'baz', false, 10, 'int' ),
				new ELH_ApiRequestParameter( 'bar', false, 'none', 'string' )
			];
			$request = Test::replace( 'ELH_ApiRequestInterface' )->method( 'parameters', $params )
			               ->method( 'uri', '/listings/:foo/shops' )->get();
			$sut->set_request( $request );

			$data = [ 'foo' => 'some_value', 'baz' => '23', 'bar' => 'woo' ];
			$out  = $sut->get_compiled_request( $data );

			$exp = '/listings/some_value/shops?baz=23&bar=woo';
			Test::assertEquals( $exp, $out );
		}

		/**
		 * @test
		 * it should not append optional parameters if missing values
		 */
		public function it_should_not_append_optional_parameters_if_missing_values() {
			$sut     = new ELH_RequestCompiler();
			$params  = [
				new ELH_ApiRequestParameter( 'foo', true, 'one', 'string' ),
				new ELH_ApiRequestParameter( 'baz', false, 10, 'int' ),
				new ELH_ApiRequestParameter( 'bar', false, 'none', 'string' )
			];
			$request = Test::replace( 'ELH_ApiRequestInterface' )->method( 'parameters', $params )
			               ->method( 'uri', '/listings/:foo/shops' )->get();
			$sut->set_request( $request );

			$data = [ 'foo' => 'some_value', 'bar' => 'woo' ];
			$out  = $sut->get_compiled_request( $data );

			$exp = '/listings/some_value/shops?bar=woo';
			Test::assertEquals( $exp, $out );
		}

		/**
		 * @test
		 * it should use default values for required parameters if missing
		 */
		public function it_should_use_default_values_for_required_parameters_if_missing() {
			$sut     = new ELH_RequestCompiler();
			$params  = [
				new ELH_ApiRequestParameter( 'foo', true, 'one', 'string' ),
				new ELH_ApiRequestParameter( 'baz', false, 10, 'int' ),
				new ELH_ApiRequestParameter( 'bar', false, 'none', 'string' )
			];
			$request = Test::replace( 'ELH_ApiRequestInterface' )->method( 'parameters', $params )
			               ->method( 'uri', '/listings/:foo/shops' )->get();
			$sut->set_request( $request );

			$data = [ 'bar' => 'woo' ];
			$out  = $sut->get_compiled_request( $data );

			$exp = '/listings/one/shops?bar=woo';
			Test::assertEquals( $exp, $out );
		}

		/**
		 * @test
		 * it should throw an exception if a required parameter is missing and has no default value
		 */
		public function it_should_throw_an_exception_if_a_required_parameter_is_missing_and_has_no_default_value() {
			$this->setExpectedException( 'ELH_RequestParameterException' );

			$sut     = new ELH_RequestCompiler();
			$params  = [
				new ELH_ApiRequestParameter( 'foo', true, null, 'string' ),
				new ELH_ApiRequestParameter( 'baz', false, 10, 'int' ),
				new ELH_ApiRequestParameter( 'bar', false, 'none', 'string' )
			];
			$request = Test::replace( 'ELH_ApiRequestInterface' )->method( 'parameters', $params )
			               ->method( 'uri', '/listings/:foo/shops' )->get();
			$sut->set_request( $request );

			$data = [ 'bar' => 'woo' ];
			$out  = $sut->get_compiled_request( $data );
		}

		/**
		 * @test
		 * it should throw an exception if a required parameter has the wrong type
		 */
		public function it_should_throw_an_exception_if_a_required_parameter_has_the_wrong_type() {
			$this->setExpectedException( 'ELH_RequestParameterException' );

			Test::replace('ELH_TypeUtils::is_a', false);
			$sut     = new ELH_RequestCompiler();
			$params  = [
				new ELH_ApiRequestParameter( 'foo', true, null, 'string' ),
				new ELH_ApiRequestParameter( 'baz', false, 10, 'int' ),
				new ELH_ApiRequestParameter( 'bar', false, 'none', 'string' )
			];
			$request = Test::replace( 'ELH_ApiRequestInterface' )->method( 'parameters', $params )
			               ->method( 'uri', '/listings/:foo/shops' )->get();
			$sut->set_request( $request );

			$data = [ 'foo' => 23, 'bar' => 'woo' ];
			$out  = $sut->get_compiled_request( $data );
		}

		/**
		 * @test
		 * it should throw an exception if an optional parameter has the wrong type
		 */
		public function it_should_throw_an_exception_if_an_optional_parameter_has_the_wrong_type() {
			$this->setExpectedException( 'ELH_RequestParameterException' );

			Test::replace('ELH_TypeUtils::is_a', false);

			$sut     = new ELH_RequestCompiler();
			$params  = [
				new ELH_ApiRequestParameter( 'foo', true, null, 'string' ),
				new ELH_ApiRequestParameter( 'baz', false, 10, 'int' ),
				new ELH_ApiRequestParameter( 'bar', false, 'none', 'string' )
			];
			$request = Test::replace( 'ELH_ApiRequestInterface' )->method( 'parameters', $params )
			               ->method( 'uri', '/listings/:foo/shops' )->get();
			$sut->set_request( $request );

			$data = [ 'foo' => 'some', 'bar' => 23 ];

			$out  = $sut->get_compiled_request( $data );
		}
	}