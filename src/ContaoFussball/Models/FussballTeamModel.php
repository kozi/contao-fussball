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
 * Class FussballTeamModel
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball
 */
class FussballTeamModel extends \Model {

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_fussball_team';


    public static function findWithArray(array $arrOptions) {
        return self::find($arrOptions);
    }

}
