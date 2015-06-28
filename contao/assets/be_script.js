/*
 * contao-fussball
 * http://kozianka.de/
 * Copyright (c) 2011-2015 Martin Kozianka
 *
 */

function editResult(matchId) {
    console.log('EDIT !!!');
    $('value'+ matchId).hide();
    $('field'+ matchId).show();
    $('input'+ matchId).focus()
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

function fussball_match_title(heimspiel, gegner) {
    var team  = $$('#tl_fussball_match input[name=name_external]').get('value')[0];
    var value = (heimspiel) ? team + " - " + gegner : (gegner + " - " + team);
    $$('#tl_fussball_match input[name=title]').set('value', value);
}

window.addEvent('domready', function() {

    // init
    heimspiel = $$('#tl_fussball_match input[name=heimspiel]').get('checked')[1];
    gegner    = $$('#tl_fussball_match select[name=gegner]').get('value')[0];
    fussball_match_title(heimspiel, gegner);

    // fussball_widget
    $$('#tl_fussball_match input[name=heimspiel]').addEvent('change', function(event) {
        var gegner    = $$('#tl_fussball_match select[name=gegner]').get('value')[0];
        fussball_match_title(this.checked, gegner);
    });

    $$('#tl_fussball_match select[name=gegner]').addEvent('change', function(event) {
        var heimspiel = $$('#tl_fussball_match input[name=heimspiel]').get('checked')[1];
        fussball_match_title(heimspiel, this.value);
    });


    /* Awesomplete
    var input = document.getElementById('ctrl_gegner');
    if (input) {
        new Awesomplete(input, {list: "#gegner_list"});
    }
    */

});

