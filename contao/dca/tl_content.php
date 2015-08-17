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

/**
 * Add palettes to tl_content
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_infobox']  = $GLOBALS['TL_DCA']['tl_content']['palettes']['text'];

$strHL = 'headline;';

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_matches'] =  str_replace(
    $strHL,
    $strHL.'{fussball_matches_legend},fussball_team_id,fussball_typ,fussball_past,fussball_future,fussball_from,fussball_to,fussball_order;',
    $GLOBALS['TL_DCA']['tl_content']['palettes']['headline']);




$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_widget'] = str_replace(
    $strHL,
    $strHL.'{fussball_legend},fussball_website_key,fussball_team_id',
    $GLOBALS['TL_DCA']['tl_content']['palettes']['headline']);

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_tournament'] = str_replace(
    $strHL,
    $strHL.'{fussball_legend},fussball_team_id_array;',
    $GLOBALS['TL_DCA']['tl_content']['palettes']['headline']);

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_calendar'] = str_replace(
    $strHL,
    $strHL.'{fussball_legend},fussball_team_id_array;',
    $GLOBALS['TL_DCA']['tl_content']['palettes']['headline']);

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_goalgetter'] = str_replace(
    $strHL,
    $strHL.'{fussball_legend},fussball_goalgetter;',
    $GLOBALS['TL_DCA']['tl_content']['palettes']['headline']);

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_team'] = str_replace(
    $strHL,
    $strHL.'{fussball_legend},fussball_team_id;',
    $GLOBALS['TL_DCA']['tl_content']['palettes']['headline']);


$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_website_key'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_website_key'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                     => ['mandatory'=>true],
    'sql'                     => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_team_id'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_team_id'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_fussball_team.name',
    'eval'                     => ['tl_class' => 'w50', 'includeBlankOption' => true],
    'sql'                     => "int(10) unsigned NULL",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_team_id_array'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_team_id_array'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_fussball_team.name',
    'eval'                     => ['multiple' => true, 'size' => 5],
    'sql'                     => "blob NULL",
];


$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_order'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_order'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => &$GLOBALS['TL_LANG']['tl_content']['fussball_order']['options'],
    'eval'                     => ['tl_class' => 'w50'],
    'sql'                     => "varchar(8) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_typ'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_typ'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => \ContaoFussball\FussballDataManager::$MATCH_TYPES,
    'reference'               => &$GLOBALS['TL_LANG']['contao_fussball']['match_types'],
    'eval'                     => ['includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql'                     => "varchar(8) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_future'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_future'],
    'default'                 => '',
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                     => ['rgxp' => 'digit', 'tl_class' => 'w50'],
    'sql'                     => "int(10) unsigned NULL",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_past'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_past'],
    'default'                 => '',
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                     => ['rgxp' => 'digit', 'tl_class' => 'w50'],
    'sql'                     => "int(10) unsigned NULL",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_from'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_from'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'flag'                    => 8,
    'eval'                    => ['rgxp'=>'date', 'datepicker'=>true, 'tl_class' => 'w50 wizard'],
    'save_callback'           => [['tl_content_fussball', 'setEmptyDate']],
    'sql'                     => "int(10) unsigned NULL",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_to'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_to'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'flag'                    => 8,
    'eval'                    => ['rgxp'=>'date', 'datepicker'=>true, 'tl_class' => 'w50 wizard'],
    'save_callback'           => [['tl_content_fussball', 'setEmptyDate']],
    'sql'                     => "int(10) unsigned NULL",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_goalgetter'] = [
		'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_goalgetter'],
		'exclude'                 => true,
		'inputType'               => 'multiColumnWizard',
		'save_callback'           => [['tl_content_fussball', 'sortGoalgetterEntries']],
        'sql'                     => "blob NULL",
		'eval'                    => [
			'buttons'      => ['down' => false, 'up' => false],
			'columnFields' => [
					'fussball_gg_name' => [
							'label'                 => &$GLOBALS['TL_LANG']['tl_content']['fussball_gg_name'],
							'exclude'               => true,
							'inputType'             => 'text',
							'eval'                  => ['style'=>'width:300px', 'tl_class' => 'fussball_gg_name']
					],
					'fussball_gg_goals' => [
							'label'                 => &$GLOBALS['TL_LANG']['tl_content']['fussball_gg_goals'],
							'exclude'               => true,
							'inputType'             => 'text',
							'eval' 			        => ['style' => 'width:40px',  'rgxp' => 'digit', 'tl_class' => 'fussball_goalgetter_name_goals']
                    ],
			]
	    ]
]; // fussball_goalgetter ENDE

class tl_content_fussball extends Backend
{

    public function setEmptyDate($varValue, DataContainer $dc)
    {
        if ($varValue === '')
        {
            $varValue = null;
        }
        return $varValue;
    }

    public function sortGoalgetterEntries($var, $dc)
    {
        $arr = unserialize($var);

        usort($arr, function($a, $b)
        {
            $res = intval($a['fussball_gg_goals']) - intval($b['fussball_gg_goals']);
            if ($res == 0) return strcmp($a['fussball_gg_name'], $b['fussball_gg_name']);
            if ($res >  0) return -1;
            return 1;
        });

        return serialize($arr);
    }
}
