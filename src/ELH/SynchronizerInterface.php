<?php
/**
 * Created by PhpStorm.
 * User: Luca
 * Date: 24/03/15
 * Time: 17:48
 */

interface ELH_SynchronizerInterface {

	public function set_first_step( ELH_StepInterface $step );

	public function sync();

	public function set_strategy_selector( ELH_StrategySelectorInterface $strategy_selector );
}