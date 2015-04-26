<?php


class ELH_ShopListingsApiRequest implements ELH_ApiRequestInterface {

	private $parameters;

	public function __construct() {
		$this->parameters = array(
			new ELH_ApiRequestParameter( 'shop_id', true, false, 'string|int' ),
			new ELH_ApiRequestParameter( 'limit', false, 25, 'int' ),
			new ELH_ApiRequestParameter( 'offset', false, 0, 'int' ),
			new ELH_ApiRequestParameter( 'page', false, false, 'int' ),
			new ELH_ApiRequestParameter( 'keywords', false, false, 'string' ),
			new ELH_ApiRequestParameter( 'sort_on', false, 'created', '{created,price,score}' ),
			new ELH_ApiRequestParameter( 'sort_order', false, 'down', '{up,down}' ),
			new ELH_ApiRequestParameter( 'min_price', false, false, 'float' ),
			new ELH_ApiRequestParameter( 'max_price', false, false, 'float' ),
			new ELH_ApiRequestParameter( 'color', false, false, 'color' ),
			new ELH_ApiRequestParameter( 'color_accuracy', false, 0, '[0-30]' ),
			new ELH_ApiRequestParameter( 'tags', false, false, '[string]' ),
			new ELH_ApiRequestParameter( 'category', false, false, 'string' ),
			new ELH_ApiRequestParameter( 'translate_keywords', false, 0, 'bool' ),
			new ELH_ApiRequestParameter( 'include_private', false, 0, 'bool' ),
		);
	}

	/**
	 * @return string
	 */
	public function uri() {
		return '/users/:shop_id/listings/active';
	}

	/**
	 * @return string
	 */
	public function method() {
		return 'findAllShopListingsActive';
	}

	/**
	 * @return bool
	 */
	public function requires_OAuth() {
		return false;
	}

	/**
	 * @return string
	 */
	public function permission_scope() {
		return 'none';
	}

	public function http_method() {
		return 'GET';
	}

	public function parameters() {
		return $this->parameters;
	}
}