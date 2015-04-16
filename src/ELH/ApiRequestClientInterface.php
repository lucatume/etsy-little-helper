<?php

interface ELH_ApiRequestClientInterface {

	public function set_request( ELH_ApiRequestInterface $request );

	public function set_request_compiler( ELH_RequestCompilerInterface $request_compiler );
}