<?php


	use tad\FunctionMocker\FunctionMocker as Test;

	class SynchronizerTest extends \PHPUnit_Framework_TestCase {

		/**
		 * @var ELH_Synchronizer
		 */
		protected $sut;

<<<<<<< HEAD
=======
		protected function setUp() {
			Test::setUp();
			$this->sut = new ELH_Synchronizer();
			Test::replace( 'get_option', 0 );
			Test::replace( 'wp_schedule_single_event' );
			Test::replace( 'update_option' );
		}

		protected function tearDown() {
			Test::tearDown();
		}

>>>>>>> 825db8a79a04feb6f8c31fe4b2ef6b4f0657835d
		/**
		 * @test
		 * it should run the first step
		 */
		public function it_should_run_the_first_step() {
<<<<<<< HEAD
			$step = Test::replace( 'ELH_StepInterface::run' );
			$sut  = new ELH_Synchronizer();
			$sut->set_first_step( $step );

			$sut->sync();

			$step->wasCalledOnce( 'run' );
=======
			$step = Test::replace( 'ELH_SyncStepInterface::sync_or_throw' );
			$this->sut->add_step( $step );

			$this->sut->sync();

			$step->wasCalledOnce( 'sync_or_throw' );
>>>>>>> 825db8a79a04feb6f8c31fe4b2ef6b4f0657835d
		}

		/**
		 * @test
<<<<<<< HEAD
		 * it should set the status on the first step
		 */
		public function it_should_set_the_status_on_the_first_step() {
			$step = Test::replace( 'ELH_StepInterface' )->method( 'set_status' )->get();
			$sut  = new ELH_Synchronizer();
			$sut->set_first_step( $step );

			$sut->sync();

			$step->wasCalledWithOnce( [ Test::isInstanceOf( 'ELH_Status' ) ], 'set_status' );
=======
		 * it should catch exceptions thrown by the steps
		 */
		public function it_should_catch_exceptions_thrown_by_the_steps() {
			$step = Test::replace( 'ELH_SyncStepInterface::sync_or_throw', function () {
				throw new ELH_SyncException( 'Some error' );
			} );

			$this->sut->add_step( $step );

			$status = $this->sut->sync();

			Test::assertFalse( $status->did_sync );
			Test::assertEquals( 'Some error', $status->message );
>>>>>>> 825db8a79a04feb6f8c31fe4b2ef6b4f0657835d
		}

		/**
		 * @test
<<<<<<< HEAD
		 * it should capture sync exceptions and pass them to the strategy handler
		 */
		public function it_should_capture_sync_exceptions_and_pass_them_to_the_strategy_handler() {
			$strategy          = Test::replace( 'ELH_StrategyInterface' )->method( 'set' )->method( 'run' )->get();
			$strategy_selector = Test::replace( 'ELH_StrategySelectorInterface' )
			                         ->method( 'get_strategy_for', $strategy )->get();
			$step = Test::replace( 'ELH_StepInterface::run', function () {
				throw new ELH_SyncException();
			} );

			$sut = new ELH_Synchronizer();
			$sut->set_strategy_selector( $strategy_selector );
			$sut->set_first_step( $step );

			$sut->sync();

			$strategy_selector->wasCalledWithOnce( [
				Test::isInstanceOf( 'ELH_SyncException' ),
				Test::isInstanceOf( 'ELH_StatusInterface' ),
			], 'get_strategy_for' );
=======
		 * it should reschedule a single event to attempt another sync
		 */
		public function it_should_reschedule_a_single_event_to_attempt_another_sync() {
			$step = Test::replace( 'ELH_SyncStepInterface::sync_or_throw', function () {
				throw new ELH_SyncException( 'Some error' );
			} );

			$wp_schedule_single_event = Test::replace( 'wp_schedule_single_event' );

			$this->sut->add_step( $step );

			$status = $this->sut->sync();

			$wp_schedule_single_event->wasCalledOnce();
>>>>>>> 825db8a79a04feb6f8c31fe4b2ef6b4f0657835d
		}

		/**
		 * @test
<<<<<<< HEAD
		 * it should let the strategy selector handle the exception
		 */
		public function it_should_let_the_strategy_selector_handle_the_exception() {
			$strategy          = Test::replace( 'ELH_StrategyInterface' )->method( 'set' )->method( 'run' )->get();
			$strategy_selector = Test::replace( 'ELH_StrategySelectorInterface::get_strategy_for', function () use ( $strategy ) {
				return $strategy;
			} );
			$step = Test::replace( 'ELH_StepInterface::run', function () {
				throw new ELH_SyncException();
			} );

			$sut = new ELH_Synchronizer();
			$sut->set_strategy_selector( $strategy_selector );
			$sut->set_first_step( $step );

			$sut->sync();

			$strategy->wasCalledWithOnce( [ 'exception', Test::isInstanceOf( 'ELH_SyncException' ) ], 'set' );
			$strategy->wasCalledWithOnce( [ 'status', Test::isInstanceOf( 'ELH_StatusInterface' ) ], 'set' );
			$strategy->wasCalledOnce( 'run' );
		}

		protected function setUp() {
			Test::setUp();
		}

		protected function tearDown() {
			Test::tearDown();
=======
		 * it should not reschedule on failure if over attempts
		 */
		public function it_should_not_reschedule_on_failure_if_over_attempts() {
			$step = Test::replace( 'ELH_SyncStepInterface::sync_or_throw', function () {
				throw new ELH_SyncException( 'Some error' );
			} );
			Test::replace( 'get_option', 12 );

			$wp_schedule_single_event = Test::replace( 'wp_schedule_single_event' );

			$this->sut->add_step( $step );

			$wp_schedule_single_event->wasNotCalled();
>>>>>>> 825db8a79a04feb6f8c31fe4b2ef6b4f0657835d
		}

	}