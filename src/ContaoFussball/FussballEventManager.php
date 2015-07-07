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
namespace ContaoFussball;

/**
 * Class FussballEventManager
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */

class FussballEventManager extends \System
{
    private $arrSearch  = [];
    private $arrReplace = [];

    public function __construct()
    {
        foreach(FussballDataManager::$MATCH_TYPES as $type)
        {
            $label              = &$GLOBALS['TL_LANG']['contao_fussball']['match_types'][$type];
            $this->arrSearch[]  = '['.strtoupper($type).']';
            $this->arrReplace[] = '<span class="match_type">[<abbr title="'.$label.'">'.strtoupper($type).'</abbr>]</span>';
        }
        parent::__construct();
    }

    public function eventHook($arrEvents, $arrCalendars, $intStart, $intEnd, $module)
    {
        foreach($arrEvents as $keyOne => &$arrOne)
        {
            foreach($arrOne as $keyTwo => &$arrTwo)
            {
                foreach($arrTwo as &$e)
                {
                    if($e['fussball_matches_id'] != '0' || $e['fussball_tournament_id'] != '0')
                    {
                        // Adjust title add abbr for match type
                        $e['title']    = $this->replaceMatchTypes($e['title']);

                        // Adjust location
                        $locArray      = array_map('trim', explode('<br>', $e['location']));
                        $e['location'] = '';
                        $i = 0;
                        foreach($locArray as $row)
                        {
                            $e['location'] .= '<span class="row'.$i++.'">'.$row.(($i==1||$i==2)? ', ': '').'</span>';
                        }
                    }
                }
            }
        }
        return $arrEvents;
    }

    private function replaceMatchTypes($strTitle)
    {
        return str_ireplace($this->arrSearch, $this->arrReplace, $strTitle);
    }
}
