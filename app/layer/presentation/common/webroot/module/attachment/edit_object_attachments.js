function initAttachments(table) { //tables can be an array of tables
	if (table) {
		if (typeof $ == "function" && typeof $(table).sortable == "function")
			$(table).sortable({
			    	axis: 'y',
			    	handle: 'td.icons .move',
			    	placeholder: "drag_and_drop_next_item",
			});
		else { //hide move icons
			var tables = null;
			
			if (typeof $ == "function" && typeof table == "object" && table.length > 0 && table[0])
				tables = table; //jquery object
			else if (table && table.nodeType == Node.ELEMENT_NODE)
				tables = [table]; //native object
			else
				tables = table; //in case of something else, like from a different lib from mootools...
			
			if (tables && tables.length > 0)
				for (var i = 0; i < tables.length; i++) {
					table = tables[i];
					
					if (table && table.nodeType == Node.ELEMENT_NODE) {
						var icons = table.querySelectorAll("tbody .icons .move");
						
						if (icons)
							for (var j = 0; j < icons.length; j++)
								icons[j].style.display = "none";
					}
				}
		}
	}
}

function addAttachment(elm) {
	//var html = $(new_object_attachment_html);
	var html = null;
	
	//var table = $(elm).parent().parent().parent().parent().children("tbody");
	var table = elm.parentNode.parentNode.parentNode.parentNode.querySelector("tbody");
	
	if (table) {
		//table.append(html);
		var aux = document.createElement('tbody');
		aux.innerHTML = new_object_attachment_html;
		
		while (aux.firstChild)
			html = table.appendChild(aux.firstChild);
		
		//table.children(".empty_object_attachments").hide();
		var empty_object_attachments = table.querySelector(".empty_object_attachments");
		if (empty_object_attachments)
			empty_object_attachments.style.display = "none";
		
		initAttachments(table);
	}
	
	return html;
}

function removeAttachment(elm) {
	//var tr = $(elm).parent().parent();
	var tr = elm.parentNode.parentNode;
	
	//var table = tr.parent();
	var table = tr.parentNode;
	
	//tr.remove();
	table.removeChild(tr);
	
	//if (table.children("tr").length <= 1) {
	if (table.querySelectorAll("tr").length <= 1) {
		//table.children(".empty_object_attachments").show();
		var empty_object_attachments = table.querySelector(".empty_object_attachments");
		
		if (empty_object_attachments)
			empty_object_attachments.style.display = "table-row";
	}
}

function initAttachmentsMoveIcons(main_selector) {
	var tables = null;
	
	if (typeof $ == "function")
		tables = $(main_selector + " table tbody");
	else
		tables = document.querySelectorAll(main_selector + " table tbody");
	
	if (tables)
		initAttachments(tables);
}

function moveUp(elm) {
	//var tr = $(elm).parent().parent();
	var tr = elm.parentNode.parentNode;
	
	//var prev = tr.prev();
	var prev = tr.previousElementSibling;
	
	//only move is not the first row
	//if (prev[0] && prev.children("th").length == 0)//if contains TH, it means the previous row is the table header
	if (prev && prev.querySelectorAll("th").length == 0)//if contains TH, it means the previous row is the table header
		//tr.parent()[0].insertBefore(tr[0], prev[0]);
		tr.parentNode.insertBefore(tr, prev);
}

function moveDown(elm) {
	//var tr = $(elm).parent().parent();
	var tr = elm.parentNode.parentNode;
	
	//var next = tr.next();
	var next = tr.nextElementSibling;
	
	//if (next[0])
	if (next)
		//tr.parent()[0].insertBefore(next[0], tr[0]);
		tr.parentNode.insertBefore(next, tr);
}
