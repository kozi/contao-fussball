<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2011-2013 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball_widget 
 * @license    LGPL 
 * @filesource
 */

/**
 * Class ContentFussballInfobox
 *
 * @copyright  Martin Kozianka 2011-2013 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    fussball_widget
 */

class ContentFussballInfobox extends ContentText {

    protected function compile() {
        parent::compile();
        $this->Template->class .= 'infobox '.$this->Template->class;
    }
}

