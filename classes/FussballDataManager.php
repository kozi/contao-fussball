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

class FussballDataManager extends System {
	private $days          = array('Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag');
	private $posturl       = "http://ergebnisdienst.fussball.de/api/fbed/fbedVereinsspielplan.php";
	private $team_id       = 0;
	private $now           = 0;
	private $matches       = array();
	private $oneDayInSec   = 86400;

	function __construct() {
		$this->now = time();
		$this->import('Database');
		parent::__construct();
	}
	public function updateMatches($team_id) {

		$result = $this->Database->prepare('SELECT * FROM tl_fussball_team WHERE id = ?')
			->execute($team_id);

		if ($result->numRows == 0) {
			return false;
		}
        $teamObj = (Object) $result->row();


		if (($teamObj->lastUpdate + (2 * $this->oneDayInSec)) > $this->now) {
			// Only Update if last Update is minimum 2 days ago
			return false;
		}
		else {
            $matches = $this->getExternalData($teamObj->id_mannschaft, $teamObj->id_verein);

            if ($matches === false) {
                return false;
            }

			foreach ($matches as $match) {
				$this->matchToDb($match, $team_id);
			}
			$result  = $this->Database->prepare('UPDATE tl_fussball_team SET lastUpdate = ? WHERE id = ?')
				->execute(time(), $team_id);
            return true;
		}
	}

	private function matchToDb($match, $team_id) {

		$dbMatch = array(
			'tstamp'       => time(),
			'spielkennung' => $match[spieljahr].'-'.str_replace(' ', '-', $match[kennung]),
			'team_id'      => $team_id,
			'anstoss'      => $this->strDateToTimestamp($match[date], $match[time]),
			'heim'         => $match[heim],
			'gast'         => $match[gast],
			'typ'          => $match[typ],
			'location'     => ($match[loc] !== null) ? $match[loc] : '',
            'ergebnis'     => $match[erg],
			// 'spielklasse'  => $match[spielklasse],

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


	private function getExternalData($mannschaft, $verein) {
		$von    = date("d.m.Y", (time() - (182 * $this->oneDayInSec)));
		$bis    = date("d.m.Y", (time() + (182 * $this->oneDayInSec)));

		$postvars = "edAustragungsort0=0&edAustragungsort1=&edAustragungsort2="
			."&edDatumBis=".$bis
			."&edDatumVon=".$von
			."&edMannschaftChk0=&edMannschaftChk1=1&edMannschaften="
			."%7B%22".$mannschaft."%22%3A%20"."1"."%7D"
			."&edSpielstaette=1&edVereinId=".$verein;

		$ch = curl_init($this->posturl);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        if ($data === null) {
            return false;
        }

        $data = '<?xml version="1.0" encoding="utf-8"?>'
            .'<html><head><title>Cal</title>'
            .'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'
            .'</head><body>'.$data.'</body></html>';

		$dom  = new DOMDocument('1.0', 'UTF-8');
		$dom->loadHTML($data);
		$dom->preserveWhiteSpace = false;

		$tables = $dom->getElementsByTagName('table');
		if ($tables->length === 0) { $this->error = "Kein table-Tag gefunden."; return false; }
		
		$rows = $tables->item(0)->getElementsByTagName('tr');
		if ($rows->length === 0) { $this->error = "Kein tr-Tag gefunden.";  return false; } 
		
		$matches = array(); $match = array();
		foreach ($rows as $row) {

			$cols = $row->getElementsByTagName('td');
			if ($cols->length > 4) {

				// Zeile mit Mannschaften und Uhrzeit
				$aTag = $cols->item(0)->getElementsByTagName('a');
				if ($aTag->length === 1) {
					$spieljahr = $aTag->item(0)->getAttribute('href');
					$spieljahr = preg_replace("#http://ergebnisdienst.fussball.de/.*/spieljahr#" , "" , $spieljahr);
					$spieljahr = preg_replace("#/.*#" , "" , $spieljahr);
					$match['spieljahr'] = $spieljahr;
				}

				$match['kennung'] = $cols->item(0)->nodeValue;
				$match['heim']    = $cols->item(1)->nodeValue;
				$match['gast']    = $cols->item(2)->nodeValue;
				$match['time']    = $cols->item(3)->nodeValue;
				$match['erg']     = trim( str_replace(" *", "", $cols->item(4)->nodeValue) );

				
				if ($match['erg'] === $this->strAbgesagt) {
					$match['loc'] = 'Spiel abgesagt.';
				}

				if ($cols->length > 5) {
					$match['typ'] = $cols->item(6)->nodeValue;
				}

			}
			else {
				for ($i=0;$i<$cols->length; $i++) {
					$val = $cols->item($i)->nodeValue;
					
					if ($this->isDate($val)) {
						if (sizeof($match) > 0) {
							$matches[] = $match;
						}
						$match = array();
						$match['date'] = $val;
					}
					else if (!array_key_exists('loc',$match) && ((strpos($val,"Sportplatz")!==false) || (strpos($val," // ")!==false)) ) {

						$val = preg_replace("/[^a-zA-Z0-9_äöüÄÖÜß \/]/" , "" , $val);

						$arr = explode(" // ", $val);
						if (count($arr) === 3 ) {
							// Der erste Wert ist nur der Name des Sportplatzes
							$val = implode(", ", array_slice($arr,1));
						}
						$match['loc'] = $val;
					}
		
				} // for
		
			} // else
		
		} //foreach

		// Das letzte Spiel muss auch noch mit
		if (sizeof($match) > 0) {
			$matches[] = $match;
		}

		return $matches;
	}


	private function isDate($str = '') {
		if (strlen($str) === 0) return false;

		foreach($this->days as $day) {
			if (strpos($str, $day) !== false) {
				return true;
			}
		}
		return false;
	}

	private function strDateToTimestamp($dateStr, $timeStr) {
		$dateStr = str_replace($this->days, array('','','','','','',''), $dateStr);
		$dateStr = str_replace(array(',', ' '), array('',''), $dateStr);

		$tmp     = array_reverse(explode('.', $dateStr));
		$dateStr = implode('-', $tmp).' '.$timeStr.':00';

		return strtotime($dateStr);
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



