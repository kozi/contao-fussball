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
namespace ContaoFussball;

/**
 * Class FussballDataManager
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */

class FussballDataManager extends \System {

    public static $FIELD_TYPES = [
        "Asche",
        "Kunstrasen",
        "Halle",
        "Kunstrasen (Halle)",
        "Rasen",
    ];

    public static $MATCH_TYPES = ['me', 'fs', 'po'];
    const ONE_DAY_SEC          = 86400;
    const MATCH_LENGTH_SEC     = 6300;
	private $now               = 0;

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
        // without matching entry in tl_fussball_match or tl_fussball_tournament
        $this->Database->execute("DELETE FROM tl_calendar_events
            WHERE fussball_matches_id != 0
            AND fussball_matches_id NOT IN (SELECT id FROM tl_fussball_match)");

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

    private function updateCalenderEvents($calendar) {
        // Get all matches from tl_fussball_match for $calendar->fussball_team_id
        $result = $this->Database->prepare('SELECT * FROM tl_fussball_match WHERE pid = ?')
            ->execute($calendar->fussball_team_id);

        while ($result->next()) {
            $this->calendarEventMatch($calendar, $result->row());
        }

        // Get all tournaments from tl_fussball_tournament for $calendar->fussball_team_id
        $result = $this->Database->prepare('SELECT * FROM tl_fussball_tournament WHERE pid = ?')
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

        $calEventModel = \CalendarEventsModel::findOneBy('fussball_tournament_id', $tournament['id']);

        if(!$calEventModel) {
            $calEventModel = new \CalendarEventsModel();
        }

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

        foreach($eventData as $key => $value) {
            $calEventModel->__set($key, $value);
        }

        $calEventModel->save();

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

        $calEventModel = \CalendarEventsModel::findOneBy('fussball_matches_id', $match['id']);

        if(!$calEventModel) {
            $calEventModel = new \CalendarEventsModel();
        }

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

        foreach($eventData as $key => $value) {
            $calEventModel->__set($key, $value);
        }
        $calEventModel->save();
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

    public function matchResult() {
        $matchId  = \Input::get('match');
        $result   = \Input::get('result');
        $ergebnis = '';
        if ($result && $matchId) {
            $this->Database->prepare("UPDATE tl_fussball_match SET ergebnis = ? WHERE id = ?")
                ->execute($result, $matchId);
        }

        $result = $this->Database->prepare("SELECT ergebnis FROM tl_fussball_match WHERE id = ?")
            ->execute($matchId);
        if ($result->numRows === 1) {
            $ergebnis = $result->ergebnis;
        }

        header('HTTP/1.0 200 OK');
        echo $ergebnis;
        exit;
    }
}
