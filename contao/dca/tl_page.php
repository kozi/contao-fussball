<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    fussball
 * @license    LGPL
 * @filesource
 */
$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] .= ';{fussball_legend}, fussball_api_key';

$GLOBALS['TL_DCA']['tl_page']['fields']['fussball_api_key'] = array(
	'label'		=>	&$GLOBALS['TL_LANG']['tl_page']['fussball_api_key'],
	'exclude'	=>	true,
	'inputType'	=>	'text',
    'sql'       =>  "varchar(255) NOT NULL default ''",
	'eval'		=>	array('mandatory'=>true),
);



