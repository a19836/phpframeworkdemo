$(function () {
	var create_entity = $(".create_entity");
	
	if (popup && on_success_js_func_name && window.parent != window) {
		//hide the close button for the popup
		window.parent.document.body.classList.add("popup_without_popup_close");
		
		create_entity.addClass("popup_without_popup_close");
	}
	else if (window.parent != window) {
		//show the close button for the popup
		window.parent.document.body.classList.remove("popup_without_popup_close");
	}
	
	create_entity.removeClass("changing_to_step");
});

function cancel() { //This function is only used on a popup
	var create_entity = $(".create_entity");
	
	if (popup && on_success_js_func_name && window.parent != window) {
		window.parent.document.body.classList.remove("popup_without_popup_close");
		create_entity.removeClass("popup_without_popup_close");
	}
	
	create_entity.children(".creation_step").children().not(".top_bar").remove();
	
	//call onSucessfullPageCreation
	onSucessfullPageCreation();
}

function onSucessfullPageCreation() {
	var func = null;
	
	if (on_success_js_func_name) {
		eval("func = typeof window.parent." + on_success_js_func_name + " == 'function' ? window.parent." + on_success_js_func_name + " : null;");

		if (!func) //could be inside of the admin_home_project.php which is inside of the admin_advanced.php
			eval("func = typeof window.parent.parent." + on_success_js_func_name + " == 'function' ? window.parent.parent." + on_success_js_func_name + " : null;");

		if (func)
			func();
	}
	
	if (!func && edit_entity_url) {
		if (typeof window.parent.parent.goToHandler == "function" && window.parent.parent != window.parent) //if inside of the admin_home_project.php which is inside of the admin_advanced.php
			window.parent.parent.goToHandler(edit_entity_url);
		else if (window.parent != window) { //if inside of the admin_advanced.php
			if (typeof window.parent.goToHandler == "function") {
				window.parent.goToHandler(edit_entity_url);
				window.parent.MyFancyPopup.hidePopup();
			}
			else //if in an independent window
				window.parent.document.location = edit_entity_url;
		}
		else
			document.location = edit_entity_url;
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
	var create_entity = $(".create_entity");
	create_entity.addClass("changing_to_step");
	
	document.location = url;
}

function postToUrl(url, data) {
	var create_entity = $(".create_entity");
	create_entity.addClass("changing_to_step");
	
	var oForm = $('<form method="post" action="' + url + '"></form>');
	oForm.hide();
	
	appendDataToForm(oForm, data);
	create_entity.append(oForm);
	
	oForm.submit();
}

function initStorePages() {
	if (get_store_pages_url)
		$.ajaxSetup({
			complete: function(jqXHR, textStatus) {
				if (jqXHR.status == 200 && this.url.indexOf(get_store_pages_url) != -1) {
					var create_entity = $(".create_entity");
					var a = create_entity.find(".top_bar.create_entity_top_bar header > ul > li.continue > a");
					
					if (!loaded_pages || $.isEmptyObject(loaded_pages))
						a.addClass("active");
					else {
						var lis = create_entity.find(".install_page .install_store_page > ul > li");
						
						lis.each(function(idx, item) {
							item = $(item);
							
							if (!item.attr("url"))
								item.hide();
							
							item.children(".img_label").removeAttr("onClick").off();
							item.children("button").on("click", function(e) {
								e.stopPropagation();
							});
							
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

function viewStorePage(preview_url, zip_url) {
	if (preview_url) {
		var popup = $(".view_store_page_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup with_title view_store_page_popup' + (is_popup ? " in_popup" : "") + '"></div>');
			$(document.body).append(popup);
		}
		
		var html = '<div class="title">Pre-built Page Preview <button class="install_page" onClick="selectStorePageSelectionThroughUrl(\'' + zip_url + '\')">Select this pre-built page</button></div>'
				+ '<iframe src="' + preview_url + '"></iframe>';
		popup.html(html);
		
		MyFancyPopupViewPage.init({
			elementToShow: popup,
			parentElement: document,
		});
		MyFancyPopupViewPage.showPopup();
	}
	else
		alert("Error: You cannot view this pre-built page. Please contact the sysadmin.");
}

function selectStorePageSelectionThroughUrl(url) {
	var create_entity = $(".create_entity");
	var ul = create_entity.find(".install_store_page > ul");
	
	ul.children("li").each(function(idx, li) {
		li = $(li);
		
		if (li.attr("url") == url && !li.hasClass("selected")) {
			li.trigger("click");
			
			return false;
		}
	});
	
	MyFancyPopupViewPage.hidePopup();
}

function choosePage(elm, choose_url) {
	//if no data in store, then redirect to the final creation_step
	if (!loaded_pages || $.isEmptyObject(loaded_pages)) {
		var current_url = "" + document.location;
		current_url = current_url.indexOf("#") != -1 ? current_url.substr(0, current_url.indexOf("#")) : current_url; //remove # so it can refresh page
		current_url = current_url.replace(/creation_step=([^&]*)&?/g, ""); //erase previous creation_step attribute
		current_url += current_url.indexOf("?") != -1 ? "" : "?"; //add "?" if apply
		current_url += "&creation_step=2"; //add creation_step to show successfull message
		current_url = current_url.replace(/\?&+/, "?"); //replace "?&&&" with "?"
		
		goToUrl(current_url);
	}
	else {
		var li = $(".create_entity ul > li.selected");
		
		if (!li[0])
			StatusMessageHandler.showError("You must select a pre-built page first.\nOr go back and click in the 'Empty Page' button.");
		else {
			StatusMessageHandler.showMessage("Downloading selected pre-built page and installing it. Please wait a while...");
			
			var post_data = {
				page_url: li.attr("url")
			};
			postToUrl(choose_url, post_data);
		}
	}
}
