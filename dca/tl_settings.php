<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
/*
 * Fussball Widget
 * http://kozianka-online.de/
 *
 * Copyright (c) 2011 Martin Kozianka
 *
 * Author: Martin Kozianka
 *
 */

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{fussball_widget_legend},fussball_api_key';

$GLOBALS['TL_DCA']['tl_settings']['fields']['fussball_api_key'] = array(
	'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['fussball_api_key'],
	'exclude'	=>	true,
	'inputType'	=>	'text',
	'eval'		=>	array('mandatory'=>true)
);

?>