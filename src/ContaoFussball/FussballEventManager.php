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
 * Class FussballEventManager
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */

class FussballEventManager extends \System
{
    const ONE_DAY_SEC          = 86400;
    const MATCH_LENGTH_SEC     = 6300;

    private $now        = 0;
    private $arrSearch  = [];
    private $arrReplace = [];

    public function __construct()
    {
        $this->import('Database');
        $this->now     = time();
        foreach(FussballDataManager::$MATCH_TYPES as $type)
        {
            $label              = &$GLOBALS['TL_LANG']['contao_fussball']['match_types'][$type];
            $this->arrSearch[]  = '['.strtoupper($type).']';
            $this->arrReplace[] = '<span class="match_type">[<abbr title="'.$label.'">'.strtoupper($type).'</abbr>]</span>';
        }
        parent::__construct();
    }

    private static function updateCalendarColors()
    {
        $calObj = \CalendarModel::findAll();
        foreach($calObj as $calendar)
        {
            $teamObj = FussballTeamModel::findByPk($calendar->fussball_team_id);

            if ($teamObj !== null && $calendar->fullcal_color !== $teamObj->bgcolor)
            {
                $calendar->fullcal_color = $teamObj->bgcolor;
                $calendar->save();
            }
        }
    }

    public function updateCalendar()
    {
        $this->loadLanguageFile('tl_fussball_tournament');

        // Update calendar color if field is available
        if($this->Database->fieldExists('fullcal_color', 'tl_calendar'))
        {
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
        while($result->next())
        {
            $cal = (Object) $result->row();
            $this->updateCalenderEvents($cal);
        }
    }

    private function updateCalenderEvents($calendar)
    {
        // Get all matches from tl_fussball_match for $calendar->fussball_team_id
        $matchCollection = FussballMatchModel::findBy('pid', $calendar->fussball_team_id);

        if ($matchCollection != null)
        {
            foreach($matchCollection as $match)
            {
                $this->calendarEventMatch($calendar, $match);
            }
        }

        // Get all tournaments from tl_fussball_tournament for $calendar->fussball_team_id
        $tournamentCollection = FussballTournamentModel::findBy('pid', $calendar->fussball_team_id);

        if ($tournamentCollection != null)
        {
            foreach($tournamentCollection as $tournament)
            {
                $this->calendarEventTournament($calendar, $tournament);
            }
        }
    }

    private function calendarEventMatch($calendar, $objMatch)
    {
        $hasErg = (strlen($objMatch->ergebnis) > 0);
        $loc    = str_replace("\n", ' <br>', $objMatch->location);
        $title  = $objMatch->getTitle().(($hasErg) ? ' ('.$objMatch->ergebnis.')': '');

        // Add type to title
        $title .= ' ['.strtoupper($objMatch->typ).']';

        $text  = implode(" <br>", array(
            $title,
            (strlen($objMatch->time) > 0) ? date('d.m.Y H:i', $objMatch->anstoss) : date('d.m.Y', $objMatch->anstoss),
            $loc
        ));

        $calEventModel = \CalendarEventsModel::findOneBy('fussball_matches_id', $objMatch->id);

        if(!$calEventModel)
        {
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
            'addTime'   => (strlen($objMatch->time) > 0) ? '1' : '',
            'startTime' => $objMatch->anstoss,
            'endTime'   => $objMatch->anstoss + static::MATCH_LENGTH_SEC,
            'startDate' => $objMatch->anstoss,
            'endDate'   => NULL,
            'published' => 1,
            'cssClass'  => 'fussball_match fussball_match_'.strtolower($objMatch->typ)
        );

        foreach($eventData as $key => $value)
        {
            $calEventModel->__set($key, $value);
        }
        $calEventModel->save();
    }

    private function calendarEventTournament($calendar, $objTourn)
    {
        $title = $objTourn->title.' [TU]';
        $text  =
            '<span class="title">'.$title.'</span>'
            .'<span class="host"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['host'][0].':</strong> '. $objTourn->host.'</span>'
            .'<span class="confirmed"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['confirmed'][0].':</strong> '.($objTourn->confirmed == '1' ? 'Ja': 'Nein').'</span>'
            .'<span class="location"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['location'][0].':</strong> '.$objTourn->location.'</span>'
            .'<span class="platzart"><strong>'.$GLOBALS['TL_LANG']['tl_fussball_tournament']['platzart'][0].':</strong> '.$objTourn->platzart.'</span>'
            .'<span class="details">'.$objTourn->details.'</span>';

        $calEventModel = \CalendarEventsModel::findOneBy('fussball_tournament_id', $objTourn->id);

        if(!$calEventModel)
        {
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
            'cssClass'  => 'fussball_tournament'
        );

        foreach($eventData as $key => $value)
        {
            $calEventModel->__set($key, $value);
        }

        $calEventModel->save();

    }

    public function eventHook($arrEvents, $arrCalendars, $intStart, $intEnd, $module)
    {
        foreach($arrEvents as $keyOne => &$arrOne)
        {
            foreach($arrOne as $keyTwo => &$arrTwo)
            {
                foreach($arrTwo as &$e)
                {
                    if($e['fussball_matches_id'] != '0' || $e['fussball_tournament_id'] != '0')
                    {
                        // Adjust title add abbr for match type
                        $e['title'] = str_ireplace($this->arrSearch, $this->arrReplace, $e['title']);

                        // Adjust location
                        $locArray      = array_map('trim', explode('<br>', $e['location']));
                        $e['location'] = '';
                        $i = 0;
                        foreach($locArray as $row)
                        {
                            $e['location'] .= '<span class="row'.$i++.'">'.$row.(($i==1||$i==2)? ', ': '').'</span>';
                        }

                        if($e['fussball_matches_id'] != '0')
                        {
                            $e['match'] = FussballMatchModel::findByPk($e['fussball_matches_id']);
                        }
                        else
                        {
                            $e['tournament'] = FussballTournamentModel::findByPk($e['fussball_tournament_id']);
                        }
                    }
                }
            }
        }
        return $arrEvents;
    }




}

