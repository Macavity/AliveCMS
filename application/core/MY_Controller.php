<?php

/**
 * Class MY_Controller
 *
 * @property    CI_Config               $config
 * @property    CI_Loader               $load
 * @property    CI_URI                  $uri
 * @property    CI_Input                $input
 * @property    CI_Smarty               $smarty
 * @property    CI_DB_active_record     $db
 *
 * @property    Cache       $cache
 * @property    Template    $template
 * @property    Realms      $realms
 * @property    User        $user
 * @property    Logger      $logger
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