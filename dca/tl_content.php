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

/**
 * Add palettes to tl_content
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_widget']   = '{title_legend},headline,type;{fussball_widget_legend},fussball_wettbewerbs_id,fussball_mandant,fussball_team_id;{expert_legend:hide},cssID,space';


$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_matches'] = '{title_legend},headline,type;'
.'{fussball_matches_legend},fussball_team_id,fussball_typ,fussball_past,fussball_future,fussball_from,fussball_to,fussball_order;{expert_legend:hide},cssID,space';

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_tournament'] =
'{title_legend},headline,type;'
.'{fussball_widget_legend},fussball_team_id_array;'
.'{expert_legend:hide},cssID,space';


$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_calendar'] =
    '{title_legend},headline,type;'
    .'{fussball_widget_legend},fussball_team_id_array;'
    .'{expert_legend:hide},cssID,space';

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_goalgetter'] =
'{title_legend},headline,type;'
.'{fussball_widget_legend},fussball_goalgetter;'
.'{expert_legend:hide},cssID,space';

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_teams'] =
    '{title_legend},headline,type;'
    .'{fussball_widget_legend},fussball_template;'
    .'{expert_legend:hide},cssID,space';


$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_mandant'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_mandant'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true),
    'sql'                     => "varchar(255) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_wettbewerbs_id'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_wettbewerbs_id'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true),
    'sql'                     => "varchar(255) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_team_id'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_team_id'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_fussball_team.name',
    'eval'                    => array('tl_class' => 'w50'),
    'sql'                     => "int(10) unsigned NULL",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_team_id_array'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_team_id_array'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_fussball_team.name',
    'eval'                    => array('multiple' => true, 'size' => 5),
    'sql'                     => "blob NULL",
);


$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_order'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_order'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => &$GLOBALS['TL_LANG']['tl_content']['fussball_order']['options'],
    'eval'                    => array('tl_class' => 'w50'),
    'sql'                     => "varchar(8) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_typ'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_typ'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => &$GLOBALS['TL_LANG']['tl_content']['fussball_typ']['options'],
    'eval'                    => array('includeBlankOption' => true, 'tl_class' => 'w50'),
    'sql'                     => "varchar(8) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_future'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_future'],
    'default'                 => '',
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp' => 'digit', 'tl_class' => 'w50'),
    'sql'                     => "int(10) unsigned NULL",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_past'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_past'],
    'default'                 => '',
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp' => 'digit', 'tl_class' => 'w50'),
    'sql'                     => "int(10) unsigned NULL",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_from'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_from'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'flag'                    => 8,
    'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'tl_class' => 'w50 wizard'),
    'save_callback'           => array(
        array('tl_content_fussball', 'setEmptyDate')
    ),
    'sql'                     => "int(10) unsigned NULL",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_to'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_to'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'flag'                    => 8,
    'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'tl_class' => 'w50 wizard'),
    'save_callback'           => array(
        array('tl_content_fussball', 'setEmptyDate')
    ),
    'sql'                     => "int(10) unsigned NULL",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_goalgetter'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_goalgetter'],
		'exclude'                 => true,
		'inputType'               => 'multiColumnWizard',
		'save_callback'           => array(array('tl_content_fussball', 'sortGoalgetterEntries')),
        'sql'                     => "blob NULL",
		'eval'                    => array(
			'buttons' => array('down' => false, 'up' => false),
			
			'columnFields' => array
			(
					'fussball_gg_name' => array
					(
							'label'                 => &$GLOBALS['TL_LANG']['tl_content']['fussball_gg_name'],
							'exclude'               => true,
							'inputType'             => 'text',
							'eval'                  => array('style'=>'width:300px', 'tl_class' => 'fussball_gg_name')
					),
					'fussball_gg_goals' => array
					(
							'label'                 => &$GLOBALS['TL_LANG']['tl_content']['fussball_gg_goals'],
							'exclude'               => true,
							'inputType'             => 'text',
							'eval' 			        => array('style' => 'width:40px',  'rgxp' => 'digit', 'tl_class' => 'fussball_goalgetter_name_goals')
					),
			)			
	)
); // fussball_goalgetter ENDE


class tl_content_fussball extends Backend {

    public function setEmptyDate($varValue, DataContainer $dc) {
        if ($varValue === '') {
            $varValue = null;
        }
        return $varValue;
    }

    public function sortGoalgetterEntries($var, $dc) {
        $arr = unserialize($var);

        function usort_sortGoalgetterEntries($a, $b) {
            $res = intval($a['fussball_gg_goals']) - intval($b['fussball_gg_goals']);
            if ($res == 0) return strcmp($a['fussball_gg_name'], $b['fussball_gg_name']);
            if ($res >  0) return -1;
            return 1;
        }

        usort($arr, 'usort_sortGoalgetterEntries');
        return serialize($arr);
    }

}
