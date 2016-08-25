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

class ContentFussballWidget extends \ContentElement
{
	const FUSSBALL_API     = 'https://www.fussball.de/static/egm//js/widget2.js';
	protected $strTemplate = 'ce_fussball_widget';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
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

	protected function compile()
	{
        // Die Dateien müssen natürlich nur einmal eingebunden werden
        if(!in_array(ContentFussballWidget::FUSSBALL_API, $GLOBALS['TL_JAVASCRIPT']))
		{
            $GLOBALS['TL_JAVASCRIPT'][] = ContentFussballWidget::FUSSBALL_API;
            // $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fussball/assets/fussball-widget.js|static';
        }

        // TODO Add team
        // FussballTeamModel::findByPk($id);
	}
}
