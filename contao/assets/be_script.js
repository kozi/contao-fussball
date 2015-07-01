/*
 * contao-fussball
 * http://kozianka.de/
 * Copyright (c) 2011-2015 Martin Kozianka
 *
 */

function editResult(matchId) {
    $('value'+ matchId).hide();
    $('field'+ matchId).show();
    $('input'+ matchId).focus();
    $('input'+ matchId).select();
}

function saveResult(matchId) {
    var matchResult = $('input'+ matchId).get('value');
    new Request({
        onSuccess: function(resultString) {
            if (resultString.length > 0) {
                $('field'+ matchId).hide();
                $('value'+ matchId).set('text', resultString);
                $('value'+ matchId).show();
            }
        },
        url: 'contao/main.php?do=fussball_matches&key=result&match=' + matchId + '&result=' + matchResult,
        method:'get'
    }).send();
}

var fussball_match_title = function() {
    if ($$('#tl_fussball_match select[name=gegner]').getLast() != null)
    {
        var gegner    = $$('#tl_fussball_match select[name=gegner]').getSelected()[0].get('text');
        var heimspiel = $$('#tl_fussball_match input[name=heimspiel]').get('checked')[1];
        var team      = $$('#tl_fussball_match input[name=name_external]').get('value')[0];

        var value     = (heimspiel) ? team + " - " + gegner : (gegner + " - " + team);
        $$('#tl_fussball_match input[name=title]').set('value', value);
    }
}

window.addEvent('domready', function()
{
    // init
    fussball_match_title();

    // fussball_widget
    $$('#tl_fussball_match input[name=heimspiel]').addEvent('change', fussball_match_title);
    $$('#tl_fussball_match select[name=gegner]').addEvent('change', fussball_match_title);

    $$('.fussball_event').each(function(el) {
        el.parentElement.addClass('fussball_event_wrap');
    });
});

