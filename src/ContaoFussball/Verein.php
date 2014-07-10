<?php

namespace ContaoFussball;

class Verein {
    public $id;
    public $action;
    public $title;
    public $alias;
    public function __construct($strId, $strAction, $strTitle, $strAlias) {
        $this->id     = $strId;
        $this->action = $strAction;
        $this->title  = $strTitle;
        $this->alias  = $strAlias;
    }
}

