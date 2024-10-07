(function( $ ) {
	'use strict';

	 $(function() {
		 if (document.location.href.endsWith('track-the-click')
	 			 || document.location.href.endsWith('track-the-click-money-page')) {
			 var dateFormat = getDateFormat();
			 var from = $( "#ttc-from" )
					 .datepicker({
						 // showButtonPanel: true,
						 dateFormat: dateFormat,
						 changeMonth: true,
						 numberOfMonths: 2
					 })
					 .on( "change", function() {
						 $( "#ttc-to" ).datepicker( "option", "minDate", getDate( $( this ) ) );
						 gd();
					 }),
				 to = $( "#ttc-to" )
					 .datepicker({
						 // showButtonPanel: true,
						 dateFormat: dateFormat,
						 changeMonth: true,
						 numberOfMonths: 2
					 })
					 .on( "change", function() {
						 $( "#ttc-from" ).datepicker( "option", "maxDate", getDate( $( this ) ) );
						 gd();
					 }),
					group = $( "#ttc-group" ).on( "change", function() {
						gd();
					}),
					grouptime = $( "#ttc-group-time" ).on( "change", function() {
						gd();
					});

			 $( "#ttc-to" ).datepicker( "option", "minDate", getDate( $( "#ttc-from" ) ) );
			 $( "#ttc-from" ).datepicker( "option", "maxDate", getDate( $( "#ttc-to" ) ) );

			 // var old_goToToday = $.datepicker._gotoToday;
			 // $.datepicker._gotoToday = function(id) {
			 //   old_goToToday.call(this,id)
			 //   this._selectDate(id)
			 // };

			 function getDate( element ) {
				 var date;
				 try {
					 date = element.datepicker( "getDate" );
				 } catch( error ) {
					 date = null;
				 }

				 return date;
			 }

			 var config = {
				 type: 'line',
				 options: {
					 responsive: true,
					 maintainAspectRatio: false,
					 scales: [{
						 y: {
							 display: true,
							 min: 0,
							 ticks: {
								 precision: 0
							 }
						 }
					 }]
				 }
			 };
			 window.myChart = new Chart( $( "#ttc-chart" ), config);

			 gd();
		 }
	 } );
})( jQuery );

function updateChart(data) {
	window.myChart.data = data.data;
	//window.myChart.options.scales.xAxes[0].scaleLabel.labelString = 'time period';
	window.myChart.options = data.options;
	window.myChart.update();
}

function updateTable( data ) {
	let doCSV = typeof(getCSV) === "function";
	if (doCSV) {
		beginCSV();
		addCSVData('Link URL', 'Anchor', 'Post', 'Clicks');
	}
	var table = '<table class="ttc-table"><tr><th class="ttc-table">Link URL</th><th class="ttc-table">Anchor</th><th class="ttc-table">Post</th><th class="ttc-table">Clicks</th></tr>';
	var total = 0;
	for (let result of data) {
		table = table
			+ '<tr class="ttc-table">'
			+ '<td class="ttc-table truncate-link"><a href="' + result.url + '"><span title="' + result.url + '">' + result.url + '</span></a></td>'
			+ '<td class="ttc-table"><a href="' + result.url + '">' + result.anchor + '</a></td>'
			+ '<td class="ttc-table"><a href="' + result.link + '">' + result.post_title + '</a></td>'
			+ '<td  class="ttc-table" align="right"><a onclick="gd(' + result.link_id + ')">' + result.hits + '</a></td>'
			+ '</tr>';
			total += parseInt(result.hits);

		if (doCSV) {
			addCSVData(result.url, result.anchor, result.post_title, result.hits);
		}
	}
	table += '<tr><td></td><td></td><td></td><td class="ttc-table" align="right">' + total + '</td></tr></table>';
	if (total  > 0) {
		jQuery( "#ttc-results-table" ).html(table);
	} else {
		jQuery( "#ttc-results-table" ).html("");
	}
}

function updateDomainTable( data ) {
	let doCSV = typeof(getCSV) === "function";
	if (doCSV) {
		beginCSV();
		addCSVData('Domain', 'Clicks');
	}
	var table = '<table class="ttc-table"><tr><th class="ttc-table">Domain</th><th class="ttc-table">Clicks</th></tr>';
	var total = 0;
	for (let result of data) {
		table = table
			+ '<tr class="ttc-table">'
			+ '<td class="ttc-table">' + result.domain + '</td>'
			+ '<td class="ttc-table" align="right">' + result.hits + '</td>'
			+ '</tr>';
			total += parseInt(result.hits);

		if (doCSV) {
			addCSVData(result.domain, result.hits);
		}
	}
	table += '<tr><td></td><td class="ttc-table" align="right">' + total + '</td></tr></table>';
	if (total  > 0) {
		jQuery( "#ttc-results-table" ).html(table);
	} else {
		jQuery( "#ttc-results-table" ).html("");
	}
}

