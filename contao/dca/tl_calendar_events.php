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

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fussball_matches_id'] = [
    'label'                   => ['FUSSBALL_MATCHES_ID', 'FUSSBALL_MATCHES_ID'],
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fussball_tournament_id'] = [
    'label'                   => ['FUSSBALL_TOURNAMENT_ID', 'FUSSBALL_TOURNAMENT_ID'],
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_calendar_events']['list']['sorting']['child_record_callback'] =
    ['tl_calendar_events_fussball', 'listEvents'];


class tl_calendar_events_fussball extends tl_calendar_events
{
    public function listEvents($arrRow)
    {
        $strReturn = parent::listEvents($arrRow);
        $isTourn   = ($arrRow['fussball_tournament_id'] !== '0');
        $isMatch   = ($arrRow['fussball_matches_id']    !== '0');

        if ($isTourn || $isMatch)
        {
            $cssClass  = 'tl_content_left fussball_event';
            $cssClass .= ($isMatch) ? ' fussball_matches' : ' fussball_tournament';

            $strReturn = str_replace('tl_content_left', $cssClass, $strReturn);
        }
        return $strReturn;
    }
}
