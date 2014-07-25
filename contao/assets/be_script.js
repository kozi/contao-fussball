/*
 * fussball_widget
 * http://kozianka.de/
 * Copyright (c) 2011-2013 Martin Kozianka
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
            console.log('SAVE !!!');
        },
        url: 'contao/main.php?do=fussball_matches&key=result&match=' + matchId + '&result=' + matchResult,
        method:'get'
    }).send();
}

window.addEvent('domready', function() {
    // fussball_widget
});

