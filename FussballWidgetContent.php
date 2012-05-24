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
 * @author     ModuleFussballWidget
 * @package    fussball_widget 
 * @license    LGPL 
 * @filesource
 */


/**
 * Class FussballWidgetContent
 *
 * @copyright  2011 
 * @author     Martin Kozianka
 * @package    Controller
 */

class FussballWidgetContent extends ContentElement {
	protected $strTemplate = 'ce_fussball_widget';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FUSSBALL WIDGET ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			
			return $objTemplate->parse();
		}
		return parent::generate();
	}

	protected function compile() {
		$api_key = $GLOBALS['TL_CONFIG']['fussball_api_key'];
		$this->addJavascriptFiles($api_key);
	}

	protected function addJavascriptFiles($api_key) {
		
		
		$fussballAPI = 'http://static.fussball.de/fbdeAPI/js/fbdeAPIFunctions.js?schluessel='.$api_key;

		// Die Dateien müssen natürlich nur einmal eingebunden werden
		if(!in_array($fussballAPI, $GLOBALS['TL_JAVASCRIPT'])) {
			$GLOBALS['TL_JAVASCRIPT'][] = $fussballAPI;
			$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fussball_widget/html/fussball-widget.js';
		}
	}
	
	
}

?>