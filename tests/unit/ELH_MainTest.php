<?php
	use tad\FunctionMocker\FunctionMocker as Test;

	class ELH_MainTest extends PHPUnit_Framework_TestCase {

		public function setUp() {
			Test::setUp();
		}

		public function tearDown() {
			Test::tearDown();
		}

		/**
		 * @test
		 * it should hook activation, deactivation and text domain
		 */
		public function it_should_hook_activation_deactivation_and_text_domain() {
			define( 'ELH_ROOT', 'foo' );
			$register_activation_hook = Test::replace( 'register_activation_hook' );
			$register_deactivation_hook = Test::replace( 'register_deactivation_hook' );
			$add_action = Test::replace( 'add_action' );

			$sut = new ELH_Main();

			$sut->hook_base();

			$register_activation_hook->wasCalledWithOnce( [ 'foo', [ $sut, 'activate' ] ] );
			$register_deactivation_hook->wasCalledWithOnce( [ 'foo', [ $sut, 'deactivate' ] ] );
			$add_action->wasCalledWithOnce( [ 'plugins_loaded', [ $sut, 'load_text_domain' ] ] );
		}

		/**
		 * @test
		 * it should hook_base the synchronizer to the sync hook_base on hook_base method
		 */
		public function it_should_hook_the_synchronizer_to_the_sync_hook_on_hook_method() {
			$sync = Test::replace( 'ELH_Synchronizer' );
			$dic = Test::replace( 'ELH_DI' )->method( 'make', $sync )->get();
			$add_action = Test::replace( 'add_action' );

			$sut = new ELH_Main();
			$sut->set_di_container( $dic );

			$sut->hook_synchronizer();

			$add_action->wasCalledWithOnce( [ ELH_Main::SYNC_HOOK, [ $sync, 'sync' ] ] );
		}

		/**
		 * @test
		 * it should schedule the sync on activation
		 */
		public function it_should_schedule_the_sync_on_activation() {
			$wp_clear_scheduled_hook = Test::replace( 'wp_clear_scheduled_hook' );
			$wp_schedule_event = Test::replace( 'wp_schedule_event' );
			$time = time();
			$dic = Test::replace( 'ELH_DI' )->method( 'get_var', function ( $alias ) use ( $time ) {
				$map = [
					'sync_start_time' => $time,
					'sync_interval'   => 'hourly'
				];

				return $map[ $alias ];
			} )->get();

			$sut = new ELH_Main();
			$sut->set_di_container( $dic );

			$sut->activate();

			$wp_clear_scheduled_hook->wasCalledWithOnce( [ ELH_Main::SYNC_HOOK ] );
			$wp_schedule_event->wasCalledWithOnce( [ $time, 'hourly', ELH_Main::SYNC_HOOK ] );
		}

		/**
		 * @test
		 * it should clear sync schedule on deactivation
		 */
		public function it_should_clear_sync_schedule_on_deactivation() {
			$wp_clear_scheduled_hook = Test::replace( 'wp_clear_scheduled_hook' );

			$sut = new ELH_Main();

			$sut->deactivate();

			$wp_clear_scheduled_hook->wasCalledWithOnce( [ ELH_Main::SYNC_HOOK ] );
		}
	}
