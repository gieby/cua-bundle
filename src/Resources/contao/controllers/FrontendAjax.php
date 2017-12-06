<?php

namespace yupdesign\CUABundle;

use Psr\Log\LogLevel;
use Contao\CoreBundle\Monolog\ContaoContext;


class FrontendAjax extends \Frontend
{
    /**
     * Initialize the object (do not remove)
     */
    public function __construct()
    {
        parent::__construct();

        // See #4099
        if (!defined('BE_USER_LOGGED_IN'))
        {
            define('BE_USER_LOGGED_IN', false);
        }
        if (!defined('FE_USER_LOGGED_IN'))
        {
            define('FE_USER_LOGGED_IN', false);
        }
    }

    /**
     * Run the controller
     *
     * @return string
     */
    public function run()
    {

        return ['Hello World!'];
    }
}
