<?php

namespace ContaoFussball;

class FullCal {
    public static $matchLength = 6300;

    public static function fullCalEventFromMatchEntry($match, $team) {
        $erg   = (strlen($match['ergebnis']) > 0) ? ' '.$match['ergebnis'] : '';
        $title = $team['name_short'].': '.$match['heim'].' - '.$match['gast'].' ['.$match['typ'].']'.$erg;

        return static::fullCalEvent(
            $match['id'],
            $title,
            $match['anstoss'],
            $match['anstoss'] + static::$matchLength,
            false,
            $team['bgcolor'][0]
        );
    }

    public static function fullCalEvent($id, $title, $start, $end, $url = false, $bgColor = false) {
        $event = array(
            'id'     => $id,
            'title'  => $title,
            'start'  => $start,
            'end'    => $end,
            'allDay' => false,
        );

        if ($url     !== false) $event['url']             = $url;
        if ($bgColor !== false) $event['backgroundColor'] = '#'.$bgColor;

        return $event;
    }
}

