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
 * Class ContentFussballTeam
 *
 * @copyright  Martin Kozianka 2011-2014 <http://kozianka.de/>
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
                $team = (Object) $result->row();
                $team->cssID      = ' id="'.$team->alias.'"';
                $team->class      = $team->alias;
                $this->teamAttributes($team);
                $this->tmplTeam->team = $team;
                $this->Template->team = $this->tmplTeam->parse();
                return;
            }
        }

        $i        = 0;
        $strTeams = '';
        $result   = $this->Database->execute('SELECT * FROM tl_fussball_team ORDER BY name');
        while($result->next()) {
            $team = (Object) $result->row();
            $bgcolorArr = unserialize($team->bgcolor);
            $team->bgcolor  = (is_array($bgcolorArr)) ? '#'.$bgcolorArr[0] : $team->bgcolor;
            $team->cssClass = $team->alias.(($i++ % 2) ? ' odd' : ' even');
            $this->teamAttributes($team);

            $this->teamsArr[$team->id] = $team;
            $this->tmplTeam->team      = $team;
            $strTeams                 .= $this->tmplTeam->parse();
        }
        $this->Template->allTeams    = $strTeams;

        $i        = 0;
        $strTeams = '';
        $result   = $this->Database->prepare("SELECT DISTINCT tl_fussball_matches.team_id AS team_id
            FROM tl_fussball_matches, tl_fussball_team
            WHERE tl_fussball_matches.team_id = tl_fussball_team.id
            AND tl_fussball_matches.anstoss > ? ORDER BY tl_fussball_team.name")
            ->execute(time()) ;

        while ($result->next()) {
            $team                 = $this->teamsArr[$result->team_id];
            $team->cssClass       = $team->alias.(($i++ % 2) ? ' odd' : ' even');
            $this->tmplTeam->team = $team;
            $strTeams            .= $this->tmplTeam->parse();
        }
        $this->Template->activeTeams = $strTeams;
    }

    private function teamAttributes(&$team) {
        $attributes = unserialize($team->team_attributes);
        unset($team->team_attributes);
        $team->attributes = array();
        foreach($attributes as $a) {
            $team->attributes[$a['fussball_ta_key']] = $this->replaceInsertTags($a['fussball_ta_value']);
        }
    }
}