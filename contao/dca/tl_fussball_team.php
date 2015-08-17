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

$GLOBALS['TL_DCA']['tl_fussball_team'] = [

    // Config
    'config' => [
        'dataContainer'           => 'Table',
        'switchToEdit'            => true,
        'enableVersioning'        => true,
        'ctable'                  => ['tl_fussball_match', 'tl_fussball_tournament'],
        'sql'                     => ['keys' => ['id' => 'primary']]
    ],


    // List
    'list' => [

        'sorting' => [
            'mode'                    => 1,
            'fields'                  => ['sorting ASC'],
            'flag'                    => 11,
            'panelLayout'             => 'filter, search, limit'
        ],
        'label' => [
            'fields'                  => ['bgcolor', 'name', 'team_attributes'],
            'showColumns'             => true,
            'label_callback'          => ['tl_fussball_team', 'labelCallback']
        ],
        'operations' => [

            'edit' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_fussball_team']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif',
                'attributes'          => 'class="contextmenu"'
            ],
            'match' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_fussball_team']['match'],
                'href'                => 'table=tl_fussball_match',
                'icon'                => 'system/modules/fussball/assets/icons/soccer.png',
                'attributes'          => 'class="contextmenu"'
            ],
            'tournament' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_fussball_team']['tournament'],
                'href'                => 'table=tl_fussball_tournament',
                'icon'                => 'system/modules/fussball/assets/icons/tournament.png',
                'attributes'          => 'class="contextmenu"'
            ],
            'delete' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_fussball_team']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_fussball_team']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ]
        ]

    ],

    // Palettes
    'palettes' => [
        'default' => '{title_legend},name,name_short,name_external,name_short_external,bgcolor;
                        {team_attr_legend},team_attributes;
                        {team_default_legend},default_time, default_typ'
    ],

    // Fields
    'fields' => [

        'id' => [
            'label'                   => ['ID'],
            'search'                  => false,
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ],
        'sorting' => [
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
        'tstamp' => [
            'label'                   => ['TSTAMP'],
            'search'                  => false,
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
        ],
        'name' => [
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['name'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''",
        ],
        'name_short' => [
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['name_short'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''",
        ],
        'name_external' => [
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['name_external'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''",
        ],
        'name_short_external' => [
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['name_short_external'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''",
        ],
        'bgcolor' => [
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['bgcolor'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'],
            'sql'                     => "varchar(64) NOT NULL default ''"
        ],
        'team_attributes' => [
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['team_attributes'],
            'exclude'                 => true,
            'search'                  => false,
            'sorting'                 => false,
            'inputType'		          => 'multiColumnWizard',
            'eval'			          => ['mandatory'=>false, 'allowHtml' => true, 'columnsCallback'=> ['tl_fussball_team', 'teamAttributes']],
            'sql'                     => "blob NULL",
        ],
        'lastUpdate' => [
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['lastUpdate'],
            'flag'                    => 8,
            'search'                  => false,
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
        ],
        'default_time' => [
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['default_time'],
            'search'                  => false,
            'inputType'               => 'text',
            'eval'                    => ['tl_class' => 'w50', 'rgxp' => 'time', 'mandatory' => true],
            'sql'                     => "varchar(5) NOT NULL default ''",
        ],
        'default_typ' => [
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_team']['default_typ'],
            'search'                  => false,
            'inputType'               => 'select',
            'options'                 => \ContaoFussball\FussballDataManager::$MATCH_TYPES,
            'reference'               => &$GLOBALS['TL_LANG']['contao_fussball']['match_types'],
            'default'                 => \ContaoFussball\FussballDataManager::$MATCH_TYPES[0],
            'eval'                    => ['tl_class' => 'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''",
        ],


    ] //fields

];

class tl_fussball_team extends Backend
{
    private $tmplBgcolor       = '<span class="fussball_bgcolor" style="background-color:#%s;">&nbsp;</span>';
    private $tmplTeamAttribute = '<strong>%s</strong>: %s<br>';

    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function labelCallback($row, $label, DataContainer $dc, $args = null)
    {
        if ($args === null)
        {
            return $label;
        }

        $bgcolor = unserialize($row['bgcolor']);
        $args[0] = sprintf($this->tmplBgcolor, $bgcolor[0]);
        $args[1] = implode('<br>', [$row['name'], 'Abkürzung: '.$row['name_short']]);

        $args[2]         = '';
        $team_attributes = unserialize($row['team_attributes']);

        if (is_array($team_attributes))
        {
            foreach ($team_attributes as $attribute)
            {
                $args[2] .= sprintf($this->tmplTeamAttribute, $attribute['fussball_ta_key'], $attribute['fussball_ta_value']);
            }
        }

        $url     = \Environment::get('script').'?do=fussball_teams&id=%s&key=sorting&sort=%s';
        $args[3] = sprintf('<div style="inline-block;width:26px;"><a class="down" href="'.$url.'"><img src="system/themes/default/images/down.gif"></a><a class="up" href="'.$url.'"><img src="system/themes/default/images/up.gif"></a></div>',
            $row['id'], 'down',
            $row['id'], 'up'
        );

        return $args;
    }

    public function teamAttributes()
    {
        return [
            'fussball_ta_key' => [
                'label'                 => 'Attribut',
                'inputType'             => 'select',
                'options'            	=> $GLOBALS['fussball']['team_attributes'],
                'eval' 			        => ['style' => 'width:200px']
            ],
            'fussball_ta_value' => [
                'label'                 => 'Wert',
                'inputType'             => 'text',
                'eval' 		            => ['style'=>'width:380px', 'allowHtml' => true]
            ]
        ];

    }
}
