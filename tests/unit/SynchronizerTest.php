<?php


use tad\FunctionMocker\FunctionMocker as Test;

class SynchronizerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ELH_Synchronizer
     */
    protected $sut;

    protected function setUp()
    {
        Test::setUp();
    }

    protected function tearDown()
    {
        Test::tearDown();
    }

    /**
     * @test
     * it should run the first step
     */
    public function it_should_run_the_first_step()
    {
        $step = Test::replace('ELH_SyncStepInterface::go');
        $sut = new ELH_Synchronizer();
        $sut->set_first_step($step);

        $sut->sync();

        $step->wasCalledOnce('go');
    }

    /**
     * @test
     * it should set the status on the first step
     */
    public function it_should_set_the_status_on_the_first_step()
    {
        $step = Test::replace('ELH_SyncStepInterface::set_status');
        $sut = new ELH_Synchronizer();
        $sut->set_first_step($step);

        $sut->sync();

        $step->wasCalledWithOnce([Test::isInstanceOf('ELH_Status')], 'set_status');
    }

    /**
     * @test
     * it should capture sync exceptions and pass them to the strategy handler
     */
    public function it_should_capture_sync_exceptions_and_pass_them_to_the_strategy_handler()
    {
        $strategy_selector = Test::replace('ELH_StrategySelectorInterface::get_strategy_for');
        $step = Test::replace('ELH_SyncStepInterface::go', function () {
            throw new ELH_SyncException();
        });

        $sut = new ELH_Synchronizer();
        $sut->set_strategy_selector($strategy_selector);
        $sut->set_first_step($step);

        $sut->sync();

        $strategy_selector->wasCalledWithOnce([
            Test::isInstanceOf('ELH_SyncException'),
            Test::isInstanceOf('ELH_StatusInterface'),
        ], 'get_strategy_for');
    }

    /**
     * @test
     * it should let the strategy selector handle the exception
     */
    public function it_should_let_the_strategy_selector_handle_the_exception()
    {
        $strategy = Test::replace('ELH_StrategyInterface::run');
        $strategy_selector = Test::replace('ELH_StrategySelectorInterface::get_strategy_for', function () use ($strategy) {
            return $strategy;
        });
        $step = Test::replace('ELH_SyncStepInterface::go', function () {
            throw new ELH_SyncException();
        });

        $sut = new ELH_Synchronizer();
        $sut->set_strategy_selector($strategy_selector);
        $sut->set_first_step($step);

        $sut->sync();

        $strategy->wasCalledWithOnce(['exception', Test::isInstanceOf('ELH_SyncException')], 'set');
        $strategy->wasCalledWithOnce(['status', Test::isInstanceOf('ELH_StatusInterface')], 'set');
        $strategy->wasCalledOnce('run');
    }

}