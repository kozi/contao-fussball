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
    // Classes
    'FussballDataManager'            => 'system/modules/fussball_widget/classes/FussballDataManager.php',

    // Elements
    'ContentFussballMatches'         => 'system/modules/fussball_widget/elements/ContentFussballMatches.php',
    'ContentFussballTournament'      => 'system/modules/fussball_widget/elements/ContentFussballTournament.php',
    'ContentFussballGoalgetter'      => 'system/modules/fussball_widget/elements/ContentFussballGoalgetter.php',
    'ContentFussballWidget'          => 'system/modules/fussball_widget/elements/ContentFussballWidget.php',
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
