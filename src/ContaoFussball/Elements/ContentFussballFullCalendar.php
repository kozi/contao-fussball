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
 * Class ContentFussballFullCalendar
 *
 * @copyright  Martin Kozianka 2011-2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball
 */

class ContentFussballFullCalendar extends \ContentElement {
    private $events        = null;
    private $teamIds       = null;
    private $teams         = array();
    protected $strTemplate = 'ce_fussball_calendar';

	public function generate() {

		if (TL_MODE == 'BE') {
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### FullCalendar ###';
			$objTemplate->title    = $this->headline;
			$objTemplate->id       = $this->id;
			$objTemplate->link     = $this->name;
			$objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
        $this->teamIds = unserialize($this->fussball_team_id_array);

        $result = $this->Database->execute("SELECT * FROM tl_fussball_team WHERE id IN (".implode(',', $this->teamIds).")");
        while($result->next()) {
            $row                      = $result->row();
            $row['bgcolor']           = unserialize($row['bgcolor']);
            $this->teams[$result->id] = $row;
        }
		return parent::generate();
	}
			
	protected function compile() {
        $legendTeams = array();


        $result      = $this->Database->prepare("SELECT DISTINCT team_id FROM tl_fussball_matches
                          WHERE anstoss > ?")->execute(time()) ;
        while ($result->next()) {
            $legendTeams[$result->team_id] = $this->teams[$result->team_id];
        }
        $this->Template->legendTeams = $legendTeams;

        if ('json' === \Input::get('fullcalendar')) {
            $this->getEvents();
            echo json_encode($this->events);
            exit;
        }
	}

    private function getEvents() {
        if ($this->events !== null) {
            return true;
        }
        $this->events = array();

        $result = $this->Database->execute("SELECT * FROM tl_fussball_matches
            WHERE team_id IN (".implode(',', $this->teamIds).")");
        while ($result->next()) {
            $this->events[] = FullCal::fullCalEventFromMatchEntry($result->row(), $this->teams[$result->team_id]);
        }

    }

}