function updateSingleLinkTable( data ) {
	let doCSV = typeof(getCSV) === "function";
	if (doCSV) {
		beginCSV();
		addCSVData('Time');
	}
	var table = '<table class="ttc-table"><tr><th class="ttc-table">Time</th></tr>';
	for (let result of data) {
		table = table
			+ '<tr class="ttc-table">'
			+ '<td class="ttc-table">' + result.time + '</td>'
			+ '</tr>';

		if (doCSV) {
			addCSVData(result.time);
		}
	}
	table += '</table>';
	jQuery( "#ttc-results-table" ).html(table);
}

function updatePageTable( data ) {
	let doCSV = typeof(getCSV) === "function";
	if (doCSV) {
		beginCSV();
		addCSVData('Page', 'Clicks');
	}
	var table = '<table class="ttc-table"><tr><th class="ttc-table">Page</th><th class="ttc-table">Clicks</th></tr>';
	var total = 0;
	for (let result of data) {
		table = table
			+ '<tr class="ttc-table">'
			+ '<td class="ttc-table"><a href="' + result.link + '">' + result.post_title + '</a></td>'
			+ '<td class="ttc-table" align="right">' + result.hits + '</td>'
			+ '</tr>';
			total += parseInt(result.hits);

		if (doCSV) {
			addCSVData(result.post_title, result.hits);
		}
	}
	table += '<tr><td></td><td class="ttc-table" align="right">' + total + '</td></tr></table>';
	if (total  > 0) {
		jQuery( "#ttc-results-table" ).html(table);
	} else {
		jQuery( "#ttc-results-table" ).html("");
	}
}

function updateText( data ) {
	jQuery( "#ttc-results-text" ).html(data);
}

function gd( data ) {
	var fromdate = jQuery( "#ttc-from" ).datepicker( "getDate" );
	var todate = jQuery( "#ttc-to" ).datepicker( "getDate" );
	var fromdatestring = String(fromdate.getFullYear()) + String(fromdate.getMonth()+1).padStart(2,'0') + String(fromdate.getDate()).padStart(2,'0');
	var todatestring = String(todate.getFullYear()) + String(todate.getMonth()+1).padStart(2,'0') + String(todate.getDate()).padStart(2,'0');

	var singlelink = false;
	var url = ajax_var.url + "track-the-click/v1/stats?start=" + fromdatestring + "&end=" + todatestring;
	var group = jQuery( "#ttc-group" ).val();
	var group_time = jQuery( "#ttc-group-time" ).val();
	url += "&group=" + group + "&group_time=" + group_time;
	if ( typeof(data) !== 'undefined' ) {
		singlelink = true;
		url = url + '&link=' + data;
	}
	if ( typeof(getLinkGroup) === 'function') {
		url = url + '&linkgroup=' + getLinkGroup();
	}

	jQuery.ajax({
		url: url,
		type: "GET",
		beforeSend: function ( xhr ) {
			xhr.setRequestHeader( 'X-WP-Nonce', ajax_var.nonce );
		},
		dataType: "json",
		success: function(data) {
			updateChart(data.chart);
			if ( data.layout == 'by-link') {
				updateTable(data.table);
			} else if ( data.layout == 'by-domain' ) {
				updateDomainTable(data.table);
			} else if ( data.layout == 'single-link' ) {
				updateSingleLinkTable(data.table);
			} else if ( data.layout == 'by-page' ) {
				updatePageTable(data.table);
			}
			updateText(data.text);
		},
		error: function(error) {
			console.error(error);
		}
	});
}

function openTab(e, tabName) {
	var i;
	var x = document.getElementsByClassName("ttc-settings-tab");
	for (i = 0; i < x.length; i++) {
		x[i].style.display = "none";
	}
	document.getElementById(tabName).style.display = "block";

	x = document.getElementsByClassName("ttc-settings-tabbutton");
	for (i = 0; i < x.length; i++) {
		x[i].classList.remove("ttc-dark-grey");
	}
	e.currentTarget.classList.add("ttc-dark-grey");
}
