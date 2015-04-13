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

			$data = [ 'foo' => 'some_value', 'baz' => 'another_value', 'bar' => 'woo' ];
			$out  = $sut->get_compiled_request( $data );

			$exp = '/listings/some_value/shops?baz=another_value&bar=woo';
			Test::assertEquals( $exp, $out );
		}
	}