<?php
use tad\FunctionMocker\FunctionMocker as Test;

class ELH_ListingRetrieverTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		Test::setUp();
	}

	public function tearDown() {
		Test::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		Test::assertInstanceOf( 'ELH_ListingRetriever', new ELH_ListingRetriever() );
	}

	/**
	 * @test
	 * it should get the shop_id from the status object
	 */
	public function it_should_get_the_shop_id_from_the_status_object() {
		$sut              = new ELH_ListingRetriever();
		$keychain         = new ELH_Keychain();
		$api              = Test::replace( 'ELH_ApiInterface' )->method( 'get_api_key', 'foo' )->get();
		$request          = new ELH_ShopListingsApiRequest();
		$request_compiler = new ELH_RequestCompiler();
		$status           = Test::replace( 'ELH_StatusInterface' )->method( 'get', '4422' )->get();

		$sut = ELH_ListingRetriever::instance( $keychain, $api, $request, $request_compiler );
		$sut->set_status( $status );

		$response = [
			'response' => [
				'code'    => 200,
				'message' => 'Ok'
			]
		];
		Test::replace( 'wp_remote_get', $response );

		$sut->run();

		$status->wasCalledWithOnce( [ 'shop_id' ], 'get' );
	}

	/**
	 * @test
	 * it should raise an exception if the response is not a 200 status
	 */
	public function it_should_raise_an_exception_if_the_response_is_not_a_200_status() {
		$sut              = new ELH_ListingRetriever();
		$keychain         = new ELH_Keychain();
		$api              = Test::replace( 'ELH_ApiInterface' )->method( 'get_api_key', 'foo' )->get();
		$request          = new ELH_ShopListingsApiRequest();
		$request_compiler = new ELH_RequestCompiler();
		$status           = Test::replace( 'ELH_StatusInterface' )->method( 'get', '4422' )->get();

		$sut = ELH_ListingRetriever::instance( $keychain, $api, $request, $request_compiler );
		$sut->set_status( $status );

		$response = [
			'response' => [
				'code'    => 402,
				'message' => 'Not ok'
			]
		];
		Test::replace( 'wp_remote_get', $response );

		$this->setExpectedException( 'ELH_SyncException' );

		$sut->run();
	}
}
