var container = document.querySelector(".admin-page-content");
//prepareHtml(container);

//preparing bootstrap classes
function prepareHtml(container) {
	if (container) {
		//preparing list-container tables
		var tables = container.querySelectorAll(".list-container table.list-table, .list_container table.list_table");
		for (var i = 0, l = tables.length; i < l; i++) {
			var table = tables[i];
			var p = table.parentNode;
			p.classList.add("table-responsive");
			
			if (!table.classList.contains("table"))
				table.classList.add("table", "table-striped", "table-hover", "table-sm");
			
			var inputs = table.querySelectorAll("input, select");
			for (var j = 0, il = inputs.length; j < il; j++) {
				var input = inputs[j];
				
				if (input.type != "checkbox" && input.type != "radio")
					input.classList.add("h-100", "bg-transparent");
			}
			
			var textareas = table.querySelectorAll("textarea");
			for (var j = 0, il = textareas.length; j < il; j++)
				textareas[j].classList.add("bg-transparent");
		}
		
		//preparing fields-container inputs
		var fields = container.querySelectorAll(".fields-container .field, .form_fields > .form_field");
		for (var i = 0, l = fields.length; i < l; i++) {
			var field = fields[i];
			var label = field.querySelector("label");
			var input = field.querySelector("input:not([type=hidden]), textarea, select, .field-value");
			var label_contains_class_col = label && label.hasAttribute("class") && label.getAttribute("class").match(/(^col-|\scol-)/);
			var input_contains_class_col = input && input.hasAttribute("class") && input.getAttribute("class").match(/(^col-|\scol-)/);
			
			field.classList.add("form-group", "row");
			
			if (input) {
				var is_hidden_input = input.classList.contains("field-value");
				
				if (input.nodeName.toUpperCase() == "TEXTAREA") {
					label && !label_contains_class_col && label.classList.add("col-12");
					!input_contains_class_col && input.classList.add("col-12");
				}
				else {
					label && !label_contains_class_col && label.classList.add((is_hidden_input ? "col-6" : "col-12"), "col-sm-4", "col-lg-2"); //if no input it means the value will be shown in a span
					!input_contains_class_col && input.classList.add((is_hidden_input ? "col-6" : "col-12"), "col-sm-8", "col-lg-10");
				}
			}
			else if (label && !label_contains_class_col)
				label.classList.add("col-12", "col-sm-4", "col-lg-2");
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
			var input_contains_class = input.hasAttribute("class") && input.getAttribute("class").match(/(^|\s)btn(\s|\-|$)/);
			input.classList.remove("form-control");
			
			if (!input_contains_class) {
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
		
		//make list_containers and fields_containers visible
		var list_containers = container.querySelectorAll(".list-container");
		for (var i = 0, l = list_containers.length; i < l; i++)
			list_containers[i].style.visibility = "visible";
		
		var fields_containers = container.querySelectorAll(".fields-container");
		for (var i = 0, l = fields_containers.length; i < l; i++)
			fields_containers[i].style.visibility = "visible";
	}
}
