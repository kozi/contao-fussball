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
 * Class Verein
 *
 * @copyright  Martin Kozianka 2011-2015 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball
 */
class Verein
{
    public $id;
    public $action;
    public $title;

    public function __construct($strId, $strAction, $strTitle)
    {
        $this->id     = $strId;
        $this->action = $strAction;
        $this->title  = $strTitle;
    }
}
