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
 * @property    CI_Output               $output
 *
 * @property    Administrator           $administrator
 * @property    Cache                   $cache
 * @property    External_account_model  $external_account_model
 * @property    Realms                  $realms
 * @property    Template                $template
 * @property    User                    $user
 * @property    Logger                  $logger
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