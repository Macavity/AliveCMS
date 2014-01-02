<?php

/**
 * Class MY_Controller
 *
 * @property    CI_Config   $config
 * @property    CI_Loader   $load
 *
 * @property    Template    $template
 * @property    Realms      $realms
 * @property    User        $user
 */
class MY_Controller extends MX_Controller
{
    protected $CI;

    public function __construct()
    {
        parent::__construct();

        $this->CI = &get_instance();
    }

}