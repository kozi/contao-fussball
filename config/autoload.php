<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2013 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball_widget 
 * @license    LGPL 
 * @filesource
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'FussballTournamentContent'      => 'system/modules/fussball_widget/classes/FussballTournamentContent.php',
	'GoalgetterListContent'          => 'system/modules/fussball_widget/classes/GoalgetterListContent.php',
	'FussballWidgetContent'          => 'system/modules/fussball_widget/classes/FussballWidgetContent.php',
	'FussballMatchesContent'         => 'system/modules/fussball_widget/classes/FussballMatchesContent.php',
	'FussballDataManager'            => 'system/modules/fussball_widget/classes/FussballDataManager.php',
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(

	'ce_fussball_goalgetter'      => 'system/modules/fussball_widget/templates',	
	'ce_fussball_matches'         => 'system/modules/fussball_widget/templates',
	'ce_fussball_widget'          => 'system/modules/fussball_widget/templates',		
	'ce_fussball_tournament'      => 'system/modules/fussball_widget/templates',
));
