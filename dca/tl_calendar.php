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

/**
 * Add palettes to tl_calendar
 */

$GLOBALS['TL_DCA']['tl_calendar']['config']['onload_callback'][] = array('FussballDataManager', 'updateCalendar');
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['default']        .= ';{fussball_widget_legend:closed},fussball_team_id';


$GLOBALS['TL_DCA']['tl_calendar']['fields']['fussball_team_id'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fussball_team_id'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_fussball_team.name',
    'eval'                    => array('mandatory' => false, 'includeBlankOption' => true),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
);