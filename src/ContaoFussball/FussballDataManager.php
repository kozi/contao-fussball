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

use ContaoFussball\Models\FussballMatchModel;
use ContaoFussball\Models\FussballTeamModel;
use ContaoFussball\Models\FussballTournamentModel;

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
        $matchCollection = FussballMatchModel::findBy('pid', $calendar->fussball_team_id);

        if ($matchCollection != null) {
            foreach($matchCollection as $match) {
                $this->calendarEventMatch($calendar, $match);
            }
        }

        // Get all tournaments from tl_fussball_tournament for $calendar->fussball_team_id
        $tournamentCollection = FussballTournamentModel::findBy('pid', $calendar->fussball_team_id);

        if ($tournamentCollection != null) {
            foreach($tournamentCollection as $tournament) {
                $this->calendarEventTournament($calendar, $tournament);
            }
        }
    }

    private function calendarEventTournament($calendar, $objTourn) {

        $title = $objTourn->title.' [TU]';
        $text  =
             '<span class="title">'.$title.'</span>'
            .'<span class="host"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['host'][0].':</strong> '. $objTourn->host.'</span>'
            .'<span class="confirmed"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['confirmed'][0].':</strong> '.($objTourn->confirmed == '1' ? 'Ja': 'Nein').'</span>'
            .'<span class="location"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['location'][0].':</strong> '.$objTourn->location.'</span>'
            .'<span class="field_type"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['field_type'][0].':</strong> '.$objTourn->field_type.'</span>'
            .'<span class="details">'.$objTourn->details.'</span>';

        $calEventModel = \CalendarEventsModel::findOneBy('fussball_tournament_id', $objTourn->id);

        if(!$calEventModel) {
            $calEventModel = new \CalendarEventsModel();
        }

        $eventData      = array(
            'fussball_tournament_id' => $objTourn->id,
            'tstamp'    => $this->now,
            'pid'       => $calendar->id,
            'title'     => $title,
            'alias'     => standardize($title.' '.date("d-m-Y", $objTourn->startDate)),
            'teaser'    => $text,
            'location'  => $objTourn->location,
            'addTime'   => $objTourn->addTime,
            'startTime' => $objTourn->startTime,
            'endTime'   => $objTourn->endTime,
            'startDate' => $objTourn->startDate,
            'endDate'   => $objTourn->endDate,
            'published' => 1,
        );

        foreach($eventData as $key => $value) {
            $calEventModel->__set($key, $value);
        }

        $calEventModel->save();

    }

    private function calendarEventMatch($calendar, $objMatch) {
        $title = $objMatch->getTitle();
        $erg   = (strlen($objMatch->ergebnis) > 0) ? ' '.$objMatch->ergebnis : '';
        $loc   = str_replace("\n", ' <br>', $objMatch->location);

        $text  = implode(" <br>", array(
            $title,
            date('d.m.Y H:i', $objMatch->anstoss),
            $loc,
            (strlen($erg) > 0) ?  'Ergebnis:'.$erg : ''
        ));

        $calEventModel = \CalendarEventsModel::findOneBy('fussball_matches_id', $objMatch->id);

        if(!$calEventModel) {
            $calEventModel = new \CalendarEventsModel();
        }

        $eventData      = array(
            'fussball_matches_id' => $objMatch->id,
            'tstamp'    => $this->now,
            'pid'       => $calendar->id,
            'title'     => $title,
            'alias'     => standardize($title.' '.date("d-m-Y", $objMatch->anstoss)),
            'teaser'    => $text,
            'location'  => $loc,
            'addTime'   => 1,
            'startTime' => $objMatch->anstoss,
            'endTime'   => $objMatch->anstoss + static::MATCH_LENGTH_SEC,
            'startDate' => $objMatch->anstoss,
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
        $strErg   = '';
        $result   = static::cleanupResult(\Input::get('result'));
        $objMatch = FussballMatchModel::findByPk(\Input::get('match'));

        if ($objMatch != null && preg_match('/^(\d{1,4}):(\d{1,4})$/', $result)) {
            $objMatch->ergebnis = $result;
            $objMatch->save();
            $objMatch->refresh();
            $strErg = $objMatch->ergebnis;
        }

        header('HTTP/1.0 200 OK');
        echo $strErg;
        exit;
    }

    public static function cleanupResult($result) {
        // Check for correct value!
        $divider = ':';
        $t = preg_replace ('/[^0-9]/',' ', $result);
        $t = preg_replace ('/\s+/', $divider, $t);

        if (strlen($t) < 3) {
            return '';
        }

        $tmp = explode($divider, $t);

        if(strlen($tmp[0]) < 1 && strlen($tmp[1]) < 1) {
            return '';
        }
        $h = intval($tmp[0], 10);
        $a = intval($tmp[1], 10);
        return $h.$divider.$a;
    }
}
