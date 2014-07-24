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
namespace ContaoFussball;

/**
 * Class FussballDataManager
 *
 * @copyright  Martin Kozianka 2011-2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */

class FussballDataManager extends \System {
    const ONE_DAY_SEC      = 86400;
    const MATCH_LENGTH_SEC = 6300;
    const SPIEL_ABGESAGT   = "Abg.";

    private $team_id       = 0;
	private $now           = 0;

	function __construct() {
		$this->now     = time();
		$this->import('Database');
		parent::__construct();
	}

    public function updateCalendar() {
        $this->loadLanguageFile('tl_fussball_tournament');

        // Update calendar color if field is available
        if($this->Database->fieldExists('fullcal_color', 'tl_calendar')) {
            self::updateCalendarColors();
        }

        // Delete all calendar events inserted by fussball extension
        // without matching entry in tl_fussball_matches or tl_fussball_tournament
        $this->Database->execute("DELETE FROM tl_calendar_events
            WHERE fussball_matches_id != 0
            AND fussball_matches_id NOT IN (SELECT id FROM tl_fussball_matches)");

        $this->Database->execute("DELETE FROM tl_calendar_events
            WHERE fussball_tournament_id != 0
            AND fussball_tournament_id NOT IN (SELECT id FROM tl_fussball_tournament)");

        $result = $this->Database->execute("SELECT id, title, fussball_team_id
                    FROM tl_calendar WHERE fussball_team_id != 0");
        while($result->next()) {
            $cal = (Object) $result->row();
            $this->updateCalenderEvents($cal);
        }
    }


    public function updateMatches() {

        // Manuelles Update mit übergebener id
        if ($teamObj = FussballTeamModel::findByPk(\Input::get('id'))) {
            if ($teamObj->team_id) {
                $log     = $this->updateTeamMatches($teamObj) ? "Updated matches for Team <strong>%s (%s, %s)</strong>" : "No matches found for Team <strong>%s (%s, %s)</strong>";
                \Message::add(sprintf($log, $teamObj->name, $teamObj->name_external, $teamObj->team_id), 'TL_INFO');
            }
            else {
                \Message::add('Team <strong>'.$teamObj->name.'</strong> has no <em>Team-ID</em>.', 'TL_INFO');
            }
            \Controller::redirect(\Environment::get('script').'?do=fussball_teams');
        }

        // Suche das Team mit dem ältesten Update-Datum das mindestens 2 Tage alt ist
        $teamObj   = FussballTeamModel::findWithArray(array(
                'column'  => array('tl_fussball_team.lastUpdate < ?'),
                'value'   => $this->now - (2 * static::ONE_DAY_SEC),
                'return'  => 'Model',
                'limit'   =>   1,
                'order'   => 'lastUpdate ASC'
        ));

        // Wenn es ein Team mit "alten Daten" gibt, aktualisiere die Spiele dieses Teams
        if ($teamObj && $teamObj->team_id) {
            $log     = $this->updateTeamMatches($teamObj) ? "Updated matches for Team %s (%s, %s)" : "No matches found for Team %s (%s, %s)";
            $message = sprintf($log, $teamObj->name, $teamObj->name_external, $teamObj->team_id);
            $this->log($message, 'FussballDataManager updateMatches()', TL_CRON);

            if (\Input::get('key') === 'update') {
                \Message::add($message, 'TL_INFO');
                \Controller::redirect(\Environment::get('script').'?do=fussball_teams');
            }
        }
        else if (\Input::get('key') === 'update') {
            \Message::add('Nothing to do!', 'TL_INFO');
            \Controller::redirect(\Environment::get('script').'?do=fussball_teams');
        }

    }

	public function updateTeamMatches(FussballTeamModel $teamObj) {
        $this->Database->prepare('UPDATE tl_fussball_team SET lastUpdate = ? WHERE id = ?')->execute($this->now, $teamObj->id);

        $von    = date("d.m.Y", (time() - (182 * static::ONE_DAY_SEC)));
        $bis    = date("d.m.Y", (time() + (182 * static::ONE_DAY_SEC)));

        $matches = FussballTools::getMatches($teamObj->action_url, $teamObj->team_id, $von, $bis);

        if ($matches === false || (is_array($matches) && count($matches) === 0)) {
            return false;
        }

        foreach ($matches as $match) {
            $this->matchToDb($match, $teamObj->id);
        }
        return true;
	}

    private function updateCalenderEvents($calendar) {
        // Get all matches from tl_fussball_match for $calendar->fussball_team_id
        $result = $this->Database->prepare('SELECT * FROM tl_fussball_matches WHERE team_id = ?')
            ->execute($calendar->fussball_team_id);

        while ($result->next()) {
            $this->calendarEventMatch($calendar, $result->row());
        }

        // Get all tournaments from tl_fussball_tournament for $calendar->fussball_team_id
        $result = $this->Database->prepare('SELECT * FROM tl_fussball_tournament WHERE team_id = ?')
            ->execute($calendar->fussball_team_id);

        while ($result->next()) {
            $this->calendarEventTournament($calendar, $result->row());
        }
    }

    private function calendarEventTournament($calendar, $tournament) {

        $title = $tournament['title'].' [TU]';
        $text  =
             '<span class="title">'.$title.'</span>'
            .'<span class="host"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['host'][0].':</strong> '. $tournament['host'].'</span>'
            .'<span class="confirmed"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['confirmed'][0].':</strong> '.($tournament['confirmed'] == '1' ? 'Ja': 'Nein').'</span>'
            .'<span class="location"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['location'][0].':</strong> '.$tournament['location'].'</span>'
            .'<span class="field_type"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['field_type'][0].':</strong> '.$tournament['field_type'].'</span>'
            .'<span class="details">'.$tournament['details'].'</span>';


        $eventData      = array(
            'fussball_tournament_id' => $tournament['id'],
            'tstamp'    => $this->now,
            'pid'       => $calendar->id,
            'title'     => $title,
            'alias'     => standardize($title.' '.date("d-m-Y", $tournament['startDate'])),
            'teaser'    => $text,
            'location'  => $tournament['location'],
            'addTime'   => $tournament['addTime'],
            'startTime' => $tournament['startTime'],
            'endTime'   => $tournament['endTime'],
            'startDate' => $tournament['startDate'],
            'endDate'   => $tournament['endDate'],
            'published' => 1,
        );

        $result = $this->Database->prepare('SELECT id FROM tl_calendar_events WHERE fussball_tournament_id = ?')
            ->limit(1)->executeUncached($tournament['id']);

        if($result->numRows == 1) {
            // $eventData['id'] = $result->id;
            $calEventModel = \CalendarEventsModel::findByPk($result->id);
        }
        else {
            $calEventModel = new \CalendarEventsModel();

        }
        $calEventModel->setRow($eventData)->save();

    }

    private function calendarEventMatch($calendar, $match) {
        $erg   = (strlen($match['ergebnis']) > 0) ? ' '.$match['ergebnis'] : '';
        $loc   = str_replace("\n", ' <br>', $match['location']);
        $title = $match['heim'].' - '.$match['gast'].' ['.$match['typ'].']'.$erg;
        $text  = implode(" <br>", array(
            $title,
            date('d.m.Y H:i', $match['anstoss']),
            $loc,
            (strlen($erg) > 0) ?  'Ergebnis:'.$erg : ''
        ));

        $eventData      = array(
            'fussball_matches_id' => $match['id'],
            'tstamp'    => $this->now,
            'pid'       => $calendar->id,
            'title'     => $title,
            'alias'     => standardize($title.' '.date("d-m-Y", $match['anstoss'])),
            'teaser'    => $text,
            'location'  => $loc,
            'addTime'   => 1,
            'startTime' => $match['anstoss'],
            'endTime'   => $match['anstoss'] + static::MATCH_LENGTH_SEC,
            'startDate' => $match['anstoss'],
            'endDate'   => NULL,
            'published' => 1,
        );

        $result = $this->Database->prepare('SELECT id FROM tl_calendar_events WHERE fussball_matches_id = ?')
            ->limit(1)->executeUncached($match['id']);

        if($result->numRows == 1) {
            // $eventData['id'] = $result->id;
            $calEventModel = \CalendarEventsModel::findByPk($result->id);
        }
        else {
            $calEventModel = new \CalendarEventsModel();

        }
        $calEventModel->setRow($eventData)->save();


    }

	private function matchToDb($match, $team_id) {
		$dbMatch = array(
			'tstamp'        => $this->now,
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

        if (static::SPIEL_ABGESAGT === $match['erg']) {
            // WENN DAS SPIEL ABGESAGT WURDE WIRD ES GELÖSCHT UND NICHT WIEDER EINGEFÜGT
            $this->Database->prepare('DELETE FROM tl_fussball_matches WHERE spielkennung = ?')
                ->execute($dbMatch['spielkennung']);
        }
		else if ($result->numRows == 0) {
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

    private static function updateCalendarColors() {
        $calObj = \CalendarModel::findAll();
        foreach($calObj as $calendar) {
            $teamObj = FussballTeamModel::findByPk($calendar->fussball_team_id);

            if ($teamObj !== null && $calendar->fullcal_color !== $teamObj->bgcolor) {
                $calendar->fullcal_color = $teamObj->bgcolor;
                $calendar->save();
            }

        }
    }

    public function sorting() {
        $id    = \Input::get('id');
        $up    = \Input::get('sort') === 'up';
        $count = 1;
        $teams = FussballTeamModel::findAll(array('order' => 'sorting ASC'));
        foreach ($teams as $teamObj) {
            if ($teamObj->id == $id) {
                    $teamObj->sorting = ($up) ? (($count-1) * 16)-1 : ($count * 16)+1;
            }
            else {
                $teamObj->sorting = ($count++ * 16);
            }
            $teamObj->save();
        }
        \Controller::redirect(\Environment::get('script').'?do=fussball_teams');
    }

}

