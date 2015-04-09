<?php

class ELH_ShopListingsApiRequest implements ELH_ApiRequestInterface
{

    private $parameters;

    public function __construct()
    {
        $this->parameters = array(
            new ELH_ApiRequestParameter('user_id', true, false, 'string|int'),
            new ELH_ApiRequestParameter('limit', false, 25, 'int'),
            new ELH_ApiRequestParameter('offset', false, false, 'int'),
            new ELH_ApiRequestParameter('page', false, false, 'int'),
        );
    }

    /**
     * @return string
     */
    public function uri()
    {
        return '/users/:user_id/shops';
    }

    /**
     * @return string
     */
    public function method()
    {
        return 'findAllShopListingsActive';
    }

    /**
     * @return bool
     */
    public function requires_OAuth()
    {
        return false;
    }

    /**
     * @return string
     */
    public function permission_scope()
    {
        return 'none';
    }

    public function http_method()
    {
        return 'GET';
    }

    public function parameters()
    {
        return $this->parameters;
    }
}