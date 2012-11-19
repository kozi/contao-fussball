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
			$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fussball_widget/assets/fussball-widget.js';
		}
	}
	
	
}
