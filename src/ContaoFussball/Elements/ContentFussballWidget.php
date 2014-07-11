<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball
 * @license    LGPL
 * @filesource
 */
namespace ContaoFussball\Elements;

/**
 * Class ContentFussballWidget
 *
 * @copyright  Martin Kozianka 2011-2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */

class ContentFussballWidget extends \ContentElement {
	protected $strTemplate = 'ce_fussball_widget';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate = new \BackendTemplate('be_wildcard');

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
        global $objPage;

        // Get API-Key from root page
        $objRootPage = \PageModel::findByPk($objPage->rootId);
        $this->addJavascriptFiles($objRootPage->fussball_api_key);

        // Get external team name
        $result = $this->Database->prepare("SELECT * FROM tl_fussball_team WHERE id = ?")->execute($this->fussball_team_id);
        if ($result->numRows === 1) {
            $this->Template->fussball_team = $result->name_external;
        }

	}

	protected function addJavascriptFiles($api_key) {
		$fussballAPI = 'http://ergebnisdienst.fussball.de/static/egm//js/egmWidget.js?schluessel='.$api_key;

		// Die Dateien müssen natürlich nur einmal eingebunden werden
		if(!in_array($fussballAPI, $GLOBALS['TL_JAVASCRIPT'])) {
			$GLOBALS['TL_JAVASCRIPT'][] = $fussballAPI;
			$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fussball_widget/assets/fussball-widget.js|static';
		}
	}
	
	
}
