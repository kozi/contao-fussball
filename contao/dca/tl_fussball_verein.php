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

$GLOBALS['TL_DCA']['tl_fussball_verein'] = array(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'switchToEdit'                => true,
        'enableVersioning'            => true,
        'sql' => array(
            'keys' => array('id' => 'primary')
        )
    ),


// List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('name ASC'),
            'flag'                    => 11,
            'panelLayout'             => 'filter, search, limit'
        ),
        'label' => array
        (
            'fields'                  => array('wappen', 'name', 'name_short', 'location', 'platzart'),
            'showColumns'             => true,
            'label_callback'          => array('tl_fussball_verein', 'addPreviewImage')
        ),


        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_fussball_verein']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif',
                'attributes'          => 'class="contextmenu"'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_fussball_verein']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_fussball_verein']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            )
        )

    ),

    // Palettes
    'palettes' => array
    (
        'default' => '{title_legend},name,name_short,platzart,homepage,location,teams,wappen,home'
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

        'name' => array
        (
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_verein']['name'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'name_short' => array
        (
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_verein']['name_short'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'homepage' => array
        (
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_verein']['homepage'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'home' => array
        (
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_verein']['home'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12', 'unique' => true),
            'sql'                     => "char(1) NOT NULL default ''",
        ),
        'location' => array
        (
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_verein']['location'],
            'search'                  => false,
            'inputType'               => 'textarea',
            'eval'                    => array('tl_class' => 'clr'),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'platzart' => array
        (
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_verein']['platzart'],
            'search'                  => false,
            'inputType'               => 'select',
            'options'                 => \ContaoFussball\FussballDataManager::$FIELD_TYPES,
            'default'                 => \ContaoFussball\FussballDataManager::$FIELD_TYPES[0],
            'eval'                    => array('tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'wappen' => array
        (
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_verein']['wappen'],
            'exclude'                 => true,
            'search'                  => false,
            'sorting'                 => false,
            'inputType'		          => 'fileTree',
            'eval'			          => array('tl_class' => 'clr', 'mandatory'=> false, 'files' => true, 'filesOnly' => true, 'fieldType' => 'radio'),
            'sql'                     => "binary(16) NULL",
        ),
        'teams' => array
        (
            'label'                   => $GLOBALS['TL_LANG']['tl_fussball_verein']['teams'],
            'exclude'                 => true,
            'search'                  => false,
            'sorting'                 => false,
            'inputType'		          => 'optionWizard',
            'eval'			          => array('mandatory'=> false, 'tl_class' => 'tl_fussball_teamlist'),
            'sql'                     => "blob NULL",
        ),
    ) //fields

);

class tl_fussball_verein extends Backend {

    public function addPreviewImage($row, $label, DataContainer $dc, $args = null) {

        $isHomeTeam = ($row['home'] === '1');
        $objFile    = FilesModel::findByUuid($row['wappen']);
        if ($objFile !== null)
        {
            // wappen
            $args[0] = '<img src="' . TL_FILES_URL . Image::get($objFile->path, 32, 32, 'center_center') . '" width="32" height="32" alt="" class="wappen">';
        }

        // location
        $args[3] = str_replace("\n", "<br>", $args[3]);

        // platzart
        if (strlen($row['platzart']) > 0) {
            $args[4] = \Image::getHtml(\Image::get('system/modules/fussball/assets/icons/type-'.standardize($row['platzart']).'.png', 16, 16), $row['platzart'], 'title="'.$row['platzart'].'"');
        }

        if ($isHomeTeam)
        {
            $args[1] = '<strong>'.$args[1].'</strong>';
            $args[2] = '<strong>'.$args[2].'</strong>';
            $args[3] = '<strong>'.$args[3].'</strong>';
        }

        return $args;
    }

}
if (Input::get('do') == 'fussball_verein') {
    $GLOBALS['TL_LANG']['MSC']['ow_value'] = 'Mannschaftsname';
    $GLOBALS['TL_LANG']['MSC']['ow_label'] = 'Abkürzung';
}


