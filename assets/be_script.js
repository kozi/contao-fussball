window.addEvent('domready', function() {
	el = $('ctrl_fussball_results');
	
	if (el) {
		console.log(el);
		
		el.getElements('input.tl_checkbox').addEvent('change', function(event) {
			var row = $(this).getParent().getParent().getParent();
			fussball_updateRow(this.checked, row);
		});

		$('ctrl_fussball_team').addEvent('change', function(event) {
			el.getElements('input.tl_checkbox').each(function(inp, index) {
				var row = inp.getParent().getParent().getParent();
				fussball_updateRow(inp.checked, row);
			});
		});
		
		
		el.getElements('input.tl_checkbox').each(function(inp, index) {
			var row = inp.getParent().getParent().getParent();
			fussball_updateRow(inp.checked, row);
		});
				
		MultiColumnWizard.addOperationUpdateCallback('copy', fussball_column_callback);
		
	}

});

function fussball_updateRow(check, row) {
	var where   = (check) ? 'before' : 'after';
	var name    = $('ctrl_fussball_team').get('value');
	var element = new Element('span', {class: 'fussball_team tl_text', 
		text: name});
	row.getElements('span.fussball_team').destroy();
	element.inject(row.getElement('td.fussball_opponent input'), where);
	
	// TODO :: flip result
}

function fussball_column_callback(el,row) {
	$('ctrl_fussball_results').getElements('input.tl_checkbox').addEvent('change', function(event) {
		var row = $(this).getParent().getParent().getParent();
		fussball_updateRow(this.checked, row);
	});
}


// 
// 