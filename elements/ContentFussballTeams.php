<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * Class ContentFussballTeams
 *
 * @copyright  Martin Kozianka 2011-2013 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball_widget
 */

class ContentFussballTeams extends ContentElement {
	protected $strTemplate = 'ce_fussball_teams';
    private $allTeams    = array();
    private $activeTeams = array();
	
	public function generate() {
        // TODO template Option
		return parent::generate();
	}
			
	protected function compile() {
        $i = 0;
        $result = $this->Database->execute('SELECT * FROM tl_fussball_team ORDER BY name');
        while($result->next()) {
            $team = (Object) $result->row();
            $bgcolorArr = unserialize($team->bgcolor);
            $team->bgcolor  = (is_array($bgcolorArr)) ? '#'.$bgcolorArr[0] : $team->bgcolor;
            $team->cssClass = $team->alias.(($i++ % 2) ? ' odd' : ' even');
            $this->allTeams[$result->id] = $team;
        }
        $i = 0;
        $result = $this->Database->prepare("SELECT DISTINCT team_id FROM tl_fussball_matches WHERE anstoss > ?")->execute(time()) ;
        while ($result->next()) {
            $team           = $this->allTeams[$result->team_id];
            $team->cssClass = $team->alias.(($i++ % 2) ? ' odd' : ' even');
            $this->activeTeams[$team->id] = $team;
        }

        $this->Template->allTeams    = $this->allTeams;
        $this->Template->activeTeams = $this->activeTeams;
	}

}

