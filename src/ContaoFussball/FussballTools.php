<?php

namespace ContaoFussball;

class FussballTools {
    const SPIEL_ABGESAGT   = "Abg.";
    const SPIELFREI        = "spielfrei";
    public static function getMatches($clubId, $teamId, $tstampFrom, $tstampTill) {
        $debugEnabled = (\Input::get('debug') === '1');

        $max = 100;
        $url = sprintf($GLOBALS['fussball']['url'],
            $clubId,
            $max,
            date("d.m.Y", $tstampFrom),
            date("d.m.Y", $tstampTill),
            $teamId
        );

        if ($debugEnabled) {

            echo "<code><pre>";
            echo "clubId: ".$clubId."\n";
            echo "teamId: ".$teamId."\n";
            echo "max:    ".$max."\n";
            echo "from:   ".date("d.m.Y", $tstampFrom)."\n";
            echo "till:   ".date("d.m.Y", $tstampTill)."\n";
            echo "url:    ".$url."\n";
            echo "</pre></code>";
        }

        $matches        = array();

        $mainContent    = file_get_contents($url);
        $jsonResult     = json_decode($mainContent);
        $strHtml        = strip_tags($jsonResult->html, "<table><tr><td><a>");
        $objHtml        = \Sunra\PhpSimple\HtmlDomParser::str_get_html($strHtml);

        $table = $objHtml->find('table.table', 0);

        if ($debugEnabled) {
            echo "<code><pre>";
            echo "strlen(\$strHtml): ".strlen($strHtml);            
            echo "</pre></code>";
        }

        if ($table == null) {
            return $matches;
        }

        $rows = $table->find('tr');
        if ($rows == null) {
            return $matches;
        }

        if ($debugEnabled) {
            echo '<code><pre>$table and $rows not NULL</pre></code>';
        }

        $arrRow0 = array();
        $arrRow1 = array();
        $arrRow2 = array();

        foreach($rows as $tr) {
            $cols = array();
            $tds  = $tr->find('td');

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
            $count = count($cols);

            if ($debugEnabled) {               
               echo '<code><pre>count($cols): '.$count.'  '.implode(' ## ', $cols)."</pre></code>";
            }

            if ($count === 3) {
                if (strlen($cols[0]) === 0 && strpos($cols[1], "|") === false) {
                    // Ort und Platzart in $col[1]
                    if (preg_match ("/(Hartplatz|Kunstrasenplatz|Rasenplatz), /" , $cols[1], $arrResult) !== false) {
                        // Location
                        $platzart  = $arrResult[1];
                        $location  = str_replace($arrResult[0], '', $cols[1]);
                        $location  = preg_replace("/[^a-zA-Z0-9_äöüÄÖÜß\.\- \/,\[\]]/" , "" , $location);
                        $location  = str_replace(", ","\n", $location);
                    }
                    $arrRow0[] = array(
                        'location'  => $location,
                        'platzart'  => $platzart
                    );
                }
                else {
                    // Datum in $cols[0] und Spielklasse in $cols[1]
                    // Typ in $col[2]
                    $arrTmp1    = array_map('trim', explode('|', $cols[1]));
                    $arrTmp2    = array_map('trim', explode('|', $cols[2]));
                    $arrRow1[] = array(
                        'tstamp'        => self::getTimestamp($cols[0]),
                        'klasse'        => (count($arrTmp1) === 2) ?  $arrTmp1[1] : '',
                        'typ'           => (count($arrTmp2) === 2) ?  $arrTmp2[0] : '',
                        'kennung_short' => (count($arrTmp2) === 2) ?  $arrTmp2[1] : ''
                    );
                }
            }
            elseif ($count > 4) {

                $a       = $tr->find('td', 5)->find('a', 0);
                $link    = ($a != null) ? $a->href : false;
                $kennung = ($link) ? substr($link, strrpos($link, '/') + 1) : false;

                // Wenn abgesagt oder spielfrei gibt es die Zeile mit dem Ort nicht!
                $spielfrei = ($cols[1] === static::SPIELFREI || $cols[3] === static::SPIELFREI);
                $abgesagt  = (static::SPIEL_ABGESAGT === $cols[4]);
                if ($abgesagt || $spielfrei) {                    
                    $arrRow0[] = array('EMPTY'=> uniqid());
                }

                $arrRow2[] = array(
                    'kennung'  => $kennung,
                    'manh'     => $cols[1],
                    'mana'     => $cols[3],
                    'link'     => $link,
                    'abgesagt' => $abgesagt
                    // 'ergebnis' => $cols[6],                    
                );

            }

        } //foreach rows

        if ($debugEnabled) {

            echo "<code><pre>";
            echo 'count($arrRow0): '.count($arrRow0)."\n";
            echo 'count($arrRow1): '.count($arrRow1)."\n";
            echo 'count($arrRow2): '.count($arrRow2)."\n";            
            echo "</pre></code>";            
        }


        if ($debugEnabled) {
            echo "<code><pre>";
            echo var_dump($arrRow0);
            echo "</pre></code>";                    }

        if ($debugEnabled) {
            echo '<style>td { background:#eeeeee; }</style>';
            echo $strHtml;
        }

        $matchCount = count($arrRow0);
        if (count($arrRow0) === count($arrRow1) && count($arrRow1) === count($arrRow2)) {
            for($i=0;$i < $matchCount;$i++) {
                $match = array_merge($arrRow0[$i], $arrRow1[$i], $arrRow2[$i]);
                if ($match['kennung'] === false) {
                    $match['kennung'] = standardize($match['klasse'].'-'.$match['kennung_short']);
                }
                $matches[] = $match;
            }

        }

        if ($debugEnabled) {
            echo '<pre>';
            foreach($matches as $m) {
                echo " \nDatum: ".\Date::parse('d.m.Y H:i', $m['tstamp'])."\n";
                var_dump($m);
            }
            echo '</pre>';
        }

        return $matches;
    }

    public static function url_title($str, $separator = 'dash') {
        $search     = ($separator == 'dash') ? '_' : '-';
        $replace    = ($separator == 'dash') ? '-' : '_';

        $trans      = array(
            $search                             => $replace,
            "\s+"                               => $replace,
            "[^a-z0-9".$replace."]"             => '',
            $replace."+"                        => $replace,
            $replace."$"                        => '',
            "^".$replace                        => ''
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

    private static function getTimestamp($strDateTime) {

        if (strlen($strDateTime) === 20) {
            $arr = explode(',', $strDateTime);
            array_shift($arr);
            $objDate = \DateTime::createFromFormat('d.m.y \| H:i', trim(implode('',$arr)));
        }
        elseif (strlen($strDateTime) === 14) {
            $arr = explode(',', $strDateTime);
            array_shift($arr);
            $objDate = \DateTime::createFromFormat('d.m.y \| H:i', trim(implode('',$arr)).' 00:00');
        }
        return ($objDate) ? $objDate->getTimestamp() : 0;
    }


}

