<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2012 <http://kozianka-online.de/>
 * @author     Martin Kozianka <http://kozianka-online.de/>
 * @package    fussball_widget 
 * @license    LGPL 
 * @filesource
 */

/**
 * Class TournamentRedirect 
 *
 * @copyright  Martin Kozianka 2012
 * @author     Martin Kozianka <http://kozianka-online.de>
 * @package    fussball_widget
 */
class TournamentRedirect extends BackendModule {

	protected function compile() {
		$id  = $GLOBALS['TL_CONFIG']['fussball_tourn_calendar'];
		$do  = 'do=calendar&table=tl_calendar_events&id='.$id;
		$this->redirect($this->addToUrl($do));
	}
	
}

