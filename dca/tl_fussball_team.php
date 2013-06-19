<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2013 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    fussball_widget
 * @license    LGPL
 * @filesource
 */


$GLOBALS['TL_DCA']['tl_fussball_team'] = array(

// Config
'config' => array
(
	'dataContainer'               => 'Table',
	'closed'                      => false,
	'notEditable'                 => false,
    'sql' => array(
        'keys' => array('id' => 'primary')
    )
),
		

// List
'list' => array
(
	'sorting' => array
	(
		'mode'                    => 2,
		'fields'                  => array('name ASC'),
		'flag'                    => 1,
		'panelLayout'             => 'filter, search, limit'
	),
	'label' => array
	(
		'fields'                  => array('name', 'name_short', 'name_external', 'id_mannschaft', 'id_verein', 'lastUpdate'),
		'showColumns'             => true,
	),


	'operations' => array
	(
		'edit' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_fussball_team']['edit'],
			'href'                => 'act=edit',
			'icon'                => 'edit.gif',
			'attributes'          => 'class="contextmenu"'
		),
		'delete' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_fussball_team']['delete'],
			'href'                => 'act=delete',
			'icon'                => 'delete.gif',
			'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_fussball_team']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
		)
	)

),


// Palettes
'palettes' => array
(
	'default'                     => '{title_legend},name,name_short,id_mannschaft,id_verein,name_external'
),

// Fields
'fields' => array
(
	'id' => array
	(
			'label'                   => array('ID'),
			'search'                  => false,
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
	),
    'tstamp' => array
    (
        'label'                   => array('TSTAMP'),
        'search'                  => false,
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
    ),
    'name' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['name'],
        'exclude'                 => true,
        'search'                  => true,
        'sorting'                 => true,
        'flag'                    => 1,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'name_short' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['name_short'],
        'exclude'                 => true,
        'search'                  => true,
        'sorting'                 => true,
        'flag'                    => 1,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'id_mannschaft' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['id_mannschaft'],
			'exclude'                 => true,
			'search'                  => false,
			'sorting'                 => false,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''",
	),
	'id_verein' => array(
			'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['id_verein'],		
			'exclude'                 => true,
			'search'                  => false,
			'sorting'                 => false,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''",
	),
    'name_external' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['name_external'],
        'exclude'                 => true,
        'search'                  => true,
        'sorting'                 => true,
        'flag'                    => 1,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'lastUpdate' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['lastUpdate'],
        'flag'                    => 8,
        'search'                  => false,
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
    )


) //fields

);

class tl_fussball_team extends Backend {

	public function __construct() {
		parent::__construct();
		$this->import('BackendUser', 'User');		
	}

	public function labelCallback($row, $label, DataContainer $dc, $args = null) {
		if ($args === null) {
			return $label;
		}
		return $args;
	}
}





