<?php

class ProjectListHooks {

    public function getParams($arrFragments) {

        if(count($arrFragments) > 1) {
        
            if($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item'])) {
            } else {

                if($arrFragments[0] == 'projekte' ) {
                    if($arrFragments[1] == 'auto_item') {
                        $arrFragments[1] = 'filter';
                    } else {
                        $frstFilter = $arrFragments[1];
                        $scndFilter = $arrFragments[2];
                        $arrFragments[1] = 'filter';
                        $arrFragments[2] = $frstFilter;
                        $arrFragments[3] = 'scnd-filter';
                        $arrFragments[4] = $scndFilter;
                    }
                }
            }
        }

        return $arrFragments;

    }
}