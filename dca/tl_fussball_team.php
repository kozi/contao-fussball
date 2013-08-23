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
		'fields'                  => array('bgcolor', 'name', 'team_attributes', 'lastUpdate'),
		'showColumns'             => true,
        'label_callback'          => array('tl_fussball_team', 'labelCallback')
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
	'default'                     => '{title_legend},name,name_short,name_external,alias,bgcolor;{team_attr_legend},team_attributes;{spielplan_legend},action_url,team_id'
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
    'alias' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['alias'],
        'exclude'                 => true,
        'search'                  => true,
        'sorting'                 => true,
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
        'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'bgcolor' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['bgcolor'],
        'exclude'                 => true,
        'search'                  => true,
        'sorting'                 => true,
        'flag'                    => 1,
        'inputType'               => 'text',
        'eval'                    => array('maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
        'sql'                     => "varchar(64) NOT NULL default ''",
    ),

    'team_attributes' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['team_attributes'],
        'exclude'                 => true,
        'search'                  => false,
        'sorting'                 => false,
        'inputType'		          => 'multiColumnWizard',
        'eval'			          => array('mandatory'=>false, 'allowHtml' => true, 'columnsCallback'=>array('tl_fussball_team', 'teamAttributes')),
        'sql'                     => "blob NULL",
    ),

    'action_url' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['action_url'],
        'exclude'                 => true,
        'search'                  => true,
        'sorting'                 => true,
        'flag'                    => 1,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class' => 'long'),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'team_id' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['team_id'],
        'exclude'                 => true,
        'search'                  => true,
        'sorting'                 => true,
        'flag'                    => 1,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class' => 'long'),
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
    private $tmplBgcolor = '<span class="fussball_bgcolor" style="background-color:#%s;">&nbsp;</span>';
    private $tmplTeamAttribute = '<strong>%s</strong>: %s<br>';

	public function __construct() {
		parent::__construct();
		$this->import('BackendUser', 'User');		
	}

    public function labelCallback($row, $label, DataContainer $dc, $args = null) {
		if ($args === null) {
			return $label;
		}

        $bgcolor = unserialize($row['bgcolor']);
        $args[0] = sprintf($this->tmplBgcolor, $bgcolor[0]);
        $args[1] = implode('<br>', array(
            $row['name'],
            'Abkürzung: '.$row['name_short'],
            'Alias: '.$row['alias']
        ));

        $args[2] = '';
        $team_attributes = unserialize($row['team_attributes']);
        foreach ($team_attributes as $attribute) {
            $args[2] .= sprintf($this->tmplTeamAttribute, $attribute['fussball_ta_key'], $attribute['fussball_ta_value']);
        }
		return $args;
	}

    public function teamAttributes() {
        return array
        (
            'fussball_ta_key' => array
            (
                'label'                 => 'Attribut',
                'inputType'             => 'select',
                'options'            	=> $GLOBALS['fussball_widget']['team_attributes'],
                'eval' 			        => array('style' => 'width:180px')
            ),
            'fussball_ta_value' => array
            (
                'label'                 => 'Wert',
                'inputType'             => 'text',
                'eval' 		            => array('style'=>'width:420px', 'allowHtml' => true)
            )
        );
    }
}





