<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2012 <http://kozianka-online.de/>
 * @author     Martin Kozianka <http://kozianka-online.de/>
 * @package    fussball_widget
 * @license    LGPL
 * @filesource
 */

/**
 * Add palettes to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_widget']   = '{title_legend},headline,type;{fussball_widget_legend},fussball_wettbewerbs_id,fussball_saison,fussball_team;{expert_legend:hide},cssID,space';

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_highscore_graph']   =
'{title_legend},headline,type;'
.'{fussball_graph_legend},fussball_positions,size,fussball_maxPositions;{expert_legend:hide},cssID,space';


$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_results'] =
'{title_legend},headline,type;'
.'{fussball_results_legend},fussball_team, fussball_results;{expert_legend:hide},cssID,space';

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_tournament_list'] =
'{title_legend},headline,type;'
.'{fussball_widget_legend},fussball_filter_team;'
.'{expert_legend:hide},cssID,space';



/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_results'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_results'],
	'exclude'                 => true,
	'inputType'               => 'multiColumnWizard',
	'eval'                    => array(
			
			
			'columnFields' => array
			(
					'fussball_is_homematch' => array
					(
							'label'                 => &$GLOBALS['TL_LANG']['tl_content']['fussball_is_homematch'],
							'exclude'               => true,
							'inputType'             => 'checkbox',
							'eval'                  => array('style'=>'width:40px')
					),
					'fussball_opponent' => array
					(
							'label'                 => &$GLOBALS['TL_LANG']['tl_content']['fussball_opponent'],
							'exclude'               => true,
							'inputType'             => 'text',
							'eval' 			        => array('style' => 'width:180px',  'tl_class' => 'fussball_opponent' )
					),
					'fussball_result' => array
					(
							'label'                 => &$GLOBALS['TL_LANG']['tl_content']['fussball_result'],
							'exclude'               => true,
							'inputType'             => 'text',
							'eval' 			        => array('style' => 'width:60px')
					),
			)			
	)
);


$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_saison'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_saison'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_wettbewerbs_id'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_wettbewerbs_id'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true)
);
$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_team'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_team'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array()
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_positions'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_positions'],
	'exclude'                 => true,
	'inputType'               => 'keyValueWizard',
	'eval'                    => array('tl_class' => 'long')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_maxPositions'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_maxPositions'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('rgxp' => 'digit')
);


$GLOBALS['TL_DCA']['tl_content']['fields']['size']['label'] = &$GLOBALS['TL_LANG']['tl_content']['fussball_graph_size'];
$GLOBALS['TL_DCA']['tl_content']['fields']['size']['options'] = array('crop');


// TODO :: Templateauswahl
$GLOBALS['TL_DCA']['tl_content']['fields']['fussball_filter_team'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fussball_filter_team'],
		'exclude'                 => true,
		'search'                  => true,
		'inputType'               => 'select',
		'options'				  => array_map('trim', explode(',', $GLOBALS['TL_CONFIG']['fussball_tourn_teams'])),
		'eval'                    => array('maxlength'=>255, 'tl_class'=>'long', 'decodeEntities' => true, 'includeBlankOption' => true)
);

