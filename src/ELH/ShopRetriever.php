<?php


class ELH_ShopRetriever extends ELH_RequestingSyncStep {

	public static function instance( ELH_KeychainInterface $keychain, ELH_ApiInterface $api, ELH_ApiRequestInterface $request, ELH_RequestCompilerInterface $request_compiler ) {
		$instance                   = new self();
		$instance->keychain         = $keychain;
		$instance->api              = $api;
		$instance->request          = $request;
		$instance->request_compiler = $request_compiler;

		return $instance;
	}

	/**
	 * @param $data
	 *
	 * @throws ELH_SyncException
	 */
	protected function ensure_request_specifics( $data ) {
		if ( $data['response']['code'] != '200' ) {
			$message = sprintf( 'Shop retrieving failed with code %d and message "%s"', $data['response']['code'], $data['response']['message'] );
			throw new ELH_SyncException( $message );
		}
	}

	/**
	 * @return array
	 */
	protected function get_request_data() {
		$data = array(
			'user_id' => get_option( ELH_Main::USER_ID_OPTION ),
			'api_key' => $this->api->get_api_key()
		);

		return $data;
	}

}
