<?php

namespace yupdesign\CUA;

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
    public function run($id)
    {

        return ['Hello World!'.$id];
    }
}
