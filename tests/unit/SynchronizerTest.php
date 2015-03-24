<?php


	use tad\FunctionMocker\FunctionMocker as Test;

	class SynchronizerTest extends \PHPUnit_Framework_TestCase {

		/**
		 * @var ELH_Synchronizer
		 */
		protected $sut;

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

		/**
		 * @test
		 * it should run the first step
		 */
		public function it_should_run_the_first_step() {
			$step = Test::replace( 'ELH_SyncStepInterface::sync_or_throw' );
			$this->sut->add_step( $step );

			$this->sut->sync();

			$step->wasCalledOnce( 'sync_or_throw' );
		}

		/**
		 * @test
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
		}

		/**
		 * @test
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
		}

		/**
		 * @test
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
		}

	}