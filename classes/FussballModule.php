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
 * Class FussballModule 
 *
 * @copyright  Martin Kozianka 2012
 * @author     Martin Kozianka <http://kozianka-online.de>
 * @package    fussball_widget
 */
class FussballModule extends BackendModule {

	protected function compile() {
		$id  = $GLOBALS['TL_CONFIG']['fussball_tourn_calendar'];
		$do  = 'do=calendar&table=tl_calendar_events&id='.$id;
		$this->redirect($this->addToUrl($do));
	}
	
	public function sortGoalGetterEntries($var, $dc) {
		$arr = unserialize($var);

		function usort_sortGoalGetterEntries($a, $b) {
			$res = intval($a['fussball_gg_goals']) - intval($b['fussball_gg_goals']); 
			if ($res == 0) return strcmp($a['fussball_gg_name'], $b['fussball_gg_name']);
			if ($res >  0) return -1;
			return 1;
		}
		
		usort($arr, 'usort_sortGoalGetterEntries');
		return serialize($arr);
	}
	
}

