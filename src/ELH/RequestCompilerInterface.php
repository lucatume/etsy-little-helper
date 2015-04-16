<?php


	interface ELH_RequestCompilerInterface {

		public function set_request( ELH_ApiRequestInterface $request );

		public function get_compiled_request( array $data = array() );
	}