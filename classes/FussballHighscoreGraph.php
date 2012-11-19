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
 * Class FussballHighscoreGraph
 *
 * @copyright  2011-2012 
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

		$js = 'system/modules/fussball_widget/assets/fussball-widget.js';
		if(!is_array($GLOBALS['TL_JAVASCRIPT'])) {
			$GLOBALS['TL_JAVASCRIPT'] = array();
		}
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

