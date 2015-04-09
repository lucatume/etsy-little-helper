<?php

abstract class ELH_AbstractSyncStep implements ELH_ApIUserInterface, ELH_KeychainUserInterface, ELH_SyncStepInterface
{

    /**
     * @var ELH_KeychainInterface
     */
    protected $keychain;
    /**
     * @var ELH_ApiInterface
     */
    protected $api;

    /**
     * @var ELH_SyncStatusInterface
     */
    protected $status;

    /**
     * @var ELH_SyncStepInterface
     */
    private $next;

    public function set_api(ELH_ApiInterface $api)
    {
        $this->api = $api;
    }

    public function set_keychain(ELH_KeychainInterface $keychain)
    {
        $this->keychain = $keychain;
    }

    public function set_next(ELH_SyncStepInterface $next)
    {
        $this->next = $next;
    }

    abstract public function go();

   public function set_status(ELH_StatusInterface $status){
       $this->status = $status;
   }
}