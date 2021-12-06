if (typeof $ == "undefined")
	alert("Jquery library must be loaded first!");

/*
This is used in the modules
*/

function deleteItem(elm, url) {
	elm = $(elm);
	
	if (confirm("Do you wish to delete this row?")) {
		$.ajax({
			url: url,
			success: function(data) {
				if (data == "1") {
					elm.parent().closest("tr, li").remove();
				}
				else {
					alert("Error trying to delete this item.\nPlease try again..." + (data ? "\n\n" + data : ""));
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				var msg = "Error trying to delete this item.\nPlease try again...\n\nStatus:" + textStatus + "\nException:" + errorThrown + (jqXHR && jqXHR.responseText ? "\nMessage:" + $(jqXHR.responseText).text() : "");
				
				alert(msg);
			},
		});
	}
}
