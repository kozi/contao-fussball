<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2013 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    fussball_widget
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fussball_matches_id'] = array(
    'label'                   => array('FUSSBALL_MATCHES_ID', 'FUSSBALL_MATCHES_ID'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
);