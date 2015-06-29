<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2015 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    fussball
 * @license    LGPL
 * @filesource
 */


$GLOBALS['TL_DCA']['tl_fussball_match'] = array(

// Config
'config' => array
(
	'dataContainer'               => 'Table',
    'ptable'                      => 'tl_fussball_team',
    'switchToEdit'                => true,
    'enableVersioning'            => true,
    'onsubmit_callback'           => [['tl_fussball_match', 'setVereinGegner']],
    'sql' => array(
        'keys' => array(
            'id'  => 'primary',
            'pid' => 'index'
        )
    )
),

// List
'list' => array
(
	'sorting' => array
	(
        'mode'                    => 4,
        'fields'                  => array('anstoss ASC'),
        'panelLayout'             => 'sort, limit',
        'disableGrouping'         => true,
        'headerFields'            => array('name', 'name_external'),
        'child_record_callback'   => array('tl_fussball_match', 'listMatch'),
        'child_record_class'      => 'tl_fussball tl_fussball_match'
	),
    'operations' => array
    (
        'edit' => array
        (
            'label'               => &$GLOBALS['TL_LANG']['tl_fussball_match']['edit'],
            'href'                => 'act=edit',
            'icon'                => 'edit.gif',
            'attributes'          => 'class="contextmenu"'
        ),
        'delete' => array
        (
            'label'               => &$GLOBALS['TL_LANG']['tl_fussball_match']['delete'],
            'href'                => 'act=delete',
            'icon'                => 'delete.gif',
            'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_fussball_match']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
        )
    )

),

