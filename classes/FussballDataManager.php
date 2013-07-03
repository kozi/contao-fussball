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
 * Class FussballDataManager
 *
 * @copyright  Martin Kozianka 2011-2013 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */


// Required for FussballTools
require_once(TL_ROOT.'/system/modules/fussball_widget/classes/simple_html_dom.php');

class FussballDataManager extends System {
	private $team_id       = 0;
	private $now           = 0;
	private $matches       = array();
	private $oneDayInSec   = 86400;

	function __construct() {
		$this->now = time();
		$this->import('Database');
		parent::__construct();
	}

    public function updateMatches() {

        // Suche das Team mit dem ältesten Update-Datum das mindestens 2 Tage alt ist
        $timestamp = $this->now - (2 * $this->oneDayInSec);
        $result    = $this->Database->prepare('SELECT * FROM tl_fussball_team WHERE lastUpdate < ? ORDER BY lastUpdate ASC')
            ->limit(1)->execute($timestamp);

        // Wenn es ein Team mit "alten Daten" gibt, aktualisiere die Spiele dieses Teams
        if ($result->numRows !== 0) {

            $teamObj = (Object) $result->row();
            $log     = $this->updateTeamMatches($teamObj) ? "Updated matches for Team %s (%s, %s)" : "No matches found for Team %s (%s, %s)";

            $this->log(sprintf($log, $teamObj->name, $teamObj->id_mannschaft, $teamObj->id_verein),
                'FussballDataManager updateMatches()', TL_CRON);
        }

    }

	public function updateTeamMatches($teamObj) {
        $this->Database->prepare('UPDATE tl_fussball_team SET lastUpdate = ? WHERE id = ?')->execute($this->now, $teamObj->id);

        $von    = date("d.m.Y", (time() - (182 * $this->oneDayInSec)));
        $bis    = date("d.m.Y", (time() + (182 * $this->oneDayInSec)));

        $matches = FussballTools::getMatches($teamObj->action_url, $teamObj->team_id, $von, $bis);

        if ($matches === false || (is_array($matches) && count($matches) === 0)) {
            return false;
        }

        foreach ($matches as $match) {
            $this->matchToDb($match, $teamObj->id);
        }
        return true;
	}

	private function matchToDb($match, $team_id) {
		$dbMatch = array(
			'tstamp'        => time(),
			'spielkennung'  => $match['kennung'].'-'.str_replace(' ', '-', $match['id']),
			'team_id'       => $team_id,
			'anstoss'       => FussballTools::getTimestampFromDateAndTimeString($match['date'], $match['time']),
			'heim'          => $match['manh'],
			'gast'          => $match['mana'],
			'typ'           => $match['typ'],
			'location'      => ($match['loc'] !== null) ? $match['loc'] : '',
            'ergebnis'      => $match['erg'],
			'spielklasse'   => $match['klasse'],

		);

        $result = $this->Database->prepare('SELECT * FROM tl_fussball_matches WHERE spielkennung = ?')
			->execute($dbMatch['spielkennung']);

		if ($result->numRows == 0) {
			// INSERT
            $this->Database->prepare('INSERT INTO tl_fussball_matches %s')->set($dbMatch)->execute();
		}
		else {
			// UPDATE
            $currentRow   = $result->row();
            $spielkennung = $dbMatch['spielkennung'];
            unset($dbMatch['spielkennung']);

            if (strlen($currentRow['ergebnis']) > 0) {
                // Es ist schon ein Ergebnis eingetragen.
                unset($dbMatch['ergebnis']);
            }

            $this->Database->prepare('UPDATE tl_fussball_matches %s WHERE spielkennung = ?')
                ->set($dbMatch)->execute($spielkennung);
		}
	}

}

