var container = document.querySelector(".admin-page-content");
prepareHtml(container);

//preparing bootstrap classes
function prepareHtml(container) {
	if (container) {
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
	}
	
	//preparing login page
	var login = document.querySelector(".login");
	
	if (login) {
		var register = login.querySelector(".register");
		var forgot_credentials = login.querySelector(".forgot_credentials");
		
		footer.innerHTML = "";
		
		if (register) {
			footer.appendChild(register);
		}
		
		if (forgot_credentials) {
			footer.appendChild(forgot_credentials);
		}
	}
    
    //preparing forgot_credentials page
	var forgot_credentials = document.querySelector(".forgot_credentials");
	
	if (forgot_credentials) {
	    //preparing fields-container inputs
    	var fields = forgot_credentials.querySelectorAll(".form_fields > .form_field");
    	for (var i = 0, l = fields.length; i < l; i++) {
    		var field = fields[i];
    		var label = field.querySelector("label");
    		var input = field.querySelector("input:not([type=hidden]), textarea, select, .field-value");
    		
    		field.classList.add("mb-3");//"form-group", "row");
    		
    		if (label) {
    			label.classList.add("form-label", "small", "mb-0");//, "col-12", "col-sm-3", "col-lg-3");
    		}
    		
    		if (input) 
    			input.classList.add("form-control");//, "col-12", "col-sm-9", "col-lg-9");
    	}
    	
    	//preparing buttons
    	var inputs = forgot_credentials.querySelectorAll(".button > input, .submit_button > input");
    	for (var i = 0, l = inputs.length; i < l; i++) {
    		var input = inputs[i];
    		var p = input.parentNode;
    		input.classList.remove("form-control");
    		input.classList.add("btn");
    		
    		if (p.classList.contains("button-delete") || p.classList.contains("button_delete")) {
    			input.classList.add("btn-danger");
    			input.classList.add("bg-danger");
    		}
    		else if (p.classList.contains("button-save") || p.classList.contains("button-update") || p.classList.contains("button_save") || p.classList.contains("button_update")) {
    			input.classList.add("btn-primary");
    			input.classList.add("bg-primary");
    		}
    		else if (p.classList.contains("button-insert") || p.classList.contains("button_insert")) {
    			input.classList.add("btn-success");
    			input.classList.add("bg-success");
    		}
    		else if (p.classList.contains("submit_button")) {
    			input.classList.add("btn-danger");
    			input.classList.add("bg-danger");
    		}
    		else {
    			input.classList.add("btn-secondary");
    			input.classList.add("bg-secondary");
    		}
    		
    		if (p.classList.contains("submit_button") && !p.classList.contains("button"))
    			p.classList.add("button");
    	}
	}
}