// Palettes
'palettes' => array
(
    'default'                     => '{title_legend}, pid, heimspiel, gegner, title, anstoss, typ,  ergebnis, platzart, location',
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
    'pid' => array
    (
        'foreignKey'              => 'tl_fussball_team.name',
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
        'relation'                => array('type'=>'belongsTo', 'load'=>'eager'),
        'input_field_callback'    => array('tl_fussball_match', 'inputFieldCallback'),
        'eval'                    => array('tl_class' => 'long', 'readonly' => true),

    ),
    'tstamp' => array
    (
        'label'                   => array('TSTAMP'),
        'search'                  => false,
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
    ),
    'anstoss' => array
	(
			'label'                   => $GLOBALS['TL_LANG']['tl_fussball_match']['anstoss'],
            'flag'                    => 8,
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class' => 'w50', 'rgxp' => 'datim', 'datepicker' => true, 'mandatory' => true),
            'sql'                     => "int(10) unsigned NULL"
	),
    'heimspiel' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_match']['heimspiel'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12', 'submitOnChange' => true),
            'sql'                     => "char(1) NOT NULL default ''",
    ),
    'gegner' => array(
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_match']['gegner'],
        'exclude'                 => true,
        'search'                  => true,
        'sorting'                 => false,
        'inputType'               => 'select',
        'options_callback'        => ['tl_fussball_match', 'getTeamNames'],
        'eval'                    => array('tl_class' => 'w50', 'mandatory' => true, 'chosen' => true, 'includeBlankOption' => true, 'submitOnChange' => true),
        'sql'                     => "varchar(255) NOT NULL default ''"
    ),
    'verein_gegner' => array(
        'label'                   => ['VEREIN_GEGNER', 'VEREIN_GEGNER'],
        'exclude'                 => true,
        'search'                  => false,
        'sorting'                 => false,
        'foreignKey'              => 'tl_fussball_verein.name',
        'relation'                => array('type'=>'hasOne', 'load'=>'eager'),
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
    ),
    'title' => array(
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_match']['title'],
        'exclude'                 => true,
        'search'                  => true,
        'sorting'                 => false,
        'inputType'               => 'text',
        'eval'                    => array('tl_class' => 'long', 'readonly' => true),
    ),
	'typ' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_match']['typ'],
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => true,
            'options'                 => \ContaoFussball\FussballDataManager::$MATCH_TYPES,
            'reference'               => &$GLOBALS['TL_LANG']['contao_fussball']['match_types'],
            'default'                 => \ContaoFussball\FussballDataManager::$MATCH_TYPES[0],
            'inputType'               => 'select',
            'eval'                    => array('tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'ergebnis' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_match']['ergebnis'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
    ),
    'location' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_match']['location'],
        'search'                  => false,
        'inputType'               => 'textarea',
        'eval'                    => array('tl_class' => 'clr long'),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'platzart' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_match']['platzart'],
        'search'                  => false,
        'inputType'               => 'select',
        'options'                 => \ContaoFussball\FussballDataManager::$FIELD_TYPES,
        'default'                 => \ContaoFussball\FussballDataManager::$FIELD_TYPES[0],
        'eval'                    => array('tl_class' => 'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'link' => array
    (
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_match']['link'],
        'search'                  => false,
        'inputType'               => 'text',
        'eval'                    => array('tl_class' => 'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),

) //fields

);

use ContaoFussball\Models\FussballMatchModel;
use ContaoFussball\Models\FussballTeamModel;
use ContaoFussball\Models\FussballVereinModel;

class tl_fussball_match extends Backend {
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

    public function inputFieldCallback(DataContainer $dc) {
        $strTeam  = '';
        $objMatch = FussballMatchModel::findByPk($dc->id);
        if ($objMatch != null) {
            $objTeam  = FussballTeamModel::findByPk($objMatch->pid);
            $strTeam .= '<h2>'.$objTeam->name.'</h2>';
            $strTeam .= '<input type="hidden" name="name_external" value="'.$objTeam->name_external.'">';

            /* Gegnerliste generieren [#gegner_list]
            $arrGegner       = [];
            $matchCollection = FussballMatchModel::findBy('pid', $objMatch->pid);
            foreach($matchCollection as $m) {
                if (!in_array($m->gegner, $arrGegner)) {
                    $arrGegner[] = $m->gegner;
                }
            }
            $strTeam .= '<ul id="gegner_list"><li>'.implode('</li><li>', $arrGegner).'</li></ul>';
            */
        }
        return $strTeam;
    }

    /**
     * List a match
     * @param array
     * @return string
     */
    public function listMatch($row)
    {
        $team   = $this->teams[$row['pid']];
        $imgSRC = \Image::getHtml(\Image::get('system/modules/fussball/assets/icons/match_typ_'.standardize($row['typ']).'.png', 12, 12));

        $title = ($row['heimspiel'] === '1') ? ($team->name_external.' - '.$row['gegner']): ($row['gegner'].' - '.$team->name_external);
        $arrRow = [
            'type'      => $imgSRC,
            'team'      => $team->name_short,
            'anstoss'   => \Date::parse('d.m.Y H:i', $row['anstoss']),
            'title'     => $title
        ];

        $strRow  = '';
        $strTmpl = '<div class="tl_fussball_cell %s">%s</div>';
        foreach($arrRow as $k => $v) {
            $strRow .= sprintf($strTmpl, $k, $v);
        }

        // Add ergebnis
        $strTmpl = '<div class="tl_fussball_cell ergebnis">
                    <span id="match%s" onclick="editResult(%s)" class="ergebnis %s">
                        <span id="value%s" class="currentValue">%s</span>
                        <span id="field%s" class="editField"><input id="input%s" name="newValue" type="text" value="%s" onblur="saveResult(%s)"></span>
                    </span>
                </div>';
        $strRow .= sprintf($strTmpl,
            $row['id'],
            $row['id'],
            (strlen($row['ergebnis']) > 0) ? 'result' : 'init',
            $row['id'],
            (strlen($row['ergebnis']) > 0) ? $row['ergebnis'] : '&nbsp;',
            $row['id'],
            $row['id'],
            (strlen($row['ergebnis']) > 0) ? $row['ergebnis'] : '',
            $row['id']
        );

        return $strRow;
    }

    public function setVereinGegner($dc) {
        if ($dc->activeRecord === null) {
            return false;
        }

        $objMatch  = FussballMatchModel::findByPk($dc->activeRecord->id);
        if ($objMatch === null) {
            return false;
        }

        // set verein id in match
        $length    = strlen($dc->activeRecord->gegner);
        $strSearch = '%{s:5:"value";s:'.$length.':"'.$dc->activeRecord->gegner.'";%';
        $objVerein = FussballVereinModel::findBy(['teams LIKE ?'], [$strSearch]);
        $objMatch->verein_gegner = ($objVerein !== null) ? $objVerein->id : 0;

        // Bei einem Heimspiel müssen die Daten vom Heimatverein gelesen werden
        if ($dc->activeRecord->heimspiel === '1') {
            $objVerein = FussballVereinModel::findOneBy('home', '1');
        }

        if ($objVerein !== null) {
            $objMatch->platzart = $objVerein->platzart;
            $objMatch->location = $objVerein->location;
        }

        $objMatch->save();

    }

    public function getTeamNames() {
        $arrNames         = [];
        $vereinCollection = FussballVereinModel::findAll(['order' => 'name ASC']);
        if ($vereinCollection != null) {
            foreach($vereinCollection as $objVerein) {
                $arrTeams = deserialize($objVerein->teams);
                foreach($arrTeams as $k => $arrTeam) {
                    $arrNames[] = $arrTeam['value'];
                }
            }
        }
        return $arrNames;
    }

}


// Adjust DCA for listing all matches
if (Input::get('do') == 'fussball_matches')
{
    $a = &$GLOBALS['TL_DCA']['tl_fussball_match'];

    unset($a['config']['ptable']);

    $a['config']['closed']        = true;
    // $a['config']['notEditable']   = true;
    // $a['config']['notDeletable']  = true;
    // $a['list']['operations']      = [];

    $a['list']['sorting']['mode'] = 2;

    unset($a['list']['sorting']['disableGrouping']);
    unset($a['list']['sorting']['headerFields']);
    unset($a['list']['sorting']['child_record_callback']);
    unset($a['list']['sorting']['child_record_class']);

    $a['list']['label'] = array(
        'fields'                  => array('typ', 'pid', 'anstoss', 'title', 'ergebnis'),
        'label_callback'          => array('tl_fussball_match', 'listMatch')
    );

}


