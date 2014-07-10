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
$GLOBALS['fussball_widget']['team_attributes'] = array(
    'Trainer/Betreuer', 'Trainer', 'Betreuer', 'Kontakt', 'Jahrgang',
    'Trainingszeiten', 'Facebook', 'Mannschaftsverantwortlicher',
    'Obmänner','Doodle','Koordinator'
);
uksort($GLOBALS['fussball_widget']['team_attributes'], 'strcasecmp');


$GLOBALS['TL_CRON']['hourly'][]                            = array('ContaoFussball\FussballDataManager', 'updateMatches');
$GLOBALS['TL_CRON']['daily'][]                             = array('ContaoFussball\FussballDataManager', 'updateCalendar');

$GLOBALS['TL_CTE']['fussball']['fussball_goalgetter']      = 'ContaoFussball\Elements\ContentFussballGoalgetter';
$GLOBALS['TL_CTE']['fussball']['fussball_tournament']      = 'ContaoFussball\Elements\ContentFussballTournament';
$GLOBALS['TL_CTE']['fussball']['fussball_matches']         = 'ContaoFussball\Elements\ContentFussballMatches';
$GLOBALS['TL_CTE']['fussball']['fussball_widget']          = 'ContaoFussball\Elements\ContentFussballWidget';
$GLOBALS['TL_CTE']['fussball']['fussball_calendar']        = 'ContaoFussball\Elements\ContentFussballFullCalendar';
$GLOBALS['TL_CTE']['fussball']['fussball_team']            = 'ContaoFussball\Elements\ContentFussballTeam';
$GLOBALS['TL_CTE']['texts']['fussball_infobox']            = 'ContaoFussball\Elements\ContentFussballInfobox';


array_insert($GLOBALS['BE_MOD'], 1, array('fussball' => array()));

array_insert($GLOBALS['BE_MOD']['fussball'], 0, array(
    'fussball_teams' => array
    (
        'tables'     => array('tl_fussball_team'),
        'icon'       => 'system/modules/fussball_widget/assets/icons/soccer.png',
    ),
    'fussball_matches' => array
    (
        'tables'     => array('tl_fussball_matches'),
        'icon'       => 'system/modules/fussball_widget/assets/icons/chain.png',
    ),
    'fussball_tournament' => array
    (
        'tables'     => array('tl_fussball_tournament'),
        'icon'       => 'system/modules/fussball_widget/assets/icons/tournament.png',
    )
));

if(TL_MODE === 'BE') {
	$GLOBALS['TL_CSS'][]          = 'system/modules/fussball/assets/be_style.css';
	$GLOBALS['TL_JAVASCRIPT'][]   = 'system/modules/fussball/assets/be_script.js';
}