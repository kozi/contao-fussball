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

if (!class_exists('tl_calendar_events')) {
    require_once(TL_ROOT . '/system/modules/calendar/dca/tl_calendar_events.php');
}

$this->loadLanguageFile('tl_calendar_events');
$this->loadLanguageFile('tl_fussball_matches');

$GLOBALS['TL_DCA']['tl_fussball_tournament'] = array(

// Config
'config' => array
(
	'dataContainer'               => 'Table',
    'closed'                      => false,
    'notEditable'                 => false,
    'sql' => array(
        'keys' => array('id' => 'primary')
    ),
    'onsubmit_callback' => array
    (
        array('tl_fussball_tournament', 'adjustTime'),
    ),
),

// List
'list' => array
(
	'sorting' => array
	(
		'mode'                    => 2,
		'fields'                  => array('startDate DESC'),
		'flag'                    => 1,
		'panelLayout'             => 'sort, filter, limit'
	),
	'label' => array
	(
		'fields'                  => array('team_id', 'title', 'host', 'startDate', 'endDate', 'confirmed'),
		'showColumns'             => true,
		'label_callback'          => array('tl_fussball_tournament', 'labelCallback')
	),

    'operations' => array
    (
        'edit' => array
        (
            'label'               => &$GLOBALS['TL_LANG']['tl_fussball_tournament']['edit'],
            'href'                => 'act=edit',
            'icon'                => 'edit.gif',
            'attributes'          => 'class="contextmenu"'
        ),
        'delete' => array
        (
            'label'               => &$GLOBALS['TL_LANG']['tl_fussball_tournament']['delete'],
            'href'                => 'act=delete',
            'icon'                => 'delete.gif',
            'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_fussball_tournament']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
        )
    )

),


// Palettes
'palettes' => array
(
    '__selector__'                => array('addTime'),
    'default'                     => '{title_legend},title,team_id,host,location,field_type,confirmed;{date_legend},addTime,startDate,endDate;{details_legend},details'
),

// Subpalettes
'subpalettes' => array
(
    'addTime'                     => 'startTime,endTime',
),


// Fields
'fields' => array
(
    'id' => array
    (
        'label'                   => array('ID'),
        'search'                  => false,
        'sql'                     => "int(10) unsigned NOT NULL auto_increment"
    ),
    'tstamp' => array
    (
        'label'                   => array('TSTAMP'),
        'search'                  => false,
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
    ),

    'title' => array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_fussball_tournament']['title'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('tl_class' => 'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'team_id' => array(
        'label'                   => $GLOBALS['TL_LANG']['tl_fussball_tournament']['team_id'],
        'exclude'                 => true,
        'filter'                  => true,
        'search'                  => false,
        'sorting'                 => true,
        'inputType'               => 'select',
        'foreignKey'              => 'tl_fussball_team.name',
        'eval'                    => array('tl_class' => 'w50'),
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
    ),
    'host' => array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_fussball_tournament']['host'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('tl_class' => 'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'location' => array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_fussball_tournament']['location'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('tl_class' => 'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),

    'field_type' => array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_fussball_tournament']['field_type'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'select',
        'options'                 => array("Asche", "Kunstrasen", "Halle", "Kunstrasen (Halle)", "Rasen"),
        'eval'                    => array('tl_class'=>'w50', 'decodeEntities' => true),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),

    'confirmed' => array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_fussball_tournament']['confirmed'],
        'exclude'                 => true,
        'search'                  => false,
        'filter'                  => true,
        'inputType'               => 'checkbox',
        'eval'                    => array('tl_class'=>'w50 m12'),
        'sql'                     => "char(1) NOT NULL default ''",
    ),
    'details' => array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_fussball_tournament']['details'],
        'exclude'                 => true,
        'search'                  => false,
        'filter'                  => false,
        'inputType'               => 'textarea',
        'eval'                    => array('style'=>'height:80px;'),
        'sql'                     => "text NULL",
    ),

    'addTime' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['addTime'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => array('submitOnChange'=>true, 'doNotCopy'=>true),
        'sql'                     => "char(1) NOT NULL default ''",
    ),
    'startTime' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['startTime'],
        'default'                 => time(),
        'exclude'                 => true,
        'filter'                  => false,
        'sorting'                 => false,
        'flag'                    => 8,
        'inputType'               => 'text',
        'eval'                    => array('rgxp'=>'time', 'mandatory'=>true, 'doNotCopy'=>true, 'tl_class'=>'w50'),
        'sql'                     => "int(10) unsigned NULL"
    ),
    'endTime' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['endTime'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('rgxp'=>'time', 'doNotCopy'=>true, 'tl_class'=>'w50'),
        'save_callback' => array
        (
            array('tl_calendar_events', 'setEmptyEndTime')
        ),
        'sql'                     => "int(10) unsigned NULL"
    ),
    'startDate' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['startDate'],
        'default'                 => time(),
        'exclude'                 => true,
        'sorting'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
        'sql'                     => "int(10) unsigned NULL"
    ),
    'endDate' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['endDate'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('rgxp'=>'date', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
        'save_callback' => array
        (
            array('tl_calendar_events', 'setEmptyEndDate')
        ),
        'sql'                     => "int(10) unsigned NULL"
    ),

) //fields

);


