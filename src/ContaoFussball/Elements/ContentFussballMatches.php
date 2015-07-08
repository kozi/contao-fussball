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

use ContaoFussball\Models\FussballMatchModel;
use ContaoFussball\Models\FussballTeamModel;
use ContaoFussball\Models\FussballVereinModel;

/**
 * Class ContentFussballMatches
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
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


        $objTeam = FussballTeamModel::findByPk($this->fussball_team_id);
        if ($objTeam != null) {
            $this->team = (Object) $objTeam->row();
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

        $this->now      = time();
        $matches_future = [];
        $matches_past   = [];

        $arrWhere  = [
            'pid'     => 'pid = ?',
            'anstoss' => 'anstoss > ?'
        ];
        $arrValues = [
            'pid'     => $this->fussball_team_id,
            'anstoss' => $this->now
        ];

        if (strlen($this->fussball_typ) > 0) {
            $arrWhere['typ']  = 'typ = ?';
            $arrValues['typ'] = $this->fussball_typ;
        }

        // Zukünftige Spiele
        if ($this->fussball_future != '0') {
            $options = ['limit' => intval($this->fussball_future), 'order' => 'anstoss ASC'];
            $arrWhere['anstoss']  = 'anstoss > ?';

            $matchesCollection = FussballMatchModel::findBy($arrWhere, $arrValues, $options);
            if ($matchesCollection != null) {
                foreach($matchesCollection as $objMatch) {
                    $matches_future[] =$this->getMatch($objMatch);
                }
            }
        }

        // Vergangene Spiele
        if ($this->fussball_past != '0') {
            $options     = ['limit' => intval($this->fussball_future), 'order' => 'anstoss DESC'];
            $arrWhere['anstoss']  = 'anstoss <= ?';

            $matchesCollection = FussballMatchModel::findBy($arrWhere, $arrValues, $options);
            if ($matchesCollection != null) {
                foreach($matchesCollection as $objMatch) {
                    $matches_past[] =$this->getMatch($objMatch);
                }
            }
        }

        $matches = array_merge($matches_future, $matches_past);

        // Sort by matches_order
        if ($this->fussball_order == 'desc') {
            usort($matches, function($a, $b) { return ($b->anstoss - $a->anstoss); });
        } else {
            usort($matches, function($a, $b) { return ($a->anstoss - $b->anstoss); });
        }

		$this->Template->sum_points = $this->sum_points;
		$this->Template->sum_goals  = $this->sum_goals;
		$this->Template->matches    = $matches;
		$this->Template->team       = $this->team;

	}

    private function getMatch($match) {

        $match->team          = $match->getRelated('pid');
        $match->title         = $match->getTitle();

        $match->verein        = FussballVereinModel::findOneBy('home', '1');;
        $match->verein_gegner = $match->getRelated('verein_gegner');

        $arrWappen = [null, null];
        // Wappen holen
        if ($match->verein !== null) {
            $objFile = \FilesModel::findByUuid($match->verein->wappen);
            $match->wappen = ($objFile !== null) ? $objFile->path : null;
            $index = ($match->isHeimspiel()) ? 0 : 1;
            $arrWappen[$index] = $match->wappen;
        }
        if ($match->verein_gegner !== null) {
            $objFile = \FilesModel::findByUuid($match->verein_gegner->wappen);
            $match->wappen_gegner = ($objFile !== null) ? $objFile->path : null;
            $index = ($match->isHeimspiel()) ? 1 : 0;
            $arrWappen[$index] = $match->wappen_gegner;
        }

        $match->hasWappen = ($arrWappen[0] !== null && $arrWappen[1] !== null);
        $match->arrWappen = $arrWappen;

        // In der Vergangenheit?
        $match->inPast = ($match->anstoss < $this->now);

        // Punkte berechnen
        $match->points = $this->getPoints($match);

        // Datum formatieren
        $match->datum     = \Date::parse('D, d.m.y', $match->anstoss);
        $match->datum    .= (strlen($match->time) > 0) ? \Date::parse(' H:i', $match->time) : '';

        $match->cssClass  =  'match points'.$match->points;
        $match->cssClass .=  $match->inPast ? ' past' : ' future';

        $match->typ_icon =  'match_typ_'.standardize($match->typ).'.png';

        return $match;
    }

    private function getPoints($match) {

        if (!$match->inPast) {
            return -1;
        }

        $isHome = ($match->isHeimspiel() == '1');
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

