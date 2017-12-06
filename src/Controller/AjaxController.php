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
     * @Route("/ajax/project/{id}", name="ajax_frontend", defaults={"_scope" = "frontend", "_token_check" = false})
     */
    public function ajaxProject($id='')
    {

        $this->container->get('contao.framework')->initialize();

        $controller = new \yupdesign\CUA\FrontendAjax();

        $data = $controller->fetchProject($id);

        $response = new JsonResponse($data);
        $response->send();
    }

    /**
    * Handles ajax requests.
    *
    * @return JsonResponse
    *
    * @Route("/ajax/tour/{id}", name="ajax_frontend", defaults={"_scope" = "frontend", "_token_check" = false})
    */
   public function ajaxTour($id='')
   {

       $this->container->get('contao.framework')->initialize();

       $controller = new \yupdesign\CUA\FrontendAjax();

       $data = $controller->fetchTour($id);

       $response = new JsonResponse($data);
       $response->send();
   }

}