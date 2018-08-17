<?php
namespace yupdesign\CUABundle\Module;

class ModuleProjectsListVue extends \Module {
    
    /**
	 * Template
	 * @var string
	 */
    protected $strTemplate = 'mod_cua_project_list_vue';

    protected function compile() {

        // JavaScript und CSS des RocksolidSliders einbinden!
        // (Wenn nicht installiert, schmeisst das Teil ja ne Fehlermeldung..)
        $rsAssetsDir = 'web/bundles/rocksolidslider';
        //beide Standard-Skripte einbinden!        
        $GLOBALS['TL_JAVASCRIPT'][] = $rsAssetsDir . '/js/rocksolid-slider.min.js|static';
        $GLOBALS['TL_CSS'][] = $rsAssetsDir . '/css/rocksolid-slider.min.css||static';
        //Abfangen eines eigenen Themes
        $skinPath = $rsAssetsDir . '/css/' . (empty($this->arrData['rsts_skin']) ? 'default' : $this->arrData['rsts_skin']) . '-skin.min.css';
        if (file_exists(TL_ROOT . '/' . $skinPath)) {
            $GLOBALS['TL_CSS'][] = $skinPath . '||static';
        }

        // jetzt binden wir noch unserer eigenes JavaScript ein - damit liegt das nicht in der Seitenvorlage rum usw.$_COOKIE
        $cuaAssetsDir = 'web/bundles/yupdesigncua';
        $GLOBALS['TL_JAVASCRIPT'][] = $cuaAssetsDir . '/js/projectListVue.js|static';
        $GLOBALS['TL_CSS'][] = $cuaAssetsDir . 'css/vue.css|static';

        // Query bauen
        $query = 'SELECT id, title, shortTitle, place, main_img, main_img_size FROM tl_cuaprojects WHERE publish ="1"  ORDER BY date DESC';
       
        $rs = \Database::getInstance()
            ->query($query);

        //ein leeres Array für alle Projekte, die wir nachfolgend aufbearbeiten
        $this->Template->projects = array();

         // merken, wieviele SPALTEN der "aktuellen" Zeile schon belegt sind
         $columnCount = 0;
         
         // Anzahl der belegten Slots zur Bestimmung der Reihe
         $rowHelper = 1;

         // falls zwei lange Elemente aufeinander folgen, müssen wir eines vorhalten
         // ggf mehrere?! -> als Array[(object)] halten und immer das Element poppen?
         $onHold = null;

         // alle Projekte in einem Array sammeln, durch das das Template laufen kann
         $projects = array();

         foreach ($rs->fetchAllAssoc() as $entry) {
            // Einträge nachbehandeln um Filtern gerecht zu werden
            if( $_GET['status'] != '' && !in_array($_GET['status'], deserialize($entry['status'])) ) {
                continue;
            }

            if( $_GET['kategorie'] != '' && !in_array($_GET['kategorie'], deserialize($entry['category']))) {
                continue;
            }

            if( $_GET['status'] != 'wettbewerb' && $entry['hide_in_all'] == '1') { //&& $_GET['kategorie'] == '' 
                continue;
            }
            
             // falls wir eine Referenz noch auf onHold haben, wird diese am Anfang eingefügt!
             if ($counter <= 1 && $onHold != null) {
                 $projects[] = $onHold;
                 $onHold = null; 
                 $counter += 2;
             }

             //Eintrag als Object erstellen
             $dataObject = (object)array();

             //Basisinfos (Titel & Ort mit ausgeben)
             $dataObject->id = $entry['id'];
             $dataObject->title = $entry['title'];
             if ($entry['shortTitle'] != '') {
                 $dataObject->title = $entry['shortTitle'];
             }
             $dataObject->place = $entry['place'];
             $dataObject->css = '';
             $dataObject->thumbnail = '';

             //Thumbnail generieren und Asset-URL anhängen
             if ($entry['main_img'] != '') {
                $fileModel = \FilesModel::findByUuid($entry['main_img']);
                $size = array(459,260,crop);
                if ($entry['main_img_size'] !='') {
                    $size = array(945,260,crop);
                }
                $dataObject->thumbnail = \Image::create($fileModel->path, $size)->executeResize()->getResizedPath();
             }

             //Anzahl der befüllten Spalten aktualisieren...
             if ($entry['main_img_size'] != '') {
                $counter += 2;
                $rowHelper += 2;
                $dataObject->css .= ' cua_project_wide';
             } else {
                $counter += 1;
                $rowHelper += 1;
             }

             //Zeile hinzufügen
             $dataObject->row=floor($rowHelper / 3);

             // ... und entsprechend verfahren
             if ($counter > 3) { // zwei breite nebeneinander!
                 $onHold = $dataObject;
                 $counter -= 2;
             } elseif ($counter == 3) { // Ende der Zeile
                 $dataObject->css .= ' cua_project_clear';
                 $counter = 0;
                 $projects[] = $dataObject;
             } else { // kein Sonderfall
                 $projects[] = $dataObject;
             }
              
         }

         // falls wir durch sind und noch ein Objekt auf onHold haben, wird dieses am Ende noch eingefügt
         if ($onHold != null) {
             $projects[] = $onHold;
         }

         $this->Template->projects = $projects;
    }
}