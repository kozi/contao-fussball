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
 * Class ContentFussballMatches
 *
 * @copyright  Martin Kozianka 2011-2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */

class ContentFussballMatches extends \ContentElement {
	protected $strTemplate = 'ce_fussball_matches';
    private $team          = null;
    private $now           = 0;
    private $sum_points    = 0;
	private $sum_goals     = array(0, 0);

	public function generate() {

        $result = $this->Database->prepare('SELECT * FROM tl_fussball_team WHERE id = ?')
            ->execute($this->fussball_team_id);

        if ($result->numRows == 1) {
            $this->team = (Object) $result->row();
        }

		if (TL_MODE == 'BE') {
			$objTemplate = new \BackendTemplate('be_wildcard');

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
        global $objPage;

        if ($objPage->isMobile) {
            $this->Template = new \FrontendTemplate('ce_fussball_matches_mobile');
        }

        $this->now      = time();
        $matches_future = array();
        $matches_past   = array();

        $count          = 0;
        $matches_order  = ($this->fussball_order == 'desc') ? 'DESC': 'ASC';
        $db_typ         = '';

        if (strlen($this->fussball_typ) > 0) {
            $db_typ = " AND typ = '".$this->fussball_typ."'";
        }

        // Zukünftige Spiele
        if ($this->fussball_future != '0') {
            $db_limit = intval($this->fussball_future);
            $result   = $this->Database->prepare('SELECT * FROM tl_fussball_matches'
            .' WHERE team_id = ?'.$db_typ.'AND anstoss > '.$this->now.' ORDER BY anstoss ASC')
                ->limit($db_limit)->execute($this->fussball_team_id);

            while($result->next()) {
                $matches_future[] =$this->getMatch($result->row(), $count++);
            }
        }

        // Vergangene Spiele
        if ($this->fussball_past != '0') {
            $db_limit = intval($this->fussball_past);
            $result   = $this->Database->prepare('SELECT * FROM tl_fussball_matches'
            .' WHERE team_id = ?'.$db_typ.'AND anstoss <= '.$this->now.' ORDER BY anstoss DESC')
                ->limit($db_limit)->execute($this->fussball_team_id);


            while($result->next()) {
                $matches_past[] = $this->getMatch($result->row(), $count++);
            }
        }

        $matches = array_merge($matches_future, $matches_past);
        if ($matches_order == 'DESC') {
            usort($matches, function($a, $b) { return ($b->anstoss - $a->anstoss); });
        } else {
            usort($matches, function($a, $b) { return ($a->anstoss - $b->anstoss); });
        }

        // Sort by matches_order
        // $matches = array_merge($matches_past, $matches_future);

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

        $isHome = ($match->heim == $this->team->name_external);
        $arr    = explode(':', $match->ergebnis);

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

