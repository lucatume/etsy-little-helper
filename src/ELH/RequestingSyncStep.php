<?php


abstract class ELH_RequestingSyncStep extends ELH_AbstractSyncStep implements ELH_ApiRequestClientInterface {

	/**
	 * @var ELH_ApiRequestInterface
	 */
	protected $request;

	/**
	 * @var ELH_RequestCompilerInterface
	 */
	protected $request_compiler;

	public function set_request( ELH_ApiRequestInterface $request ) {
		$this->request = $request;
	}

	public function set_request_compiler( ELH_RequestCompilerInterface $request_compiler ) {
		$this->request_compiler = $request_compiler;
	}

	/**
	 * @param $url
	 *
	 * @return array|mixed|WP_Error
	 * @throws ELH_SyncException
	 */
	protected function get_response( $url ) {
		$data = wp_remote_get( $url );

		$data = $this->ensure_response( $data );

		return $data;
	}

	/**
	 * @param $data
	 */
	protected function maybe_call_next( $data ) {
		if ( isset( $this->next ) && isset( $this->status ) ) {
			$this->status->set( 'data', $data );
			$this->next->set_status( $this->status );
			$this->next->run();
		}
	}

	public function run() {
		$this->ensure_requirements();

		$url = $this->get_request_url();

		$data = $this->get_response( $url );

		$this->maybe_call_next( $data );
	}

	protected function ensure_requirements() {
		if ( ! isset( $this->keychain ) ) {
			throw new ELH_MissingStepRequirement( 'Keychain parameter is not set' );
		}
		if ( ! isset( $this->api ) ) {
			throw new ELH_MissingStepRequirement( 'Api parameter is not set' );
		}
		if ( ! isset( $this->request ) ) {
			throw new ELH_MissingStepRequirement( 'Request parameter is not set' );
		}
		if ( ! isset( $this->request_compiler ) ) {
			throw new ELH_MissingStepRequirement( 'Request compiler parameter is not set' );
		}
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 * @throws ELH_SyncException
	 */
	protected function ensure_response( $data ) {
		if ( empty( $data ) ) {
			throw new ELH_SyncException( 'Server returned empty data.' );
		}
		if ( ! isset( $data['response'] ) && ! isset( $data['response']['code'] ) ) {
			throw new ELH_SyncException( 'Server returned non coherent data' );
		}

		$this->ensure_request_specifics( $data );

		return $data;
	}
}