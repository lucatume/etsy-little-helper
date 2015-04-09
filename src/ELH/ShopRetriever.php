<?php


class ELH_ShopRetriever extends ELH_AbstractSyncStep
{

    public static function instance(ELH_KeychainInterface $keychain, ELH_ApiInterface $api)
    {
        $instance = new self();
        $instance->keychain = $keychain;
        $instance->api = $api;
    }

    public function go()
    {
        // TODO: Implement go() method.
    }

}