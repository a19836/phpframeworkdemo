var container = document.querySelector(".admin-page-content");
prepareHtml(container);

//preparing bootstrap classes
function prepareHtml(container) {
	//preparing fields-container inputs
	var fields = container.querySelectorAll(".form_fields > .form_field");
	for (var i = 0, l = fields.length; i < l; i++) {
		var field = fields[i];
		var label = field.querySelector("label");
		var input = field.querySelector("input:not([type=hidden]), textarea, select, .field-value");
		
		field.classList.remove("row");
		
		if (label) {
			label.classList.add("small");
			label.classList.remove("col-12", "col-sm-4", "col-lg-2");
		}
		
		if (input) 
			input.classList.remove("col-12", "col-sm-8", "col-lg-10");
	}
	
	var footer = null;
	for (var i = 0, l = container.childNodes.length; i < l; i++) {
		var child = container.childNodes[i];
		
		if (child && child.nodeType == Node.ELEMENT_NODE && child.matches(".card"))
			for (var j = 0, l2 = child.childNodes.length; j < l2; j++) {
				var sub_child = child.childNodes[j];
				
				if (sub_child && sub_child.nodeType == Node.ELEMENT_NODE && sub_child.matches(".card-footer")) {
					footer = sub_child;
					i = l;
					break;
				}
			}
	}
	
	//preparing login page
	var login = document.querySelector(".login");
	
	if (login) {
		var register = login.querySelector(".register");
		var forgot_credentials = login.querySelector(".forgot_credentials");
		
		footer.innerHTML = "";
		
		if (register) {
			footer.appendChild(register);
			register.classList.add("float-left");
		}
		
		if (forgot_credentials) {
			footer.appendChild(forgot_credentials);
			forgot_credentials.classList.add("float-right");
		}
	}
}
