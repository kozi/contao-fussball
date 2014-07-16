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
$GLOBALS['fussball']['team_attributes'] = array(
    'Betreuer', 'Doodle', 'Facebook', 'Jahrgang',
    'Kontakt', 'Koordinator', 'Mannschaftsverantwortlicher', 'Obmänner',
    'Trainer', 'Trainer/Betreuer', 'Trainingszeiten',
);


$GLOBALS['TL_CRON']['hourly'][]                            = array('ContaoFussball\FussballDataManager', 'updateMatches');
$GLOBALS['TL_CRON']['daily'][]                             = array('ContaoFussball\FussballDataManager', 'updateCalendar');

$GLOBALS['TL_CTE']['fussball']['fussball_goalgetter']      = 'ContaoFussball\Elements\ContentFussballGoalgetter';
$GLOBALS['TL_CTE']['fussball']['fussball_tournament']      = 'ContaoFussball\Elements\ContentFussballTournament';
$GLOBALS['TL_CTE']['fussball']['fussball_matches']         = 'ContaoFussball\Elements\ContentFussballMatches';
$GLOBALS['TL_CTE']['fussball']['fussball_widget']          = 'ContaoFussball\Elements\ContentFussballWidget';
$GLOBALS['TL_CTE']['fussball']['fussball_team']            = 'ContaoFussball\Elements\ContentFussballTeam';
$GLOBALS['TL_CTE']['texts']['fussball_infobox']            = 'ContaoFussball\Elements\ContentFussballInfobox';


array_insert($GLOBALS['BE_MOD'], 1, array('fussball' => array()));

array_insert($GLOBALS['BE_MOD']['fussball'], 0, array(
    'fussball_teams' => array
    (
        'tables'     => array('tl_fussball_team'),
        'icon'       => 'system/modules/fussball/assets/icons/soccer.png',
        'update'     => array('ContaoFussball\FussballDataManager', 'updateMatches'),
    ),
    'fussball_matches' => array
    (
        'tables'     => array('tl_fussball_matches'),
        'icon'       => 'system/modules/fussball/assets/icons/chain.png',
    ),
    'fussball_tournament' => array
    (
        'tables'     => array('tl_fussball_tournament'),
        'icon'       => 'system/modules/fussball/assets/icons/tournament.png',
    )
));

if(TL_MODE === 'BE') {
	$GLOBALS['TL_CSS'][]          = 'system/modules/fussball/assets/be_style.css';
	$GLOBALS['TL_JAVASCRIPT'][]   = 'system/modules/fussball/assets/be_script.js';
}
