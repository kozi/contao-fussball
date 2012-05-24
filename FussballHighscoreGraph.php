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
 * @author     Martin Kozianka <http://kozianka-online.de>
 * @package    fussball_widget 
 * @license    LGPL 
 * @filesource
 */


/**
 * Class FussballHighscoreGraph
 *
 * @copyright  2011 
 * @author     Martin Kozianka
 * @package    Controller
 */

class FussballHighscoreGraph extends ContentElement {
	protected $strTemplate = 'ce_fussball_highscore_graph';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate() {
		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FUSSBALL HIGHSCORE STATS ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			
			return $objTemplate->parse();
		}
		return parent::generate();
	}

	protected function compile() {
		
		$sizeArr = deserialize($this->Template->size);
		$this->Template->size = array(
			'width'  => $sizeArr[0],
			'height' => $sizeArr[1],
		);
		
		$fp = deserialize($this->Template->fussball_positions);
		$maxsize = 0;
		foreach ($fp as $team) {
			$label[] = $team['key'];
			$posArr = $this->getArray($team['value']);
			if (sizeof($posArr) > $maxsize) {
				$maxsize = sizeof($posArr);
			}
			$data[] = $posArr;
		}
		
		$this->Template->graph_id = uniqid('graph');
		$this->Template->graph_label = $label;
		$this->Template->graph_dataLabel = $this->getDataLabel($maxsize);
		$this->Template->graph_data  = $data;

		$js = 'system/modules/fussball_widget/html/fussball-widget.js';
		// Die Dateien müssen natürlich nur einmal eingebunden werden
		if(!in_array($js, $GLOBALS['TL_JAVASCRIPT'])) {
			$GLOBALS['TL_JAVASCRIPT'][] = $js;
		}
	}

	
	private function getDataLabel($ms) {
		$arr = array();
		for ($i=1;$i <= $ms;$i++) {
			$arr[] = $i.".";
		}
		return $arr;
	}
	private function getArray($str) {
		$arr = array();

		foreach (explode("," , $str) as $el) {
			if (trim($el) != "") {
				$arr[] = intval(trim($el));
			}
		}
		return $arr;
	}
		
}

?>