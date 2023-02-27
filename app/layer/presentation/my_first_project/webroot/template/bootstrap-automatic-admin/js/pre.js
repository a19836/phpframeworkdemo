var container = document.querySelector(".admin-page-content");
prepareHtml(container);

//preparing bootstrap classes
function prepareHtml(container) {
	if (container)  {
		//preparing list-container tables
		var tables = container.querySelectorAll(".list-container table.list-table, .list_container table.list_table");
		for (var i = 0, l = tables.length; i < l; i++) {
			var table = tables[i];
			var p = table.parentNode;
			p.classList.add("table-responsive");
			table.classList.add("table", "table-bordered", "table-striped", "table-hover", "table-sm");
			
			var inputs = container.querySelectorAll("input, select");
			for (var j = 0, il = inputs.length; j < il; j++) {
				var input = inputs[j];
				
				if (input.type != "checkbox" && input.type != "radio")
					input.classList.add("h-100");
			}
		}
		
		//preparing fields-container inputs
		var fields = container.querySelectorAll(".fields-container .field, .form_fields > .form_field");
		for (var i = 0, l = fields.length; i < l; i++) {
			var field = fields[i];
			var label = field.querySelector("label");
			var input = field.querySelector("input:not([type=hidden]), textarea, select, .field-value");
			
			field.classList.add("form-group", "row");
			
			if (input) {
				var is_hidden_input = input.classList.contains("field-value");
				
				if (input.nodeName.toUpperCase() == "TEXTAREA") {
					label && label.classList.add("col-12");
					input.classList.add("col-12");
				}
				else {
					label && label.classList.add((is_hidden_input ? "col-6" : "col-12"), "col-sm-4", "col-lg-2"); //if no input it means the value will be shown in a span
					input.classList.add((is_hidden_input ? "col-6" : "col-12"), "col-sm-8", "col-lg-10");
				}
			}
			else if (label)
				label && label.classList.add("col-12", "col-sm-4", "col-lg-2");
		}
		
		//preparing all inputs (from .fields-container and .list-container)
		var inputs = container.querySelectorAll("input, textarea, select");
		for (var i = 0, l = inputs.length; i < l; i++) {
			var input = inputs[i];
			
			if (input.type != "checkbox" && input.type != "radio")
				input.classList.add("form-control");
		}
		
		//preparing buttons
		var inputs = container.querySelectorAll(".button > input, .submit_button > input");
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
				input.classList.add("btn-primary");
				input.classList.add("bg-primary");
			}
			else {
				input.classList.add("btn-secondary");
				input.classList.add("bg-secondary");
			}
			
			if (p.classList.contains("submit_button") && !p.classList.contains("button"))
				p.classList.add("button");
		}
		
		//preparing pagination buttons
		var inputs = container.querySelectorAll(".paging input, .paging select, .paging select");
		for (var i = 0, l = inputs.length; i < l; i++) {
			var input = inputs[i];
			input.classList.remove("form-control");
		}
	}
	
	//make list_containers and fields_containers visible
	document.body.classList.add("visible");
}
