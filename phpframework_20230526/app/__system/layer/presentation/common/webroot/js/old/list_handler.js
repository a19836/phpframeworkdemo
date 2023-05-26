function deleteItem(url) {
	if (confirm("The selected item will be deleted.\nDo you want to continue?")) {
		$.ajax({
		  url: url,
		  success: function(data, textStatus, jqXHR) {
		  	var firstLine = data.split("\n");
		  	firstLine = firstLine[0];
		  	
		  	if (firstLine == 1) {
		  		window.location.reload()
		  	}
		  	else if (data == "") {
		  		window.location.reload()
		  	}
		  	else {
		  		alert('There was an error trying to delete this item.');
		  	}
		  },
		  dataType: "html"
		});
	}
}
