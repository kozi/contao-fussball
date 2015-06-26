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
$GLOBALS['fussball']['team_attributes'] = array(
    'Betreuer', 'Doodle', 'Facebook', 'Jahrgang',
    'Kontakt', 'Koordinator', 'Mannschaftsverantwortlicher', 'Obmänner',
    'Torwarttraining', 'Trainer', 'Trainer/Betreuer', 'Trainingszeiten'
);

$GLOBALS['TL_MODELS']['tl_fussball_team']                  = '\ContaoFussball\Models\FussballTeamModel';
$GLOBALS['TL_MODELS']['tl_fussball_match']                 = '\ContaoFussball\Models\FussballMatchModel';
$GLOBALS['TL_MODELS']['tl_fussball_tournament']            = '\ContaoFussball\Models\FussballTournamentModel';

$GLOBALS['TL_CRON']['hourly'][]                            = array('ContaoFussball\FussballDataManager', 'updateCalendar');

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
        'tables'     => array('tl_fussball_team', 'tl_fussball_match', 'tl_fussball_tournament'),
        'icon'       => 'system/modules/fussball/assets/icons/soccer.png',
        'sorting'    => array('ContaoFussball\FussballDataManager', 'sorting'),
    ),
    'fussball_matches' => array
    (
        'tables'     => array('tl_fussball_match'),
        'icon'       => 'system/modules/fussball/assets/icons/chain.png',
        'result'     => array('ContaoFussball\FussballDataManager', 'matchResult'),
    ),
    'fussball_tournament' => array
    (
        'tables'     => array('tl_fussball_tournament'),
        'icon'       => 'system/modules/fussball/assets/icons/tournament.png',
    )
));

if(TL_MODE === 'BE') {
    // awesomplete
    $GLOBALS['TL_CSS'][]          = 'system/modules/fussball/assets/rbecheras-awesomplete/awesomplete.css|static';
    $GLOBALS['TL_JAVASCRIPT'][]   = 'system/modules/fussball/assets/rbecheras-awesomplete/awesomplete.min.js|static';

    $GLOBALS['TL_CSS'][]          = 'system/modules/fussball/assets/be_style.css|static';
    $GLOBALS['TL_JAVASCRIPT'][]   = 'system/modules/fussball/assets/be_script.js|static';
}
