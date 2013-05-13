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

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{fussball_widget_legend},fussball_api_key,fussball_tourn_calendar,fussball_tourn_teams';

$GLOBALS['TL_DCA']['tl_settings']['fields']['fussball_api_key'] = array(
	'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['fussball_api_key'],
	'exclude'	=>	true,
	'inputType'	=>	'text',
	'eval'		=>	array('mandatory'=>true)
);



