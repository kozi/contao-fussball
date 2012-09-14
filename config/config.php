<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2012 <http://kozianka-online.de/>
 * @author     Martin Kozianka <http://kozianka-online.de/>
 * @package    fussball_widget
 * @license    LGPL
 * @filesource
 */
$GLOBALS['TL_CTE']['fussball']['fussball_tournament_list'] = 'TournamentListContent';
$GLOBALS['TL_CTE']['fussball']['fussball_goalgetter_list'] = 'GoalgetterListContent';
$GLOBALS['TL_CTE']['fussball']['fussball_widget']          = 'FussballWidgetContent';
$GLOBALS['TL_CTE']['fussball']['fussball_highscore_graph'] = 'FussballHighscoreGraph';
$GLOBALS['TL_CTE']['fussball']['fussball_results']         = 'FussballResultsContent';

if (strlen($GLOBALS['TL_CONFIG']['fussball_tourn_calendar']) > 0) {
	array_insert($GLOBALS['BE_MOD'], 1, array(
			'content' => array(
					'fussball_tournament' => array
					(
							'icon'       => 'system/modules/fussball_widget/html/icons/tournament.png',
							'callback'   => 'FussballModule',
					)
	)));

}



if(TL_MODE == 'BE') {
	$GLOBALS['TL_CSS'][]        = 'system/modules/fussball_widget/html/be_style.css';
	$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fussball_widget/html/be_script.js';
}
