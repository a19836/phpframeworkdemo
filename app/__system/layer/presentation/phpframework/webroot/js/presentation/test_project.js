$(function () {
	var iframe = $(".test_project > iframe");
		
	iframe.load(function() {
		var style = '<style>' +
'::-webkit-scrollbar {' +
'	width:5px;' +
'	height:5px;' +
'}' +
//track
'::-webkit-scrollbar-track {' +
'    -webkit-border-radius:5px;' +
'    border-radius:5px;' +
'    -webkit-box-shadow:inset 0 0 6px rgba(0,0,0, 0); ' +
'    background-color:transparent;' +
'}' +
//Handle
'::-webkit-scrollbar-thumb {' +
'    -webkit-border-radius:5px;' +
'    border-radius:5px;' +
'    background:#83889E;' +
'}' +
'::-webkit-scrollbar-thumb:window-inactive {' +
'	/*background:rgba(0,0,0,0.2);*/ ' +
'}' +
'</style>';
		var iframe_head = this.contentWindow.document.head;
		$(iframe_head).prepend(style);
	});
});

function addVar(elm, type) {
	var tbody = $(elm).parent().closest("table").children("tbody");
	tbody.children(".no_vars").hide();
	var index = getListNewIndex(tbody);
	
	var row = vars_html.replace(/#type#/g, type).replace(/#index#/g, index).replace(/#name#/g, "").replace(/#value#/g, "");
	row = $(row);
	tbody.append(row);
	
	return row;
}

function removeVar(elm) {
	var tr = $(elm).parent().closest("tr");
	var tbody = tr.parent();
	
	tr.remove();
	
	if (tbody.children().length == 1)
		tbody.children(".no_vars").show();
}

function toggleSettings(elm) {
	elm = $(elm);
	elm.parent().toggleClass("hide_settings");
	
	if (elm.hasClass("maximize"))
		elm.removeClass("maximize").addClass("minimize");
	else
		elm.removeClass("minimize").addClass("maximize");
}
