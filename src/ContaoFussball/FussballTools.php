<?php

namespace ContaoFussball;

class FussballTools {

    public static function getMatches($clubId, $teamId, $tstampFrom, $tstampTill) {
        $max = 100;
        $url = sprintf($GLOBALS['fussball']['url'],
            $clubId,
            $max,
            date("d.m.Y", $tstampFrom),
            date("d.m.Y", $tstampTill),
            $teamId
        );

        $mainContent    = file_get_contents($url);
        $jsonResult     = json_decode($mainContent);
        $strHtml        = strip_tags($jsonResult->html, "<table><tr><td><a>");

        $objHtml        = \Sunra\PhpSimple\HtmlDomParser::str_get_html($strHtml);
        $matches        = array();
        $match_date     = '';
        $match          = null;

        $table = $objHtml->find('table.table', 0);
        if ($table == null) {
            return $matches;
        }

        $rows = $table->find('tr');
        if ($rows == null) {
            return $matches;
        }



        foreach($rows as $tr) {
            $tds     = $tr->find('td');
            $count   = count($tds);
            $content = ($count === 1) ?  trim($tds[0]->plaintext): false;

            if ($content !== false) {
                // Date or Location
                if (self::containsDayname($content)) {
                    // Date
                    $match_date = $content;
                }
                else if (preg_match ("/(Hartplatz|Kunstrasenplatz|Rasenplatz), /" , $content, $arrResult) !== false) {

                    // Location
                    $platzart  = $arrResult[1];
                    $location  = str_replace($arrResult[0], '', $content);
                    $location  = preg_replace("/[^a-zA-Z0-9_äöüÄÖÜß\.\- \/,\[\]]/" , "" , $location);
                    $location  = str_replace(", ","\n", $location);

                    // Wenn eine Location gefunden wurde gehört diese zum "aktuellen" Spiel
                    if (is_array($match) && !array_key_exists('loc', $match)) {
                        $match['loc']        = $location;
                        $match['platzart']   = $platzart;

                    }
                }

            }
            else if ($count > 4) {

                if ($match !== null) {
                    $matches[] = $match;
                }

                $cols = array();
                foreach($tds as $td) {
                    $value = trim($td->find('text', 0)->plaintext);
                    if (strlen($value) === 0) {
                        $a     = $td->find('a', 0);
                        $value = ($a != null) ? trim($a->find('text', 0)->plaintext) : '';
                        if (strlen($value) === 0) {
                            $div = $td->find('div.club-name', 0);
                            $value = ($div != null) ? trim($div->find('text', 0)->plaintext) : '';
                        }
                    }
                    $cols[] = $value;
                }

                // Kennung
                $a         = $tr->find('td', 7)->find('a', 0);
                $kennung   = ($a != null) ? preg_replace("#http:\/\/.*\/spiel\/#", "", trim($a->href)) : 'SPIELFREI';

                // Match
                $match     = array(
                    'kennung'  => $kennung,
                    'tstamp'   => self::getTimestamp($cols[0], $match_date),
                    'typ'      => self::getMatchType($cols[1]),
                    'klasse'   => $cols[2],
                    'manh'     => $cols[3],
                    'mana'     => $cols[5],
                    // 'ergebnis' => $cols[6],
                );
            }

        } //foreach

        // Das letzte Spiel muss auch noch mit
        if ($match !== null && sizeof($match) > 0) {
            $matches[] = $match;
        }
        return $matches;
    }

    public static function url_title($str, $separator = 'dash') {
        $search		= ($separator == 'dash') ? '_' : '-';
        $replace	= ($separator == 'dash') ? '-' : '_';

        $trans      = array(
            $search								=> $replace,
            "\s+"								=> $replace,
            "[^a-z0-9".$replace."]"				=> '',
            $replace."+"						=> $replace,
            $replace."$"						=> '',
            "^".$replace						=> ''
        );

        $str = strip_tags(strtolower($str));

        foreach ($trans as $key => $val) {
            $str = preg_replace("#".$key."#", $val, $str);
        }

        return trim(stripslashes($str));
    }

    public static function containsDayname($str = '') {
        if (strlen($str) === 0) return false;

        $days = array('Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag');
        foreach($days as $day) {
            if (strpos($str, $day) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function getVereine($url) {
        $vereine = array();
        $html    = file_get_html($url);
        $element = $html->find('form[id=egmClubFixtureListParams"]', 0);
        $action  = $element->action;

        $tds = $html->find('div.egmClubFixtureListScrollable td.egmClubFixtureList');

        foreach ($tds as $td) {
            $saison1 = date("y", time() - ((24*60*60)*365))."/".date("y");
            $saison2 = date("y")."/".date("y", time() + ((24*60*60)*365));

            $id      = $td->find('input', 0)->value;
            $title   = $td->find('span', 0)->find('text', 0)->plaintext;
            $saisons = $td->find('div.egmTooltip div', 0)->plaintext;

            $isAktuelleSaison = (strpos($saisons, $saison1) !== false) || (strpos($saisons, $saison2) !== false);

            // id muss gesetzt sein und aktuelle Saison muss im String sein
            if ($id !== null && $isAktuelleSaison) {
                $vereine[] = self::getVerein($id, $action, $title, self::url_title($title));
            }
        }

        return $vereine;
    }

    public static function getVerein($strId, $strAction, $strTitle) {
        return new Verein($strId, $strAction, $strTitle);
    }
    private static function getMatchType($strTyp) {
        $arrTypes = array(
            'Meisterschaftsspiel' => 'ME',
            'Freundschaftsspiel'  => 'FS',
            'Pokalspiel'          => 'PO',
            'Turnierspiel'        => 'TU',
        );
        if (array_key_exists($strTyp, $arrTypes)) {
            return $arrTypes[$strTyp];
        }
        return $strTyp;
    }
    private static function getTimestamp($strDateTime, $strDate) {

        if (strlen($strDateTime) === 20) {
            $arr = explode(',', $strDateTime);
            array_shift($arr);
            $objDate = \DateTime::createFromFormat('d.m.y \| H:i', trim(implode('',$arr)));
        }
        else {
            $arr = explode(',', $strDate.' '.$strDateTime);
            array_shift($arr);
            $objDate = \DateTime::createFromFormat('d.m.Y H:i', trim(implode('',$arr)));
        }
        return ($objDate) ? $objDate->getTimestamp() : 0;
    }


}

