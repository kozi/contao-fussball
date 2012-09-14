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
 * Class FussballResultsContent
 *
 * @copyright  2011-2012 
 * @author     Martin Kozianka
 * @package    Controller
 */

class FussballResultsContent extends ContentElement {
	protected $strTemplate = 'ce_fussball_results';
	private $sum_points = 0;
	private $sum_goals  = array(0, 0);
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate() {
		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FUSSBALL RESULTS ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			
			return $objTemplate->parse();
		}
		return parent::generate();
	}

	protected function compile() {
		$rs       = deserialize($this->Template->fussball_results);
		$results  = array();
		$team     = $this->fussball_team;
		$matchday = 1;
		foreach($rs as $r) {

			if (strlen($r['fussball_opponent']) > 0) {
				
				$result = new stdClass;
				$result->matchday = $matchday++.'.';
				$result->is_home  = ($r['fussball_is_homematch'] == '1');
				$result->home     = ($result->is_home) ? $team : $r['fussball_opponent'];
				$result->away     = ($result->is_home) ? $r['fussball_opponent'] : $team;
				$result->result   = $r['fussball_result'];
				$result->points   = $this->getPoints($result);

				$result->css      = ($result->is_home) ? 'home' : 'away';
				$result->css     .= ($matchday % 2 == 0) ? ' odd' : ' even';
				
				if (strlen($result->result) > 0) {
					$result->css     .= ($result->points == 1)  ? ' draw' : '';
					$result->css     .= ($result->points == 3)  ? ' victory' : ' defeat';
				}
				$results[] = $result;
			}
		}
		
		$this->Template->sum_points = $this->sum_points;
		$this->Template->sum_goals  = $this->sum_goals;
		$this->Template->results    = $results;
		$this->Template->team       = $team;
		
	}
	
	private function getPoints($r) {
		$arr = array_map('trim', explode(':', $r->result));
		$h = $arr[0];
		$a = $arr[1];
		$home = ($h > $a);
		
		if (strlen($h) == 0 || strlen($a) == 0) {
			return 0;
		}
			
		if ($h == $a) {
			$p = 1;
		} elseif (($home && $r->is_home) || (!$home && !$r->is_home)) {
			$p = 3;
		} else {
			$p = 0;
		}

		$this->sum_points   += $p;
		
		if ($r->is_home) {
			$this->sum_goals[0] += $h;
			$this->sum_goals[1] += $a;
		} else {
			$this->sum_goals[0] += $a;
			$this->sum_goals[1] += $h;
		}
		
		return $p;
	}
}

