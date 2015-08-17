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

use ContaoFussball\Models\FussballMatchModel;
use ContaoFussball\Models\FussballTeamModel;

/**
 * Class FussballDataManager
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    Controller
 */

class FussballDataManager extends \System
{

    public static $FIELD_TYPES = [
        "Asche",
        "Kunstrasen",
        "Halle",
        "Kunstrasen (Halle)",
        "Rasen",
    ];

    public static $MATCH_TYPES = ['me', 'fs', 'po', 'kt'];

    public function sorting() {
        $id    = \Input::get('id');
        $up    = \Input::get('sort') === 'up';
        $count = 1;
        $teams = FussballTeamModel::findAll(['order' => 'sorting ASC']);
        foreach ($teams as $teamObj)
        {
            if ($teamObj->id == $id)
            {
                    $teamObj->sorting = ($up) ? (($count-1) * 16)-1 : ($count * 16)+1;
            }
            else
            {
                $teamObj->sorting = ($count++ * 16);
            }
            $teamObj->save();
        }
        \Controller::redirect(\Environment::get('script').'?do=fussball_teams');
    }

    public function matchResult() {
        $strErg   = '';
        $result   = static::cleanupResult(\Input::get('result'));
        $objMatch = FussballMatchModel::findByPk(\Input::get('match'));

        if ($objMatch === null)
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo $strErg;
            exit;
        }

        if (\Input::get('result') === '-')
        {
            $objMatch->ergebnis = '';
            $objMatch->save();
            $strErg = '-';
        }
        elseif (preg_match('/^(\d{1,4}):(\d{1,4})$/', $result))
        {
            $objMatch->ergebnis = $result;
            $objMatch->save();
            $objMatch->refresh();
            $strErg = $objMatch->ergebnis;
        }

        header($_SERVER['SERVER_PROTOCOL'].' 200 OK', true, 200);
        echo $strErg;
        exit;
    }

    public static function cleanupResult($result) {
        // Check for correct value!
        $divider = ':';
        $t = preg_replace ('/[^0-9]/',' ', $result);
        $t = preg_replace ('/\s+/', $divider, $t);

        if (strlen($t) < 3)
        {
            return '';
        }

        $tmp = explode($divider, $t);

        if(strlen($tmp[0]) < 1 && strlen($tmp[1]) < 1)
        {
            return '';
        }
        $h = intval($tmp[0], 10);
        $a = intval($tmp[1], 10);
        return $h.$divider.$a;
    }
}

