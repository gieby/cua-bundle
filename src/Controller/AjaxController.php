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
     * @Route("/ajax/{id}", name="ajax_frontend", defaults={"_scope" = "frontend", "_token_check" = false})
     */
    public function ajaxAction($id='')
    {

        $this->container->get('contao.framework')->initialize();

        $controller = new \yupdesign\CUA\FrontendAjax();

        $data = $controller->run($id);

        $response = new JsonResponse(array('result' => 'success', 'data' => $data));
        $response->send();
    }

}