<?php

namespace yupdesign\CUA;

class ModuleProjectsList extends Module {
    
    /**
	 * Template
	 * @var string
	 */
    protected $strTemplate = 'mod_cua_project_list';

    protected function compile() {

        // JavaScript und CSS des RocksolidSliders einbinden!
        // (Wenn nicht installiert, schmeisst das Teil ja ne Fehlermeldung..)
        $rsAssetsDir = 'system/modules/rocksolid-slider/assets';
        //beide Standard-Skripte einbinden!        
        $GLOBALS['TL_JAVASCRIPT'][] = $rsAssetsDir . '/js/rocksolid-slider.min.js|static';
        $GLOBALS['TL_CSS'][] = $rsAssetsDir . '/css/rocksolid-slider.min.css||static';
        //Abfangen eines eigenen Themes
        $skinPath = $rsAssetsDir . '/css/' . (empty($this->arrData['rsts_skin']) ? 'default' : $this->arrData['rsts_skin']) . '-skin.min.css';
        if (file_exists(TL_ROOT . '/' . $skinPath)) {
            $GLOBALS['TL_CSS'][] = $skinPath . '||static';
        }

        // jetzt binden wir noch unserer eigenes JavaScript ein - damit liegt das nicht in der Seitenvorlage rum usw.$_COOKIE
        $cuaAssetsDir = 'system/modules/cua-projects/assets';
        $GLOBALS['TL_JAVASCRIPT'][] = $cuaAssetsDir . '/js/projectList.js|static';

        // Query bauen
        $query = 'SELECT * FROM tl_cuaprojects WHERE publish ="1"';

/*
        // falls Filter gesetzt sind, diese mit berücksichtigen
        if($_GET['kategorie'] != '') {
            $query .= ' AND category="' . $_GET['kategorie'] . '"';
        }
        if($_GET['status'] != '') {
            $query .= $_GET['status'] == 'wettbewerb' ? 'AND competition="1"' : ('AND status="' . $_GET['status'] . '"');
        } else {
            $query .= 'AND hide_in_all=""';
        }
*/
        $query .= ' ORDER BY date DESC';
       
        $rs = Database::getInstance()
            ->query($query);

        //ein leeres Array für alle Projekte, die wir nachfolgend aufbearbeiten
        $this->Template->projects = array();

         // merken, wieviele SPALTEN der "aktuellen" Zeile schon belegt sind
         $columnCount = 0;

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
                $fileModel = FilesModel::findByUuid($entry['main_img']);
                $size = array(459,260,crop);
                if ($entry['main_img_size'] !='') {
                    $size = array(945,260,crop);
                }
                $dataObject->thumbnail = Image::create($fileModel->path, $size)->executeResize()->getResizedPath();
             }

             //Anzahl der befüllten Spalten aktualisieren...
             if ($entry['main_img_size'] != '') {
                 $counter += 2;
                 $dataObject->css .= ' cua_project_wide';
             } else {
                 $counter += 1;
             }

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