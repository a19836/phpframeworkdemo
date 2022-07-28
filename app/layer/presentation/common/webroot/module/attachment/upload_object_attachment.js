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
