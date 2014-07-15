<?php

namespace ContaoFussball;

class Verein {
    public $id;
    public $action;
    public $title;
    public function __construct($strId, $strAction, $strTitle) {
        $this->id     = $strId;
        $this->action = $strAction;
        $this->title  = $strTitle;
    }
}

