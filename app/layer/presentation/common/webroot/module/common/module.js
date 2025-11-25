/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

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
