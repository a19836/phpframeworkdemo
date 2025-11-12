/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

function addNewUploadItem(elm) {
	//var upload_item = $(upload_item_html);
	//$(elm).parent().parent().children(".upload_items").append(upload_item);
	
	var upload_items = elm.parentNode.parentNode.querySelector(".upload_items");
	var upload_item = null;
	
	if (upload_items) {
		var aux = document.createElement('div');
		aux.innerHTML = upload_item_html;

		while (aux.firstChild)
			upload_item = upload_items.appendChild(aux.firstChild);
	}
	
	return upload_item;
}
function removeUploadItem(elm) {
	//$(elm).parent().remove();
	var p = elm.parentNode;
	p.parentNode.removeChild(p);
}
