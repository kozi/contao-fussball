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

TemplateLoader::addFiles(array
(
    // ContentElement Templates
	'ce_fussball_goalgetter'      => 'system/modules/fussball/templates',	
	'ce_fussball_matches'         => 'system/modules/fussball/templates',
	'ce_fussball_widget'          => 'system/modules/fussball/templates',
    'ce_fussball_tournament'      => 'system/modules/fussball/templates',
    'ce_fussball_calendar'        => 'system/modules/fussball/templates',
    'ce_fussball_team'            => 'system/modules/fussball/templates',
    'fussball_team_list'          => 'system/modules/fussball/templates',
    'fussball_team_single'        => 'system/modules/fussball/templates',

    // Mobile template
    'ce_fussball_matches_mobile'  => 'system/modules/fussball/templates',
));