class tl_fussball_tournament extends Backend {
    private $teams = array();
    public function __construct() {
        parent::__construct();
        $this->import('BackendUser', 'User');

        $result = $this->Database->execute('SELECT * FROM tl_fussball_team');

        while($result->next()) {
            $team                   = (Object) $result->row();
            $this->teams[$team->id] = $team;
        }
    }


    public function adjustTime(DataContainer $dc) {

        // Return if there is no active record (override all)
        if (!$dc->activeRecord) {
            return;
        }

        $arrSet['startTime'] = $dc->activeRecord->startDate;
        $arrSet['endTime']   = $dc->activeRecord->startDate;

        // Set end date
        if (strlen($dc->activeRecord->endDate)) {
            if ($dc->activeRecord->endDate > $dc->activeRecord->startDate) {
                $arrSet['endDate'] = $dc->activeRecord->endDate;
                $arrSet['endTime'] = $dc->activeRecord->endDate;
            }
            else {
                $arrSet['endDate'] = $dc->activeRecord->startDate;
                $arrSet['endTime'] = $dc->activeRecord->startDate;
            }
        }

        // Add time
        if ($dc->activeRecord->addTime) {
            $arrSet['startTime'] = strtotime(date('Y-m-d', $arrSet['startTime']) . ' ' . date('H:i:s', $dc->activeRecord->startTime));
            $arrSet['endTime']   = strtotime(date('Y-m-d', $arrSet['endTime']) . ' ' . date('H:i:s', $dc->activeRecord->endTime));
        }
        // Adjust end time of "all day" events
        elseif ((strlen($dc->activeRecord->endDate) && $arrSet['endDate'] == $arrSet['endTime']) || $arrSet['startTime'] == $arrSet['endTime']) {
            $arrSet['endTime'] = (strtotime('+ 1 day', $arrSet['endTime']) - 1);
        }
        $this->Database->prepare("UPDATE tl_fussball_tournament %s WHERE id=?")->set($arrSet)->execute($dc->id);
    }

    public function changeTeamIdField() {

    }

    public function labelCallback($row, $label, DataContainer $dc, $args = null) {
        if ($args === null) {
            return $label;
        }

        $team_id = $row['team_id'];
        $team    = $this->teams[$team_id];
        $args[0] = $team->name_short;

        $args[3] = Date::parse('d.m.Y', $row['startDate']).'&nbsp;';

        $args[4] = ($row['endDate'] != null) ? Date::parse('d.m.Y', $row['endDate']).'&nbsp;' : '';

        if ($row['addTime']) {
            $args[3] .= Date::parse('H:i', $row['startTime']);
            $args[4] .= Date::parse('H:i', $row['endTime']);
        }

        $imgSRC  = Image::get('system/modules/fussball_widget/assets/icons/confirmed'.$row['confirmed'].'.png', 16, 16);
        $args[5] = Image::getHtml($imgSRC);

        return $args;
    }
}