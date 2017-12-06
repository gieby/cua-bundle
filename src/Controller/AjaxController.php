<?php

namespace yupdesign\CUABundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use yupdesign\CUA\FrontendAjax;

class AjaxController extends Controller
{
    /**
     * Handles ajax requests.
     *
     * @return JsonResponse
     *
     * @Route("/ajax/{test}", name="ajax_frontend", defaults={"_scope" = "frontend", "_token_check" = false})
     */
    public function ajaxAction($test = '')
    {

        $this->container->get('contao.framework')->initialize();

        $controller = new FrontendAjax();

        $data = $controller->run();

        $response = new JsonResponse(array('result' => 'success','token' => $test, 'data' => $data));
        $response->send();
    }

}