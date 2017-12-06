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
        $rs = \Database::getInstance()->prepare("SELECT * FROM tl_cuaprojects WHERE id = ?")->execute($id);
        $responseObject = $rs->fetchAllAssoc()[0];

        //Antwort als Object zusammenbauen
        $dataObject = (object)[];

        //  Werte aus Response übernehmen
        $dataObject->title = $responseObject['title'];
        $dataObject->place = $responseObject['place'];
        $dataObject->principal = $responseObject['principal'];
        /*
        switch($responseObject['status']) {
            case 'wettbewerb':
                $dataObject->status = 'Wettbewerb';
                break;
            case 'in-planung':
                $dataObject->status = 'In Planung';
                break;
            case 'im-bau':
                $dataObject->status = 'Im Bau';
                break;
            case 'fertiggestellt':
                $dataObject->status = 'Fertiggestellt';
                break;
        }
        */
        $dataObject->status = $responseObject['status_text'];
        $dataObject->task = $responseObject['task'];
        $dataObject->year_comp = $responseObject['year_comp'];
        $dataObject->year_build = $responseObject['year_build'];
        $dataObject->description = $responseObject['description'];
        $dataObject->cost = $responseObject['cost'];
        $dataObject->url = $responseObject['url'];
        $dataObject->media = '';
        $dataObject->content = '
            <div class="contentBlock">
                <p class="projectTitle">' . $responseObject['title'] . '</p>
                <p class="projectPlace">' . $responseObject['place'] . '</p>
            </div>
            <div class="contentBlock projectDescription">
                ' . $responseObject['description'] . '
            </div>
            <div class="contentBlock projectInfos">';
        
        if(!empty($responseObject['principal'])) {
            $dataObject->content .= '<div class="infoBlock">
                                        <p>Bauherr</p>
                                        <p>' . $responseObject['principal'] . '</p>
                                    </div>';
        }

        if(!empty($responseObject['year_build'])) {
            $dataObject->content .= '<div class="infoBlock">
                                        <p>Fertigstellung</p>
                                        <p>' . $responseObject['year_build'] . '</p>
                                    </div>';
        }

        if(!empty($responseObject['year_comp'])) {
            $dataObject->content .= '<div class="infoBlock">
                                        <p>Wettbewerb</p>
                                        <p>' . $responseObject['year_comp'] . '</p>
                                    </div>';
        }

        if(!empty($responseObject['surface'])) {
            $dataObject->content .= '<div class="infoBlock">
                                        <p>Fläche BGF</p>
                                        <p>' . $responseObject['surface'] . '&nbsp;m&#178;</p>
                                    </div>';
        }

        /*  (07.04.2017) Status wird im Frontend nicht mehr ausgegeben

        if(!empty($responseObject['status_text'])) {
            $dataObject->content .= '<div class="infoBlock">
                                        <p>Status</p>
                                        <p>' . $dataObject->status . '</p>
                                    </div>';
        }

        */

        if(!empty($responseObject['task'])) {
            $dataObject->content .= '<div class="infoBlock">
                                        <p>Aufgabe</p>
                                        <p>' . $responseObject['task'] . '</p>
                                    </div>';
        }

        if(!empty($responseObject['cost'])) {
            $dataObject->content .= '<div class="infoBlock">
                                        <p>Kosten</p>
                                        <p>' . $responseObject['cost'] . '&nbsp;Mio&nbsp;€</p>
                                    </div>';
        }
        if($responseObject['url'] != 0) {
            $closing = '
            <div class="moreBlock">
                <a title="' . \PageModel::findById($responseObject['url'])->loadDetails()->title . '" href="'. \PageModel::findById($responseObject['url'])->getFrontendUrl().'">Zur Projektseite</a>
            </div>
        </div>
        ';
        } else {
            $closing = '</div>';
        }
        $dataObject->content .= $closing;
                    
        //  Falls weitere Elemente hinzugefügt wurden, werden diese mit ausgegeben
        $objElements = \ContentModel::findPublishedByPidAndTable($this->Input->get('projectId'), 'tl_cuaprojects');
        if ($objElements !== null)
        {
            while ($objElements->next())
            {
                $dataObject->media .= \Controller::getContentElement($objElements->id);
            }
        }


        return $dataObject;
    }
}
