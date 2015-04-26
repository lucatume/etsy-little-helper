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


}
