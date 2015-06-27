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
namespace ContaoFussball\Models;

/**
 * Class FussballMatchModel
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball
 */

class FussballMatchModel extends \Model {

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_fussball_match';

    public function getTitle() {
        $objTeam  = FussballTeamModel::findByPk($this->pid);
        if ($this->isHeimspiel()) {
            $title    = $objTeam->name_external.' - '.$this->gegner;
        }
        else {
            $title    = $this->gegner.' - '.$objTeam->name_external;
        }

        return $title;
    }

    public function isHeimspiel() {
        return ($this->heimspiel === '1');
    }
}
