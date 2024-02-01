$(function () {
	var create_project = $(".create_project");
	
	if (popup && on_success_js_func_name && window.parent != window) {
		//hide the close button for the popup
		window.parent.document.body.classList.add("popup_without_popup_close");
		
		create_project.addClass("popup_without_popup_close");
	}
	else if (window.parent != window) {
		//show the close button for the popup
		window.parent.document.body.classList.remove("popup_without_popup_close");
	}
	
	create_project.removeClass("changing_to_step");
});

function cancel() { //This function is only used on a popup
	var create_project = $(".create_project");
	
	if (popup && on_success_js_func_name && window.parent != window) {
		window.parent.document.body.classList.remove("popup_without_popup_close");
		create_project.removeClass("popup_without_popup_close");
	}
	
	create_project.children(".creation_step").children().not(".top_bar").remove();
	
	//call onSucessfullProjectCreation
	var url = null;
	
	if (typeof window.parent.parent.goTo == "function" && window.parent.parent != window.parent) //if inside of the admin_home_project.php which is inside of the admin_advanced.php
		url = window.parent.document.location;
	else if (window.parent != window) { //if inside of the admin_advanced.php
		if (typeof window.parent.goTo == "function")
			url = MyJSLib.CookieHandler.getCookie('default_page');
		else
			url = window.parent.document.location;
	}
	//else - should never enter here bc this function will only be called when there is a popup, this is, when: window.parent != window
	
	onSucessfullProjectCreation(url);
}

function goToProjectDashboard(url) {
	onSucessfullProjectCreation(url);
}

function onSucessfullProjectCreation(url) {
	var func = null;
	
	if (on_success_js_func_name) {
		eval("func = typeof window.parent." + on_success_js_func_name + " == 'function' ? window.parent." + on_success_js_func_name + " : null;");

		if (!func) //could be inside of the admin_home_project.php which is inside of the admin_advanced.php
			eval("func = typeof window.parent.parent." + on_success_js_func_name + " == 'function' ? window.parent.parent." + on_success_js_func_name + " : null;");

		if (func)
			func(on_success_js_func_opts);
	}
	
	if (!func && url) {
		if (typeof window.parent.parent.goToHandler == "function" && window.parent.parent != window.parent) //if inside of the admin_home_project.php which is inside of the admin_advanced.php
			window.parent.document.location = url;
		else if (window.parent != window) { //if inside of the admin_advanced.php
			if (typeof window.parent.goToHandler == "function") {
				window.parent.goToHandler(url);
				window.parent.MyFancyPopup.hidePopup();
			}
			else //if in an independent window
				window.parent.document.location = url;
		}
		else
			document.location = url;
	}
}

function appendDataToForm(oForm, data, prefix_name) {
	if ($.isPlainObject(data) || $.isArray(data))
		$.each(data, function(k, v) {
			var name = prefix_name ? prefix_name + "[" + k + "]" : k;
			
			if ($.isPlainObject(v) || $.isArray(v))
				appendDataToForm(oForm, v, name);
			else {
				var input = $('<input type="hidden" name="' + name + '"/>');
				input.val(v);
				
				oForm.append(input);
			}
		});
}

function goToUrl(url) {
	var create_project = $(".create_project");
	create_project.addClass("changing_to_step");
	
	document.location = url;
}

function postToUrl(url, data) {
	var create_project = $(".create_project");
	create_project.addClass("changing_to_step");
	
	var oForm = $('<form method="post" action="' + url + '"></form>');
	oForm.hide();
	
	appendDataToForm(oForm, data);
	create_project.append(oForm);
	
	oForm.submit();
}

function createProject(elm) {
	var create_project = $(".create_project");
	create_project.addClass("changing_to_step");
	
	var btn = create_project.find(".edit_project_details .buttons").find("input, button");
	btn.trigger("click");
	
	setTimeout(function() {
		if (!btn.hasClass("loading"))
			create_project.removeClass("changing_to_step");
	}, 1000);
}

function initStorePrograms() {
	if (get_store_programs_url)
		$.ajaxSetup({
			complete: function(jqXHR, textStatus) {
				if (jqXHR.status == 200 && this.url.indexOf(get_store_programs_url) != -1) {
					var create_project = $(".create_project");
					var a = create_project.find(".top_bar.create_project_top_bar header > ul > li.continue > a");
					
					if (!loaded_programs || $.isEmptyObject(loaded_programs))
						a.addClass("active");
					else {
						var lis = create_project.find(".install_program .step_0 .install_program_step_0_with_tabs .install_store_program > ul > li");
						
						lis.each(function(idx, item) {
							item = $(item);
							
							if (!item.attr("url"))
								item.hide();
							
							item.on("click", function(event) {
								var li = $(this);
								var is_selected = li.hasClass("selected");
								
								lis.removeClass("selected");
								a.removeClass("active");
								
								if (!is_selected) {
									li.addClass("selected");
									a.addClass("active");
								}
							});
						});
					}
				}
		    	}
		});
}

function chooseProgram(elm, choose_url, post_data) {
	//if no data in store, then redirect to the final creation_step
	if (!loaded_programs || $.isEmptyObject(loaded_programs)) {
		choose_url = choose_url.indexOf("#") != -1 ? choose_url.substr(0, choose_url.indexOf("#")) : choose_url; //remove # so it can refresh page
		choose_url = choose_url.replace(/creation_step=([^&]*)&?/g, ""); //erase previous creation_step attribute
		choose_url += choose_url.indexOf("?") != -1 ? "" : "?"; //add "?" if apply
		choose_url += "&creation_step=2"; //add creation_step to show successfull message
		choose_url = choose_url.replace(/\?&+/, "?"); //replace "?&&&" with "?"
	}
	else {
		var li = $(".create_project .install_program .step_0 .install_program_step_0_with_tabs .install_store_program > ul > li.selected");
		
		if (!li[0])
			StatusMessageHandler.showError("You must select a program first.\nOr go back and click in the 'Empty Project' button.");
		else {
			post_data["step"] = 1;
			post_data["program_url"] = li.attr("url");
		}
	}
	
	postToUrl(choose_url, post_data);
}

function installProgramStep(elm, data) {
	$(elm).removeAttr("onClick");
	
	var create_project = $(".create_project");
	create_project.addClass("changing_to_step");
	
	var install_program = create_project.find(".install_program");
	var oForm = install_program.find("form").first();
	
	appendDataToForm(oForm, data);
	
	var a = install_program.find(".top_bar > header > ul li.continue > a");
	a.trigger("click");
	
	setTimeout(function() {
		if (!a.hasClass("loading"))
			create_project.removeClass("changing_to_step");
	}, 1000);
}
