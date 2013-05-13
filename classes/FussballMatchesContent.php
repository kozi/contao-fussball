<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2013 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball_widget
 * @license    LGPL
 * @filesource
 */


/**
 * Class FussballMatchesContent
 *
 * @copyright  Martin Kozianka 2011-2013 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */

class FussballMatchesContent extends ContentElement {
	protected $strTemplate = 'ce_fussball_matches';
    private $team          = null;
    private $now           = 0;
    private $sum_points    = 0;
	private $sum_goals     = array(0, 0);
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate() {

        $result = $this->Database->prepare('SELECT * FROM tl_fussball_team WHERE id = ?')
            ->execute($this->fussball_team_id);

        if ($result->numRows == 1) {
            $this->team = (Object) $result->row();
        }

		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FUSSBALL MATCHES ###<br>Team: '.$this->team->name;
			$objTemplate->title    = $this->headline;
			$objTemplate->id       = $this->id;
			$objTemplate->link     = $this->name;
			$objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			
			return $objTemplate->parse();
		}
		return parent::generate();
	}

	protected function compile() {


		$this->import('FussballDataManager');
		$this->FussballDataManager->updateMatches($this->fussball_team_id);

        $this->now = time();
        $matches_future = array();
        $matches_past   = array();


        $count          = 0;
        $db_order       = ($this->fussball_order == 'desc') ? 'DESC': 'ASC';
        $db_typ         = '';

        if (strlen($this->fussball_typ) > 0) {
            $db_typ = " AND typ = '".$this->fussball_typ."'";
        }

        // Zukünftige Spiele
        $db_limit = ($this->fussball_future == '') ? 0 : $this->fussball_future;
        $result   = $this->Database->prepare('SELECT * FROM tl_fussball_matches'
            .' WHERE team_id = ?'.$db_typ.'AND anstoss > '.$this->now.' ORDER BY anstoss '.$db_order)
            ->limit($db_limit)->execute($this->fussball_team_id);

        while($result->next()) {
            $matches_future[] =$this->getMatch($result->row(), $count++);
        }

        // Vergangene Spiele
        $db_limit = ($this->fussball_future == '') ? 0 : $this->fussball_future;
        $result   = $this->Database->prepare('SELECT * FROM tl_fussball_matches'
            .' WHERE team_id = ?'.$db_typ.'AND anstoss <= '.$this->now.' ORDER BY anstoss '.$db_order)
            ->limit($db_limit)->execute($this->fussball_team_id);


        while($result->next()) {
            $matches_past[] = $this->getMatch($result->row(), $count++);
        }

        if ($db_order === 'ASC') {
            $matches = array_merge($matches_past, $matches_future);
        }
        else {
            $matches = array_merge($matches_future, $matches_past);
        }


		$this->Template->sum_points = $this->sum_points;
		$this->Template->sum_goals  = $this->sum_goals;
		$this->Template->matches    = $matches;
		$this->Template->team       = $this->team;
		
	}

    private function getMatch($row, $count) {
        $match     = (Object) $row;

        // In der Vergangenheit?
        $match->inPast = ($match->anstoss < $this->now);
        // Punkte berechnen
        $match->points = $this->getPoints($match);
        // Datum formatieren

        $match->datum = Date::parse('D, d.m.y H:i', $match->anstoss);

        $match->cssClass  =  'match points'.$match->points;
        $match->cssClass .=  $match->inPast ? ' past' : ' future';

        $match->typ_icon =  'match_typ_'.standardize($match->typ).'.png';

        return $match;
    }

    private function getPoints($match) {

        if (!$match->inPast) {
            return -1;
        }

        if ($match->heim == $this->team->name_external) {
            $isHome = true;
        }

        $arr  = explode(':', $match->ergebnis);

        if (count($arr) !== 2) {
            return -1;
        }

        $score_h = intval($arr[0]);
        $score_g = intval($arr[1]);

        if ($score_h === $score_g) {
            return 1;
        }

        if (($score_h > $score_g) && $isHome) {
            return 3;
        }

        if (($score_h < $score_g) && !$isHome) {
            return 3;
        }

        return 0;
    }
}

