<?php
/**
 * @copyright  Stephan Gieb 2017 <http://yupdesign.de>
 * @author     Stephan Gieb
 * @package    Demo
 * @license    LGPL-3.0+
 *
 */
/**
 * Add back end modules
 */

$GLOBALS['BE_MOD']['content']['cua_projects'] = array (
	'tables'   =>  array('tl_cuaprojects'),
	'icon'     =>  'bundles/yupdesigncua/images/backend_icon.png',
	'tables'    =>  array('tl_cuaprojects', 'tl_content')
);

/**
*  Frontend
*/
array_insert($GLOBALS['FE_MOD'], 2, array(
	'miscellaneous' => array(
		'cua_project_list'   =>  'yupdesign\\CUABundle\\Module\\ModuleProjectsList',
		'cua_project_list_filter'   =>  'yupdesign\\CUABundle\\Module\\ProjectListFilter'
	),
	'cua_ajax' => array
	(
			'ajax'    => 'yupdesign\CUA\ModuleAjax'
	)
));