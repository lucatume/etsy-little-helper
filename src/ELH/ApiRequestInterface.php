<?php

interface ELH_ApiRequestInterface
{
    /**
     * @return string
     */
    public function uri();

    /**
     * @return string
     */
    public function method();

    /**
     * @return bool
     */
    public function requires_OAuth();

    /**
     * @return string
     */
    public function permission_scope();

    /**
     * @return string
     */
    public function http_method();

    /**
     * @return ELH_ApiRequestParameter[]
     */
    public function parameters();
}