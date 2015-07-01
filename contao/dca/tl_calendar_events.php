<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2015 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    fussball
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fussball_matches_id'] = array(
    'label'                   => array('FUSSBALL_MATCHES_ID', 'FUSSBALL_MATCHES_ID'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fussball_tournament_id'] = array(
    'label'                   => array('FUSSBALL_TOURNAMENT_ID', 'FUSSBALL_TOURNAMENT_ID'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
);

$GLOBALS['TL_DCA']['tl_calendar_events']['list']['sorting']['child_record_callback'] =
    array('tl_calendar_events_fussball', 'listEvents');

class tl_calendar_events_fussball extends tl_calendar_events
{
    public function listEvents($arrRow)
    {
        $strReturn = parent::listEvents($arrRow);
        $isTourn   = (strlen($arrRow['fussball_tournament_id']) > 0);
        $isMatch   = (strlen($arrRow['fussball_matches_id']) > 0);

        if ($isTourn || $isMatch)
        {
            $cssClass  = ($isMatch) ? ' fussball_matches' : ' fussball_tournament';
            $strReturn = '<div class="fussball_event'.$cssClass.'">'.$strReturn.'</div>';
        }
        return $strReturn;
    }
}
