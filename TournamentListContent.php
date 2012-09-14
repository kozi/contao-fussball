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
 * @author     Martin Kozianka <http://kozianka-online.de/>
 * @package    fussball_widget
 * @license    LGPL 
 * @filesource
 */


/**
 * Class TournamentListContent
 *
 * @copyright  2011 
 * @author     Martin Kozianka <http://kozianka-online.de/>
 * @package    Controller
 */

class TournamentListContent extends ContentModule {

	public function generate() {
		$cal_id = $GLOBALS['TL_CONFIG']['fussball_tourn_calendar'];
		
		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### TournamentList ###<br>'
				.'Archive: '.$cal_id.'<br>'
				.'Filter: '.$this->fussball_tournament_filter;
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		
		$data = array(
				'type' => 'eventlist',
				'cal_calendar' => serialize(array($cal_id)),
				'cal_noSpan' => '1',
				'cal_format' => 'next_all',
				'cal_order' => 'ascending',
				'cal_limit' => '0',
				'cal_template' => 'event_tournament',
				'cal_startDay' => '0',
				'cal_showQuantity' => '',
				'cal_ignoreDynamic' => '',
		);
		
		$objModule = new SimpleDatabaseResult($data);
		$objModule->headline = $this->headline;
		$objModule->space    = $this->space;
		$objModule->cssID    = $this->cssID;

		$eventlist = new ModuleEventlistFilter($objModule);
		$eventlist->addFilter('fussball_tourn_clients', $this->fussball_filter_team);
		
		return $eventlist->generate();
	}
			
	protected function compile() {
		return;
	}

}

?>