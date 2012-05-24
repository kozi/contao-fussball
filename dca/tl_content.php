<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao webCMS
 * Copyright (C) 2011 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  2011 
 * @author     Martin Kozianka
 * @package    mediabox
 * @license    LGPL 
 * @filesource
 */



/**
 * Add palettes to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_widget']   = '{title_legend},name,headline,type;{fussball_widget_legend},fussball_wettbewerbs_id,fussball_saison,fussball_team;{expert_legend:hide},cssID,space';

$GLOBALS['TL_DCA']['tl_content']['palettes']['fussball_highscore_graph']   =
	'{title_legend},name,headline,type;'
   .'{fussball_graph_legend},fussball_positions,size,fussball_maxPositions;{expert_legend:hide},cssID,space';

/**
 * Add fields to tl_module
 */
 

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


?>