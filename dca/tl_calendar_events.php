<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2012
 * @author     Martin Kozianka 
 * @package    calendar_tournaments
 * @license    GNU/LGPL 
 * @filesource
 */


$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onload_callback'][] = array('fussball_tl_calendar_events', 'tournamentCalendar');

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fussball_tourn_clients'] = array
(
'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['fussball_tourn_clients'],
'exclude'                 => true,
'search'                  => true,
'inputType'               => 'select',
'options'				  => array_map('trim', explode(',', $GLOBALS['TL_CONFIG']['fussball_tourn_teams'])),
'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fussball_tourn_host'] = array
(
'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['fussball_tourn_host'],
'exclude'                 => true,
'search'                  => true,
'inputType'               => 'text',
'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fussball_tourn_location'] = array
(
'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['fussball_tourn_location'],
'exclude'                 => true,
'search'                  => true,
'inputType'               => 'text',
'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fussball_tourn_type'] = array
(
'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['fussball_tourn_type'],
'exclude'                 => true,
'search'                  => true,
'inputType'               => 'select',
'options'                 => array("Asche", "Kunstrasen", "Halle", "Kunstrasen (Halle)", "Rasen"),
'eval'                    => array('tl_class'=>'w50', 'decodeEntities' => true),
'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fussball_tourn_confirmed'] = array
(
'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['fussball_tourn_confirmed'],
'exclude'                 => true,
'search'                  => true,
'inputType'               => 'checkbox',
'eval'                    => array('tl_class'=>'w50 m12'),
'sql'                     => "char(1) NOT NULL default ''"
);


class fussball_tl_calendar_events extends Backend {

	public function tournamentCalendar() {
		$table = $this->Input->get('table');
		$id    = $this->Input->get('id');
		
		if ($table != 'tl_calendar_events') {
			return false;
		}

		$result = $this->Database->prepare('SELECT pid FROM tl_calendar_events WHERE id = ?')
					->execute($id);
		if ($result->numRows !== 1) {
			return false;
		}		
		
		if ($result->pid == $GLOBALS['TL_CONFIG']['fussball_tourn_calendar']) {
			$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace(
					'{title_legend},title,alias,author;',
					'{fussball_widget_legend},title,alias,fussball_tourn_clients, fussball_tourn_host, fussball_tourn_location, fussball_tourn_type, fussball_author, fussball_tourn_confirmed;',
					$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']
			);
		}
	}

}

?>