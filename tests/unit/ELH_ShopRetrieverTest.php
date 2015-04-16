<?php

	use tad\FunctionMocker\FunctionMocker as Test;

	class ELH_ShopRetrieverTest extends \PHPUnit_Framework_TestCase {

		protected function setUp() {
			Test::setUp();
			Test::replace( 'get_option' );
			Test::replace( 'wp_remote_get' );
		}

		protected function tearDown() {
			Test::tearDown();
		}

		/**
		 * @test
		 * it should raise an exception if keychain is not set on run
		 */
		public function it_should_raise_an_exception_if_keychain_is_not_set_on_run() {
			$this->setExpectedException( 'ELH_MissingStepRequirement' );

			$sut = new ELH_ShopRetriever();

			$sut->set_api( Test::replace( 'ELH_ApiInterface' )->get() );
			$sut->set_request( Test::replace( 'ELH_ApiRequestInterface' )->get() );
			$sut->set_request_compiler( Test::replace( 'ELH_RequestCompilerInterface' )->get() );

			$sut->run();
		}

		/**
		 * @test
		 * it should raise an exception if api is not set on run
		 */
		public function it_should_raise_an_exception_if_api_is_not_set_on_run() {
			$this->setExpectedException( 'ELH_MissingStepRequirement' );

			$sut = new ELH_ShopRetriever();

			$sut->set_keychain( test::replace( 'ELH_KeychainInterface' )->get() );
			$sut->set_request( Test::replace( 'ELH_ApiRequestInterface' )->get() );
			$sut->set_request_compiler( Test::replace( 'ELH_RequestCompilerInterface' )->get() );

			$sut->run();
		}

		/**
		 * @test
		 * it should raise an exception if request is not set on run
		 */
		public function it_should_raise_an_exception_if_request_is_not_set_on_run() {
			$this->setExpectedException( 'ELH_MissingStepRequirement' );

			$sut = new ELH_ShopRetriever();

			$sut->set_api( Test::replace( 'ELH_ApiInterface' )->get() );
			$sut->set_keychain( test::replace( 'ELH_KeychainInterface' )->get() );
			$sut->set_request_compiler( Test::replace( 'ELH_RequestCompilerInterface' )->get() );

			$sut->run();
		}

		/**
		 * @test
		 * it should raise an exception if request compiler is not set on run
		 */
		public function it_should_raise_an_exception_if_request_compiler_is_not_set_on_run() {
			$this->setExpectedException( 'ELH_MissingStepRequirement' );

			$sut = new ELH_ShopRetriever();

			$sut->set_api( Test::replace( 'ELH_ApiInterface' )->get() );
			$sut->set_keychain( test::replace( 'ELH_KeychainInterface' )->get() );
			$sut->set_request( Test::replace( 'ELH_ApiRequestInterface' )->get() );

			$sut->run();
		}

		/**
		 * @test
		 * it should fetch the userId from the options when running
		 */
		public function it_should_fetch_the_user_id_from_the_options_when_running() {
			$sut = new ELH_ShopRetriever();

			$sut->set_api( Test::replace( 'ELH_ApiInterface' )->get() );
			$sut->set_keychain( test::replace( 'ELH_KeychainInterface' )->get() );
			$request = Test::replace( 'ELH_ApiRequestInterface' )->get();
			$sut->set_request( $request );
			$request_compiler = Test::replace( 'ELH_RequestCompilerInterface' )->get();
			$sut->set_request_compiler( $request_compiler );
			$get_option    = Test::replace( 'get_option', 23 );
			$response      = [
				'response' => [
					'code'    => 200,
					'message' => 'Ok'
				]
			];
			$wp_remote_get = Test::replace( 'wp_remote_get', $response );

			$sut->run();

			$get_option->wasCalledWithOnce( [ ELH_Main::USER_ID_OPTION ] );
		}

		/**
		 * @test
		 * it should request the api key when running
		 */
		public function it_should_request_the_api_key_when_running() {
			$sut = new ELH_ShopRetriever();

			$api = Test::replace( 'ELH_ApiInterface' )->method( 'get_api_key' )->get();
			$sut->set_api( $api );
			$sut->set_keychain( test::replace( 'ELH_KeychainInterface' )->get() );
			$request = Test::replace( 'ELH_ApiRequestInterface' )->get();
			$sut->set_request( $request );
			$request_compiler = Test::replace( 'ELH_RequestCompilerInterface' )->method( 'set_request' )
			                        ->method( 'get_compiled_request' )->get();
			$sut->set_request_compiler( $request_compiler );
			$get_option    = Test::replace( 'get_option', 23 );
			$response      = [
				'response' => [
					'code'    => 200,
					'message' => 'Ok'
				]
			];
			$wp_remote_get = Test::replace( 'wp_remote_get', $response );

			$sut->run();

			$api->wasCalledOnce( 'get_api_key' );
		}

		/**
		 * @test
		 * it should compile the request in the run method
		 */
		public function it_should_compile_the_request_in_the_run_method() {
			$sut = new ELH_ShopRetriever();

			$api = Test::replace( 'ELH_ApiInterface' )->method( 'get_api_key', 'foo' )->get();
			$sut->set_api( $api );
			$sut->set_keychain( test::replace( 'ELH_KeychainInterface' )->get() );
			$request = Test::replace( 'ELH_ApiRequestInterface' )->get();
			$sut->set_request( $request );
			$request_compiler = Test::replace( 'ELH_RequestCompilerInterface' )->method( 'set_request' )
			                        ->method( 'get_compiled_request' )->get();
			$sut->set_request_compiler( $request_compiler );
			$get_option    = Test::replace( 'get_option', 23 );
			$response      = [
				'response' => [
					'code'    => 200,
					'message' => 'Ok'
				]
			];
			$wp_remote_get = Test::replace( 'wp_remote_get', $response );

			$sut->run();

			$request_compiler->wasCalledWithOnce( [ $request ], 'set_request' );
			$request_compiler->wasCalledWithOnce( [ [ 'user_id' => 23, 'api_key' => 'foo' ] ], 'get_compiled_request' );
		}


		/**
		 * @test
		 * it should send the GET request in the run method
		 */
		public function it_should_send_the_get_request_in_the_run_method() {
			$sut = new ELH_ShopRetriever();

			$fake_api_key = 'foo';
			$api          = Test::replace( 'ELH_ApiInterface' )->method( 'get_api_key', $fake_api_key )->get();
			$sut->set_api( $api );
			$sut->set_keychain( test::replace( 'ELH_KeychainInterface' )->get() );
			$request = Test::replace( 'ELH_ApiRequestInterface' )->get();
			$sut->set_request( $request );
			$request_compiler = Test::replace( 'ELH_RequestCompilerInterface' )->method( 'set_request' )
			                        ->method( 'get_compiled_request', '/users/someuser/shops?api_key=foo' )->get();
			$sut->set_request_compiler( $request_compiler );
			$get_option    = Test::replace( 'get_option', 'someuser' );
			$response      = [
				'response' => [
					'code'    => 200,
					'message' => 'Ok'
				]
			];
			$wp_remote_get = Test::replace( 'wp_remote_get', $response );

			$sut->run();

			$url = 'https://openapi.etsy.com/v2/users/someuser/shops?api_key=foo';
			$wp_remote_get->wasCalledWithOnce( [ $url ] );
		}

		/**
		 * @test
		 * it should raise an exception if the result is not a success
		 */
		public function it_should_raise_an_exception_if_the_result_is_not_a_success() {
			$this->setExpectedException( 'ELH_SyncException' );

			$sut = new ELH_ShopRetriever();

			$fake_api_key = 'foo';
			$api          = Test::replace( 'ELH_ApiInterface' )->method( 'get_api_key', $fake_api_key )->get();
			$sut->set_api( $api );
			$sut->set_keychain( test::replace( 'ELH_KeychainInterface' )->get() );
			$request = Test::replace( 'ELH_ApiRequestInterface' )->get();
			$sut->set_request( $request );
			$request_compiler = Test::replace( 'ELH_RequestCompilerInterface' )->method( 'set_request' )
			                        ->method( 'get_compiled_request', '/users/someuser/shops?api_key=foo' )->get();
			$sut->set_request_compiler( $request_compiler );
			$get_option    = Test::replace( 'get_option', 'someuser' );
			$response      = [
				'response' => [
					'code'    => 404,
					'message' => 'The supplied uri doesn\'t map to a valid command'
				]
			];
			$wp_remote_get = Test::replace( 'wp_remote_get', $response );

			$sut->run();
		}

		/**
		 * @test
		 * it should call next step and pass it the data if success
		 */
		public function it_should_call_next_step_and_pass_it_the_data_if_success() {
			$sut = new ELH_ShopRetriever();

			$fake_api_key = 'foo';
			$api          = Test::replace( 'ELH_ApiInterface' )->method( 'get_api_key', $fake_api_key )->get();
			$sut->set_api( $api );
			$sut->set_keychain( test::replace( 'ELH_KeychainInterface' )->get() );
			$request = Test::replace( 'ELH_ApiRequestInterface' )->get();
			$sut->set_request( $request );
			$request_compiler = Test::replace( 'ELH_RequestCompilerInterface' )->method( 'set_request' )
			                        ->method( 'get_compiled_request', '/users/someuser/shops?api_key=foo' )->get();
			$status           = Test::replace( 'ELH_Status' )->method( 'set' )->get();
			$sut->set_status( $status );
			$sut->set_request_compiler( $request_compiler );
			$next = Test::replace( 'ELH_AbstractSyncStep' )->method( 'set_status' )->method( 'run' )->get();
			$sut->set_next( $next );

			$get_option    = Test::replace( 'get_option', 'someuser' );
			$response      = [
				'response' => [
					'code'    => 200,
					'message' => 'Ok'
				]
			];
			$wp_remote_get = Test::replace( 'wp_remote_get', $response );

			$sut->run();

			$status->wasCalledWithOnce( [ 'data', $response ], 'set' );
			$next->wasCalledWithOnce( [ $status ], 'set_status' );
			$next->wasCalledOnce( 'run' );
		}

	}