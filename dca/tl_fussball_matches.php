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

$GLOBALS['TL_DCA']['tl_fussball_matches'] = array(

// Config
'config' => array
(
	'dataContainer'               => 'Table',
    'closed'                      => true,
    'sql' => array(
        'keys' => array('id' => 'primary')
    ),
    'onload_callback'             => array(
        // array('tl_fussball_matches', 'replaceLabel')
    )
),


// List
'list' => array
(
	'sorting' => array
	(
		'mode'                    => 2,
		'fields'                  => array('anstoss DESC'),
		'flag'                    => 1,
		'panelLayout'             => 'sort, filter, limit'
	),
	'label' => array
	(
		'fields'                  => array('team_id', 'anstoss', 'heim', 'gast', 'ergebnis', 'typ'),
		'showColumns'             => true,
		'label_callback'          => array('tl_fussball_matches', 'labelCallback')
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
    'default'                     => '{title_legend},heim,ergebnis;',
),


    /*
  `` varchar(255) NOT NULL default '',
  `team_id` int(10) unsigned NOT NULL default '0',
  `anstoss` int(10) unsigned NOT NULL default '0',
  `heim` varchar(255) NOT NULL default '',
  `gast` varchar(255) NOT NULL default '',
  `ergebnis` varchar(255) NOT NULL default '',
  `typ` varchar(255) NOT NULL default '',
  `spielklasse` varchar(255) NOT NULL default '',
  `ort` ,

*/

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
    'spielkennung' => array
    (
        'label'                   => array('SPIELKENNUNG'),
        'search'                  => false,
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'team_id' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_matches']['team_id'],
            'exclude'                 => true,
            'foreignKey'              => 'tl_fussball_team.name',
            'filter'                  => true,
            'search'                  => false,
            'sorting'                 => true,
            'inputType'               => 'text',
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
    ),
    'anstoss' => array
	(
			'label'                   => $GLOBALS['TL_LANG']['tl_fussball_matches']['anstoss'],
            'flag'                    => 8,
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'sql'                     => "int(10) unsigned NULL"
	),
    'heim' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_matches']['heim'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => false,
            'inputType'               => 'text',
            'input_field_callback'    => array('tl_fussball_matches', 'inputFieldCallback'),
            'sql'                     => "varchar(255) NOT NULL default ''",

	),
	'gast' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_matches']['gast'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => false,
            'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'typ' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_matches']['typ'],
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => true,
            'sorting'                 => true,
            'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'ergebnis' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_matches']['ergebnis'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'               => 'text',
            'eval'                    => array(),
            'sql'                     => "varchar(255) NOT NULL default ''"
    ),

    'spielklasse' => array
    (
        'label'                   => array('SPIELKLASSE'),
        'search'                  => false,
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'location' => array
    (
        'label'                   => array('LOCATION'),
        'search'                  => false,
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),


) //fields

);


class tl_fussball_matches extends Backend {
    private $teams = array();
	public function __construct() {
		parent::__construct();
		$this->import('BackendUser', 'User');

        $result = $this->Database->execute('SELECT * FROM tl_fussball_team');

        while($result->next()) {
            $team                   = (Object) $result->row();
            $this->teams[$team->id] = $team;
        }
	}

	public function labelCallback($row, $label, DataContainer $dc, $args = null) {
		if ($args === null) {
			return $label;
		}
        $team_id    = $row['team_id'];
        $team       = $this->teams[$team_id];
        $args[0]    = $team->name_short;

        $imgSRC     = Image::get('system/modules/fussball_widget/assets/icons/match_typ_'.standardize($row['typ']).'.png', 14, 14);
        $args[5]    = Image::getHtml($imgSRC, $row['typ'], 'title="'.$row['typ'].'"');

		return $args;
	}

    public function inputFieldCallback(DataContainer $dc) {

        $result = $this->Database->prepare('SELECT * FROM tl_fussball_matches WHERE id = ?')->execute($dc->id);

        if ($result->numRows == 1) {
            $match = (Object) $result->row();

            $str  = '<h2><small>'.Date::parse('D, d.m.y H:i', $match->anstoss).'</small><br>';
            $str .= $match->heim.' - '.$match->gast.'</h2>';
            return $str;
        }

    }
}





