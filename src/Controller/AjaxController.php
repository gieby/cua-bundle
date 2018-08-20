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
     * @Route("/ajax/{cat}/{id}", name="ajax_frontend", defaults={"_scope" = "frontend", "_token_check" = false})
     */
    public function ajaxAction($cat='project',$id='')
    {

        $this->container->get('contao.framework')->initialize();

        $controller = new \yupdesign\CUA\FrontendAjax();

        if($cat == 'project') {
            if($id == 'list') {
                $data = $controller->fetchAllProjects();
            } else {
                $data = $controller->fetchProject($id);
            }
        } else {
            $data = $controller->fetchTour($id);
        }

        $response = new JsonResponse($data);
        $response->send();
    }
}