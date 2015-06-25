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

/**
 * Class ContentFussballTournament
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */

class ContentFussballTournament extends \ContentElement {
    protected $strTemplate   = 'ce_fussball_tournament';
    private $teams           = NULL;
    private $team_ids        = array();
    private $now             = 0;
    private $oneDayInSeconds = 86400;

    public function generate() {

        $this->now    = time();
        $this->teams  = array();
        $result       = $this->Database->execute('SELECT id, name FROM tl_fussball_team '
            .'WHERE id IN ('.implode(',', unserialize($this->fussball_team_id_array)).')'
        );

        while($result->next()) {
            $team             = (Object) $result->row();
            $this->teams[]    = $team;
            $teamNames[]      = $team->name;
            $this->team_ids[] = $team->id;

        }


        if (TL_MODE == 'BE') {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### FUSSBALL TOURNAMENT ###<br>'
                .'Teams: '.implode(', ', $teamNames);
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }
        return parent::generate();
    }

    protected function compile() {

        $tournaments = array();
        $db_limit    = 0;

        $result   = $this->Database->prepare('SELECT tl_fussball_tournament.*, tl_fussball_team.name AS team_name, tl_fussball_team.name_short AS team_name_short'
            .' FROM tl_fussball_tournament, tl_fussball_team'
            .' WHERE tl_fussball_tournament.pid IN ('.implode(',', $this->team_ids).')'
            .' AND tl_fussball_tournament.pid = tl_fussball_team.id'
            .' AND tl_fussball_tournament.startDate > '.($this->now - $this->oneDayInSeconds)
            .' ORDER BY tl_fussball_tournament.startDate ASC, tl_fussball_tournament.startTime ASC')
            ->limit($db_limit)->execute($this->fussball_team_id);

        $i = 0;
        while($result->next()) {
            $t = (Object) $result->row();

            $t->date = \Date::parse('D, d.m.y', $t->startDate);
            if (strlen($t->endDate) === 0 || $t->endDate == $t->startDate) {
                if($t->addTime == '1') {
                    // Startzeit hinzufügen
                    $t->date .= \Date::parse(', H:i', $t->startTime);
                    // Endzeit hinzufügen
                    if ($t->startTime < $t->endTime) {
                        $t->date .= \Date::parse('-H:i', $t->endTime);
                    }
                }
            } else {
                if($t->addTime != '1') {
                    $t->date .= \Date::parse(' - D, d.m.y', $t->endDate);
                }
                else {
                    $t->date .= \Date::parse(', H:i', $t->startTime);
                    $t->date .= \Date::parse(' - D, d.m.y', $t->endDate);
                    $t->date .= \Date::parse(', H:i', $t->endTime);
                }
            }

            $t->cssClass       = 'confirmed'.$t->confirmed;
            $t->cssClass      .= ($i++ % 2 == 0) ? ' odd': ' even';


            $tournaments[]    = $t;
        }

        $this->Template->team           = $this->team;
        $this->Template->tournaments    = $tournaments;

    }

}
