/*
 * Fussball Widget
 * http://kozianka-online.de/
 *
 * Copyright (c) 2011 Martin Kozianka
 *
 * Author: Martin Kozianka
 *
 */
 
function drawVisualization(gData) {
	// Some raw data (not necessarily accurate)
	var areas     = gData.label;
	var dataLabel = gData.dataLabel;
	var areaData  = gData.data;
	

	// Create and populate the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Platz');
	for (var i = 0; i < areas.length; ++i) {
		data.addColumn('number', areas[i]);
	}
	data.addRows(dataLabel.length);
	for (var i = 0; i < dataLabel.length; ++i) {
		data.setCell(i, 0, dataLabel[i]);
	}
	
	for (var i = 0; i < areas.length; ++i) {
		var area = areaData[i];
		for (var label = 0; label < dataLabel.length; ++label) {
			data.setCell(label, i + 1, area[label]);
		}
	}
	
	// Create and draw the visualization.
	var ac = new google.visualization.LineChart(document.getElementById(gData.graph_id));
	ac.draw(data, {
		titlePosition: 'none',
		isStacked: true,
		width: gData.width,
		height: gData.height,		
		chartArea:{left:"10px",top:"10px",width:"90%",height:"80%"},
		legend: 'bottom',
		pointSize: '5',
		vAxis: {
			direction: -1,
			viewWindowMode: 'explicit', viewWindow: {min: 1, max: gData.maxValue}
		},
		hAxis: {title: "Spieltag"}
	});
}
 
 
var FussballWidget = function() {
	var id = null;
	var div_id = null;
	var team = null;
	var wettbewerb = null;
	
return {

	ergebnisse: function() {
		this.wettbewerb.zeigeWettbewerb(this.div_id);
		return false;
	},
	tabelle: function() {
		this.wettbewerb.zeigeTabelle(this.div_id);
		return false;

	},
	init: function(id, saison, wettbewerbs_id, team) {
		// id setzen
		this.id = id;
		this.div_id = 'id' + this.id;
		
		this.wettbewerb = new fussballdeAPI();
		this.wettbewerb.setzeSaison(saison);
		this.wettbewerb.setzeWettbewerbID(wettbewerbs_id);
		
		if (team.length > 0) {
			this.team = team;
			this.highlight_team();
		}
	},

	highlight_team: function() {
		window.setTimeout(this.id + '.highlight_team()', 2000);

		divEl = document.getElementById('fussballdeAPI');
		if (divEl == null || document.getElementById('highlighted_row') != null) { 
			return false;
		}

		a_nodes = divEl.getElementsByTagName('a');

		for (i = 0;i < a_nodes.length; i++) {
			node = a_nodes[i].firstChild;
			if (node.nodeType == 3 && node.nodeValue.indexOf(this.team) != -1) {
				row = node.parentNode.parentNode.parentNode;
				row.id ="fussball_widget_highlighted_row";
				
				return true;
			}
		}
		
	}

};

};