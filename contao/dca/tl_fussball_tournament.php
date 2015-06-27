<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2015 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    fussball
 * @license    LGPL
 * @filesource
 */

if (!class_exists('tl_calendar_events')) {
    require_once(TL_ROOT . '/system/modules/calendar/dca/tl_calendar_events.php');
}

$this->loadLanguageFile('tl_calendar_events');
$this->loadLanguageFile('tl_fussball_match');

$GLOBALS['TL_DCA']['tl_fussball_tournament'] = array(

// Config
'config' => array
(
	'dataContainer'               => 'Table',
    'ptable'                      => 'tl_fussball_team',
    'enableVersioning'            => true,
    'sql' => array(
        'keys' => array(
            'id'  => 'primary',
            'pid' => 'index'
        )
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
        'mode'                    => 4,
        'fields'                  => array('startDate DESC', 'title'),
        'headerFields'            => array('name', 'name_external'),
        'disableGrouping'         => true,
        'panelLayout'             => 'limit',
        'child_record_callback'   => array('tl_fussball_tournament', 'listTournament'),
        'child_record_class'      => 'tl_fussball tl_fussball_tournament'
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
    'pid' => array
    (
        'foreignKey'              => 'tl_fussball_team.name',
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
        'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
    ),
    'tstamp' => array
    (
        'label'                   => array('TSTAMP'),
        'search'                  => false,
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
        'options'                 => \ContaoFussball\FussballDataManager::$FIELD_TYPES,
        'eval'                    => array('tl_class'=>'w50', 'decodeEntities' => true),
        'sql'                     => "varchar(255) NOT NULL default ''",
    ),
    'title' => array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_fussball_tournament']['title'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('tl_class' => 'w50'),
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
        'flag'                    => 8,
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

    /**
     * List a tournament
     * @param array
     * @return string
     */
    public function listTournament($row)
    {
        $team      = $this->teams[$row['pid']];
        $strStart  = Date::parse('d.m.Y', $row['startDate']);
        $strEnd    = ($row['endDate'] != null) ? Date::parse('d.m.Y', $row['endDate']) : '';

        if ($row['addTime']) {
            $strStart .= '&nbsp;'.Date::parse('H:i', $row['startTime']);
            $strEnd   .= '&nbsp;'.Date::parse('H:i', $row['endTime']);

        }

        $confirmedImg = \Image::get('system/modules/fussball/assets/icons/confirmed'.$row['confirmed'].'.png', 16, 16);
        $typeImg      = \Image::get('system/modules/fussball/assets/icons/type-'.standardize($row['field_type']).'.png', 16, 16);

        $arrRow = [
            'team'       => $team->name_short,
            'title'      => String::substr($row['title'], 52),
            'start'      => '&nbsp;'.$strStart,
            'end'        => '&nbsp;'.$strEnd,
            'field_type' => \Image::getHtml($typeImg),
            'confirmed'  => \Image::getHtml($confirmedImg)

        ];

        $strRow  = '';
        $strTmpl = '<div class="tl_fussball_cell %s">%s</div>';
        foreach($arrRow as $k => $v) {
            $strRow .= sprintf($strTmpl, $k, $v);
        }

        return $strRow;
    }
}

// Adjust DCA for listing all matches
if (Input::get('do') == 'fussball_tournament') {

    $a = &$GLOBALS['TL_DCA']['tl_fussball_tournament'];

    unset($a['config']['ptable']);

    $a['config']['closed']        = true;
    $a['list']['sorting']['mode'] = 2;

    unset($a['list']['sorting']['disableGrouping']);
    unset($a['list']['sorting']['headerFields']);
    unset($a['list']['sorting']['child_record_callback']);
    unset($a['list']['sorting']['child_record_class']);

    $a['list']['label'] = array(
        'fields'                  => array('startDate', 'pid', 'title'),
        'label_callback'          => array('tl_fussball_tournament', 'listTournament')
    );

}
