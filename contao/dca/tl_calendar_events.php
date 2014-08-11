<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2014 <http://kozianka.de/>
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

