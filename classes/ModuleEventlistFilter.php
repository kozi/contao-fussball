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
 * @author     eventlist_filter
 * @package    eventlist_filter
 * @license    LGPL 
 * @filesource
 */


/**
 * Class ModuleEventlistFilter
 *
 * @copyright  2011 
 * @author     Martin Kozianka
 * @package    Controller
 */

class ModuleEventlistFilter extends ModuleEventlist {

	private $filterArray = array();

	protected function getAllEvents($arrCalendars, $intStart, $intEnd) {
		
		$days = parent::getAllEvents($arrCalendars, $intStart, $intEnd);
		foreach($days as $key_day => &$day) {
			foreach($day as $key_starts => &$starts) {
				foreach($starts as $key_ev => $ev) {
					if ($this->filterEvent($ev)){
						unset($starts[$key_ev]);
					}
				}
			}
		}
		return $days;
	}
	
	private function filterEvent($ev) {
		
		foreach($this->filterArray as $key => $value) {
			if ($value !== '' && strpos($ev[$key], $value) === false) {
				return true;
			}
		}
		return false;
	}

	public function addFilter($key, $value) {
		$this->filterArray[$key] = $value;
	}
	
	public function setFilter($filter) {
		$fArr = array();
		parse_str($filter, $fArr);
		
		$this->filterArray = $fArr;
	}
}

?>