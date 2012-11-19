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
 * @author     fussball_widget
 * @package    fussball_widget
 * @license    LGPL 
 * @filesource
 */


/**
 * Class SimpleDatabaseResult
 *
 * @copyright  2011 
 * @author     Martin Kozianka
 * @package    Controller
 */

class SimpleDatabaseResult extends Database_Result {
	public $data;
	public $cssID;
	public $space;
	
	public function __construct($arrData = array()) {
		$this->data = $arrData;
	}
	
	public function row($blnFetchArray=false) {
		return $this->data;
	}

	protected function fetch_row() {}
	protected function fetch_assoc() {}
	protected function num_rows() {}
	protected function num_fields() {}
	protected function fetch_field($intOffset) {}
	public function free() {}
}

?>