<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
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
/**
 * Class ContentFussballTeam
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball
 */

class ContentFussballTeam extends \ContentElement {
    protected $strTemplate = 'ce_fussball_team';
    private $teamsArr      = array();
    private $tmplTeam      = NULL;


    public function generate() {
        $this->tmplTeam = new \FrontendTemplate($this->fussball_template);
        return parent::generate();
    }

    protected function compile() {

        if ($this->fussball_team_id != '0') {
            $result = $this->Database->prepare('SELECT * FROM tl_fussball_team WHERE id = ?')->limit(1)->execute($this->fussball_team_id);
            if($result->numRows == 1) {
                $team         = (Object) $result->row();
                $team->cssID  = ' id="'.standardize($team->name).'"';
                $team->class  = standardize($team->name);
                $this->teamAttributes($team);
                $this->Template->team = $team;
                return;
            }
        }

        $i           = 0;
        $arrTeams    = array();
        $addCalendar = $this->Database->fieldExists('fullcal_color', 'tl_calendar');
        $result      = $this->Database->execute('SELECT * FROM tl_fussball_team ORDER BY sorting');
        while($result->next()) {
            $team           = (Object) $result->row();
            $bgcolorArr     = unserialize($team->bgcolor);
            $team->bgcolor  = (is_array($bgcolorArr)) ? '#'.$bgcolorArr[0] : $team->bgcolor;
            $team->cssClass = standardize($team->name).(($i++ % 2) ? ' odd' : ' even');
            $this->teamAttributes($team);

            if($addCalendar) {
                $this->addCalendarInfo($team);
            }

            $arrTeams[$team->id] = $team;
        }

        $result   = $this->Database->prepare("SELECT DISTINCT tl_fussball_matches.team_id AS team_id
            FROM tl_fussball_matches, tl_fussball_team
            WHERE tl_fussball_matches.team_id = tl_fussball_team.id
            AND tl_fussball_matches.anstoss > ? ORDER BY tl_fussball_team.sorting")
            ->execute(time()) ;


        while ($result->next()) {
            $arrTeams[$result->team_id]->isActive = true;
        }


        while ($result->next()) {
            $arrTeams[$result->team_id]->isActive = true;
        }
        $this->Template->arrTeams = $arrTeams;
    }

    private function addCalendarInfo(&$team) {
        $calObj = \CalendarModel::findOneBy('fussball_team_id', $team->id);
        if ($calObj) {
            $team->cal = (object) array(
                'name'   => $calObj->name,
                'alias'  => $calObj->ical_alias,
                'color'  => unserialize($calObj->fullcal_color),
                'webcal' => str_replace('http', 'webcal', \Environment::get('url')).'/'.$calObj->ical_alias.'.ics'
            );
        }
    }

    private function teamAttributes(&$team) {
        $attributes = unserialize($team->team_attributes);
        unset($team->team_attributes);
        $team->attributes = array();
        foreach($attributes as $a) {

            $key    = $a['fussball_ta_key'];
            $raw    = $a['fussball_ta_value'];
            $isLink = false;

            if (substr($raw, 0, 7) === 'http://' || substr($raw, 0, 8) === 'https://') {
                $isLink = true;
                $label = str_replace(
                    array('http://www.', 'https://www.', 'http://', 'https:'),
                    array('', '', '', ''),
                    $raw
                );
                $labelShort48 = \String::substr($label, 48, '…');
                $labelShort32 = \String::substr($label, 32, '…');
            }

            $team->attributes[] = (object) array(
                'key'      => $key,
                'raw'      => $raw,
                'value'    => $this->replaceInsertTags($raw),
                'isLink'   => $isLink,
                'label'    => $label,
                'label48'  => $labelShort48,
                'label32'  => $labelShort32
            );
        }
    }
}

