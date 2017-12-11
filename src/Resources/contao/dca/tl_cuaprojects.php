<?php

if (TL_MODE === 'BE') {
	$GLOBALS['TL_CSS'][] = 'bundles/yupdesigncua/css/backend.css';
}

/**
 * Table tl_cuaprojects
 */
$GLOBALS['TL_DCA']['tl_cuaprojects'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'ctable'                      => array('tl_content'),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'date' => 'index',
				'title' => 'index'
			)
		),
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('date'),
			'panelLayout'             => 'filter;sort,search,limit',
			'disableGrouping'		  => true
		),
		'label' => array
		(
			'fields'                  => array('shortHandle', 'title'),
			'format'                  => '%s | %s',
			'label_callback'          => array('tl_cuaprojects', 'titleImageForLabel')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cuaprojects']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'edit_content' => array
 			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cuaprojects']['edit_content'],
				'href'                => 'table=tl_content',
				'icon'                => 'article.gif'
			 ),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cuaprojects']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cuaprojects']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cuaprojects']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif',
				'attributes'          => 'style="margin-right:3px"'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{title_legend},title,shortHandle,shortTitle,place,date,url;{display_legend},status,category,hide_in_all;{data_legend},task,principal,surface,cost,year_comp,year_build;{project_legend},description,main_img,main_img_size;publish'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['date'],
			'default'                 => time(),
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'				  => true,
			'flag'					  => 10,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard clr'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['title'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'unique'=>false, 'maxlength'=>128, 'tl_class'=>'long'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'shortTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['shortTitle'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => false,
			'flag'                    => 1,
			'search'                  => true,
			'eval'                    => array('unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'shortHandle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['shortHandle'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'search'                  => true,
			'eval'                    => array('unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(5) NOT NULL default ''"
		),
		'place' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['place'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => false,
			'flag'                    => 1,
			'search'                  => false,
			'eval'                    => array('mandatory'=>false, 'unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'principal' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['principal'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => false,
			'flag'                    => 1,
			'search'                  => false,
			'eval'                    => array('mandatory'=>false, 'unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'competition' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_cuaprojects']['competition'],
			'exclude'					=> true,
			'inputType'					=> 'checkbox',
			'eval'						=> array('isBoolean'=>true,'tl_class'=>'w50 m12 clr'),
			'sql'						=> "char(1) NOT NULL default ''"
		),
		'hide_in_all' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_cuaprojects']['hide_in_all'],
			'exclude'					=> true,
			'inputType'					=> 'checkbox',
			'eval'						=> array('isBoolean'=>true,'tl_class'=>'long clr'),
			'sql'						=> "char(1) NOT NULL default ''"
		),
		'status' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['status'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'				  => 'checkbox',
			'reference'				  => &$GLOBALS['TL_LANG']['tl_cuaprojects']['status_reference'],
			'options'				  => array('wettbewerb', 'in-planung','im-bau','fertiggestellt'),
			'eval'					  => array('multiple'=>true,'tl_class'=>'w50'),
			'sql'					  => "varchar(255) NOT NULL default ''"
		),
		'status_text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['status_text'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => false,
			'search'                  => false,
			'eval'                    => array('mandatory'=>false, 'unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'category' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['category'],
			'exclude'                 => true,
			'sorting'                 => false,
			'search'                  => false,
			'inputType'				  => 'checkbox',
			'reference'				  => &$GLOBALS['TL_LANG']['tl_cuaprojects']['category_reference'],
			'options'				  => array('bildung', 'kultur','gewerbe','sozialbau'),
			'eval'					  => array('multiple'=>true,'tl_class'=>'w50'),
			'sql'					  => "varchar(255) NOT NULL default ''"
		),
		'task' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['task'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => false,
			'flag'                    => 1,
			'search'                  => false,
			'eval'                    => array('mandatory'=>false, 'unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'cost' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['cost'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => false,
			'flag'                    => 1,
			'search'                  => false,
			'eval'                    => array('mandatory'=>false, 'unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'surface' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['surface'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => false,
			'flag'                    => 1,
			'search'                  => false,
			'eval'                    => array('mandatory'=>false, 'unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'year_comp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['year_comp'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => false,
			'flag'                    => 1,
			'search'                  => false,
			'eval'                    => array('mandatory'=>false, 'unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'year_build' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['year_build'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => false,
			'flag'                    => 1,
			'search'                  => false,
			'eval'                    => array('mandatory'=>false, 'unique'=>false, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
        'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['description'],
			'inputType'               => 'textarea',
			'exclude'                 => true,
			'search'                  => false,
			'eval'                    => array('mandatory'=>false, 'rte'=>'tinyMCE', 'helpwizard'=>true, 'tl_class' => 'clr'),
			'sql'                     => "mediumtext NULL"
		),
		'main_img' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['main_img'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('filesOnly'=>true, 'fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>' w50 clr'),
			'sql'                     => "binary(16) NULL"
		),
		'main_img_size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['main_img_size'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cuaprojects']['url'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'foreignKey'              => 'tl_page.title',
			'eval'                    => array('mandatory'=>false, 'fieldType'=>'radio', 'tl_class' => 'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'publish' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_cuaprojects']['publish'],
			'exclude'					=> true,
			'inputType'					=> 'checkbox',
			'eval'						=> array('isBoolean'=>true,'tl_class'=>'w50 m12 clr'),
			'sql'						=> "char(1) NOT NULL default ''"
		)
	)
);

class tl_cuaprojects extends Backend {
	public function titleImageForLabel($row, $label)
	{
		if ($row['main_img'] != '')
		{
			$objFile = FilesModel::findByUuid($row['main_img']);
			if ($objFile !== null)
			{
				if($row['main_img_size'] != '') {
					$label = Image::getHtml(Image::get($objFile->path, 170, 60, 'crop'), '', 'class="theme_preview"') . $label;
				} else {
					$label = Image::getHtml(Image::get($objFile->path, 80, 60, 'crop'), '', 'class="theme_preview"') . $label;
				}
			}
		}
		return $label;
	}
}