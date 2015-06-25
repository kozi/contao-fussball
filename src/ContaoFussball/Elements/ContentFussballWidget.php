<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2015 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball
 * @license    LGPL
 * @filesource
 */
namespace ContaoFussball\Elements;

/**
 * Class ContentFussballWidget
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */

class ContentFussballWidget extends \ContentElement {
    const FUSSBALL_API = 'http://www.fussball.de/static/egm//js/widget2.js';
	protected $strTemplate = 'ce_fussball_widget';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate           = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### FUSSBALL WIDGET ###';
			$objTemplate->title    = $this->headline;
			$objTemplate->id       = $this->id;
			$objTemplate->link     = $this->name;
			$objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			return $objTemplate->parse();
		}
		return parent::generate();
	}

	protected function compile() {

        // Die Dateien müssen natürlich nur einmal eingebunden werden
        if(!in_array(ContentFussballWidget::FUSSBALL_API, $GLOBALS['TL_JAVASCRIPT'])) {
            $GLOBALS['TL_JAVASCRIPT'][] = ContentFussballWidget::FUSSBALL_API;
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fussball/assets/fussball-widget.js|static';
        }

        // Get external team name
        $result = $this->Database->prepare("SELECT * FROM tl_fussball_team WHERE id = ?")->execute($this->fussball_team_id);
        if ($result->numRows === 1) {
            $this->Template->fussball_team = $result->name_external;
        }

	}
    /*
    <div id="widget1"></div>
    <script type="text/javascript">
        new fussballdeWidgetAPI().showWidget('widget1', '01O99IGINC000000VS541L4GVVT6PI5S');
    </script>
     */
}
