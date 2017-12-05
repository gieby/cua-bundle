<?php

if (\Input::get('do') == 'cua_projects')
 {
     $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_cuaprojects';
 }