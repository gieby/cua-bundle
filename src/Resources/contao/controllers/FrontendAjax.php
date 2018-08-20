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
    public function fetchProject($id)
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
        $objElements = \ContentModel::findPublishedByPidAndTable($id, 'tl_cuaprojects');
        if ($objElements !== null)
        {
            while ($objElements->next())
            {
                $dataObject->media .= \Controller::getContentElement($objElements->id);
            }

            //das ist nur ein Bandaid-Fix....
            $dataObject->media = json_decode(str_replace('TL_FILES_URL','',json_encode($dataObject->media)));
        }


        return $dataObject;
    }

    /**
     * Liefert alle Projekte in vereinfachter Form für die Übersicht zurück.
     * @return string
     */
    public function fetchAllProjects() {
        $rs = \Database::getInstance()->prepare('SELECT id, title, shortTitle, place, main_img, main_img_size FROM tl_cuaprojects WHERE publish ="1"  ORDER BY date DESC')->execute();
        $responseObject = $rs->fetchAllAssoc();

        $returnData = [];

        foreach ($responseObject as $k => $project) {
            $dataObject = (object)[];
            $dataObject->id = $project['id'];
            $dataObject->title = ($project['shortTitle'] != '') ? $project['shortTitle'] : $project['title'];
            $dataObject->place = $project['place'];

            if ($project['main_img'] != '') {
                $fileModel = \FilesModel::findByUuid($project['main_img']);
                $size = array(459,260,crop);
                if ($project['main_img_size'] !='') {
                    $size = array(945,260,crop);
                }
                $dataObject->thumbnail = \Image::create($fileModel->path, $size)->executeResize()->getResizedPath();
            }

            $returnData[] = $dataObject;
        }

        return $returnData;
    }

     /**
     * Run the controller
     *
     * @return string
     */
    public function fetchTour($id)
    {
        $rs = \Database::getInstance()->prepare("SELECT * FROM tl_cuatours WHERE id = ?")->execute($id);
        $responseObject = $rs->fetchAllAssoc()[0];

        //Antwort als Object zusammenbauen
        $dataObject = (object)[];

        //  Werte aus Response übernehmen
        $dataObject->title = $responseObject['title'];
        $dataObject->place = $responseObject['place'];
        $dataObject->description = $responseObject['description'];
        $dataObject->media = '';
        $dataObject->content = '
            <div class="contentBlock">
                <p class="tourTitle">' . $responseObject['title'] . '</p>
                <p class="tourPlace">' . $responseObject['place'] . '</p>
            </div>
            <div class="contentBlock tourDescription">
                ' . $responseObject['description'] . '
            </div>
            <div class="contentBlock tourInfos">';
        
        $closing = '</div>';
        
        $dataObject->content .= $closing;
                    
        //  Falls weitere Elemente hinzugefügt wurden, werden diese mit ausgegeben
        $objElements = \ContentModel::findPublishedByPidAndTable($id, 'tl_cuatours');
        if ($objElements !== null)
        {
            while ($objElements->next())
            {
                $dataObject->media .= \Controller::getContentElement($objElements->id);
            }

            $dataObject->media = json_decode(str_replace('TL_FILES_URL','',json_encode($dataObject->media)));
        }
            

        return $dataObject;
    }
}
