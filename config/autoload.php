<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Fussball_widget
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'FussballHighscoreGraph' => 'system/modules/fussball_widget/FussballHighscoreGraph.php',
	'FussballWidgetContent'  => 'system/modules/fussball_widget/FussballWidgetContent.php',
	'FussballResultsContent' => 'system/modules/fussball_widget/FussballWidgetContent.php',
	'TournamentRedirect'     => 'system/modules/fussball_widget/TournamentRedirect.php',
	'ModuleEventlistFilter'  => 'system/modules/fussball_widget/ModuleEventlistFilter.php',
	'SimpleDatabaseResult'   => 'system/modules/fussball_widget/SimpleDatabaseResult.php',
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_fussball_highscore_graph' => 'system/modules/fussball_widget/templates',
	'ce_fussball_widget'          => 'system/modules/fussball_widget/templates',
	'ce_fussball_results'         => 'system/modules/fussball_widget/templates',
	'ce_fussball_goalgetter'      => 'system/modules/fussball_widget/templates',		
	'event_tournament'            => 'system/modules/fussball_widget/templates',
));
