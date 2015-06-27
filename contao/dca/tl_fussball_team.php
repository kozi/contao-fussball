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

$GLOBALS['TL_DCA']['tl_fussball_team'] = array(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'switchToEdit'                => true,
        'enableVersioning'            => true,
        'ctable'                      => array('tl_fussball_match', 'tl_fussball_tournament'),
        'sql' => array(
            'keys' => array('id' => 'primary')
        )
    ),


// List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('sorting ASC'),
            'flag'                    => 11,
            'panelLayout'             => 'filter, search, limit'
        ),
        'label' => array
        (
            'fields'                  => array('bgcolor', 'name', 'team_attributes'),
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
            'match' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_fussball_team']['match'],
                'href'                => 'table=tl_fussball_match',
                'icon'                => 'system/modules/fussball/assets/icons/soccer.png',
                'attributes'          => 'class="contextmenu"'
            ),
            'tournament' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_fussball_team']['tournament'],
                'href'                => 'table=tl_fussball_tournament',
                'icon'                => 'system/modules/fussball/assets/icons/tournament.png',
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
        'default' => '{title_legend},name,name_short,name_external,name_short_external,bgcolor;
                        {team_attr_legend},team_attributes;
                        {team_default_legend},default_time, default_platzart'
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
        'sorting' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
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
        'name_short_external' => array
        (
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['name_short_external'],
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
            'sql'                     => "varchar(64) NOT NULL default ''"
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
        'lastUpdate' => array
        (
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['lastUpdate'],
            'flag'                    => 8,
            'search'                  => false,
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
        ),
        'default_time' => array(

            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['default_time'],
            'search'                  => false,
            'inputType'               => 'text',
            'eval'                    => array('tl_class' => 'w50', 'rgxp' => 'time', 'mandatory' => true),
            'sql'                     => "varchar(5) NOT NULL default ''",
        ),
        'default_platzart' => array(

            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['default_platzart'],
            'search'                  => false,
            'inputType'               => 'select',
            'options'                 => \ContaoFussball\FussballDataManager::$FIELD_TYPES,
            'default'                 => \ContaoFussball\FussballDataManager::$FIELD_TYPES[0],
            'eval'                    => array('tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),


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
            'Abkürzung: '.$row['name_short']
        ));

        $args[2]         = '';
        $team_attributes = unserialize($row['team_attributes']);

        if (is_array($team_attributes)) {
            foreach ($team_attributes as $attribute) {
                $args[2] .= sprintf($this->tmplTeamAttribute, $attribute['fussball_ta_key'], $attribute['fussball_ta_value']);
            }
        }
        $url = \Environment::get('script').'?do=fussball_teams&id=%s&key=sorting&sort=%s';
        $args[3] = sprintf('<div style="inline-block;width:26px;"><a class="down" href="'.$url.'"><img src="system/themes/default/images/down.gif"></a><a class="up" href="'.$url.'"><img src="system/themes/default/images/up.gif"></a></div>',
            $row['id'], 'down',
            $row['id'], 'up'
        );

        return $args;
    }

    public function teamAttributes() {
        return array
        (
            'fussball_ta_key' => array
            (
                'label'                 => 'Attribut',
                'inputType'             => 'select',
                'options'            	=> $GLOBALS['fussball']['team_attributes'],
                'eval' 			        => array('style' => 'width:200px')
            ),
            'fussball_ta_value' => array
            (
                'label'                 => 'Wert',
                'inputType'             => 'text',
                'eval' 		            => array('style'=>'width:380px', 'allowHtml' => true)
            )
        );
    }
}


