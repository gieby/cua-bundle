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
        $GLOBALS['TL_JAVASCRIPT'][] = $cuaAssetsDir . '/js/vue.js|static';
        $GLOBALS['TL_CSS'][] = $cuaAssetsDir . '/css/vue.css|static';

        /**
         * Das Modul stellt lediglich die Styles und Logic bereit.
         * 
         * Vue.js fragt zur Laufzeit per AJAX alle Projekte ab und baut diese beim Nutzer zusammen.
         * Erlaubt es uns die Projektseite schneller auszuliefern.
         */
    }
}