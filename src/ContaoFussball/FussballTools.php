<?php

namespace ContaoFussball;

class FussballTools {

    private static $strAbgesagt = 'Abg.';

    public static function getMatches($action, $id, $from, $till) {

        $url = $action."?team-id=".$id."&show-venues=true"
            ."&date-from=".$from."&date-to=".$till
            ."&_=".time();

        $mainContent    = file_get_contents($url);
        $mainContent    = str_replace(array('%0A','var mainContent = "', '<wbr>'), '', $mainContent);
        $mainContent    = htmlspecialchars_decode($mainContent);
        $mainContent    = substr($mainContent, 0, strlen($mainContent) - 2);

        $html           = \HtmlDomParser::str_get_html($mainContent);
        $matches        = array();
        $match_date     = '';
        $match          = null;

        $table = $html->find('table.egmMatchesTable', 0);
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
                else if (strpos($content, '//') !== false) {
                    // Location


                    $location = preg_replace("/[^a-zA-Z0-9_äöüÄÖÜß\.\- \/]/" , "" , $content);
                    $location = str_replace(" // ","\n", $location);

                    // Wenn eine Location gefunden wurde gehört diese zum "aktuellen" Spiel
                    if (is_array($match) && !array_key_exists('loc', $match)) {
                        $match['loc'] = $location;
                    }

                }

            }
            else if ($count > 4) {

                if ($match !== null) {
                    $matches[] = $match;

                }

                // Match
                $match            = array();
                $match['date']    = $match_date;
                $match['id']      = trim($tr->find('td', 0)->find('text', 0)->plaintext);


                $timeEl          = $tr->find('td', 1)->find('text', 0);
                $match['time']   = ($timeEl != null) ? trim($timeEl->plaintext) : '';


                $a = $tr->find('td', 2)->find('a', 0);
                $match['manh']   = ($a != null) ? trim($a->find('text', 0)->plaintext) : trim($tr->find('td', 2)->plaintext);

                // Spalte 3 ist der Bindestrich

                $a = $tr->find('td', 4)->find('a', 0);
                $match['mana']   = ($a != null) ? trim($a->find('text', 0)->plaintext) : trim($tr->find('td', 4)->plaintext);

                // Ergebnis
                $textEl          = $tr->find('td', 5)->find('text', 0);
                $ergebnis        = ($textEl != null) ? str_replace('*','', trim($textEl->plaintext)) : '';
                $match['erg']    = $ergebnis;

                if (self::$strAbgesagt == $ergebnis) {
                    $match['loc'] = 'Spiel abgesagt.';
                }

                $match['klasse'] = trim($tr->find('td', 6)->find('text', 0)->plaintext);
                $match['typ']    = trim(str_replace('&nbsp;', '', $tr->find('td', 7)->find('text', 0)->plaintext));

                // Kennung

                $a                = $tr->find('td', 7)->find('a', 0);
                $kennung          = ($a != null) ? preg_replace("#http:\/\/.*\/spiel\/#", "", trim($a->href)) : 'SPIELFREI';
                $match['kennung'] = $kennung;

            }

        } //foreach

        // Das letzte Spiel muss auch noch mit
        if ($match !== null && sizeof($match) > 0) {
            $matches[] = $match;
        }

        foreach ($matches as &$oneMatch) {
            foreach ($oneMatch as $key => $value) {
                $oneMatch[$key] = str_replace(
                    array('&nbsp;', '&#8209;'),
                    array(' '     ,'-'),
                    strip_tags($value)
                );
            }
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

    public static function getTimestampFromDateAndTimeString($str, $time) {

        $tmparr = explode(",", $str);
        $tmparr = explode(".", $tmparr['1']);

        $d = trim($tmparr[0]);
        $m = $tmparr[1];
        $y = $tmparr[2];


        $h = "12"; $i= "00";
        if (strpos($time, ":") !== false) {
            $tmparr = explode(":", $time);
            $h = $tmparr[0];
            $i = $tmparr[1];
        }

        $tstamp = strtotime("$y-$m-$d $h:$i:00");
        return $tstamp;
    }

}

