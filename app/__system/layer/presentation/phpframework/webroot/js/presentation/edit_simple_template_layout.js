window.onload = function() {
	onLoadEditSimpleTemplateLayout();
};

window.onerror = function(msg, url, lineNo, columnNo, error) {
	//alert("Javascript Error was found!");
	
	return true; //return true, avoids the error to be shown and other scripts to stop.
}

//Note that this function is called in the edit_page_and_template.js file too
function onLoadEditSimpleTemplateLayout() {
	/*if (!window.jQuery) {
		// jQuery not loaded, load it and when it loads call
		// noConflict and the callback (if any).
		var script = document.createElement('script');
		script.onload = function() {
			jQuery.noConflict();
		};
		script.src = "http://code.jquery.com/jquery-1.8.1.min.js";
		var head = document.getElementsByTagName('head')[0];
		var body = document.getElementsByTagName('body')[0];
		
		if (head)
			head.appendChild(script);
		else
			body.appendChild(script);
	}*/
	
	if (typeof parent.pretifyRegionsBlocksComboBox == "function") {
		parent.disableLinksAndButtonClickEvent(document.body);
		
		var items = document.querySelectorAll(".template_region .items");
		parent.prepareRegionsBlocksHtmlValue(items);
		parent.pretifyRegionsBlocksComboBox(items);
		parent.loadAvailableBlocksList(items);
		createRegionsBlocksHtmlEditor(items);
		prepareRegionsBlocks(items);
		
  		//prepare light class - if background of template_region or its parents is dark, change colors to white colors...
  		var template_regions = document.querySelectorAll(".template_region");
		
		if (template_regions)
			for (var i = 0; i < template_regions.length; i++)
				if (isRegionBlockInDarkBackground(template_regions[i]))
		  			template_regions[i].classList.add("item-light");
	}
	else 
		alert("This page should be called as an iframe from another page. Should not be called directly as a main window bc it needs some javascript of the parent window.");
}

function prepareRegionsBlocks(elms) {
	var items = [];
	
	if (elms)
		for (var i = 0; i < elms.length; i++) {
			var elm = elms[i];
			var elm_items = elm.classList.contains("item") ? [elm] : elm.querySelectorAll(".item");
			
			if (elm_items)
				for (var j = 0; j < elm_items.length; j++)
					items.push(elm_items[j]);
		}
	else
		items = document.querySelectorAll(".item");
	
	for (var i = 0; i < items.length; i++) {
		var item = items[i];
		
  		prepareRegionBlockOverHandlers(item);
		prepareRegionBlockSimulatedHtml(item);
	}
}
 
function prepareRegionBlockOverHandlers(item) {
	if (item) {
		//prepare handlers
		var item_mouseover_handler = function() {
  			var timeout_id = this.hasOwnProperty("out_timeout_id") ? this["out_timeout_id"] : null;
  			if (timeout_id)
  				clearTimeout(timeout_id);
  			
  			this.classList.add("item-hover");
  		};
  		
  		var item_mouseout_handler = function() {
  			var timeout_id = this.hasOwnProperty("out_timeout_id") ? this["out_timeout_id"] : null;
  			if (timeout_id)
  				clearTimeout(timeout_id);
  			
  			var elm = this;
  			var timeout_id = setTimeout(function() {
  				elm.classList.remove("item-hover");
  			}, 1000);
  			
  			this["out_timeout_id"] = timeout_id;
  		};
  		
		if (item.addEventListener) {
			item.addEventListener("mouseover", item_mouseover_handler);
			item.addEventListener("mouseout", item_mouseout_handler);
		}
  		else if (item.attachEvent) {
		   	item.attachEvent('mouseover', item_mouseover_handler);
		   	item.attachEvent('mouseout', item_mouseout_handler);
  		}
  	}
}

function prepareRegionBlockSimulatedHtml(item) {
	if (item) {
		//get block_simulated_html div
		var item_children = item.childNodes;
		var block_simulated_html = filterSelectorInNodes(item_children, ".block_simulated_html");
		
		if (block_simulated_html)
			block_simulated_html.innerHTML = '';
		
		//get block path
		var values = parent.getSettingsTemplateRegionBlockValues(item);
		var type = values["type"];
		var region = values["region"];
		var block = values["block"];
		var rb_index = values["rb_index"];
		
		var b = ("" + block).charAt(0) == '"' ? ("" + block).substr(1, ("" + block).length - 2).replace(/\\"/g, '"') : block;
		
		if (b && (type == "options" || type == "string" || type == "text")) {
			var project = values["project"];
			var p = ("" + project).charAt(0) == '"' ? ("" + project).substr(1, ("" + project).length - 2).replace(/\\"/g, '"') : project;
			p = p ? p : selected_project_id;
			
			//preparing params and join points
			var page_region_block_params = "";
			var page_region_block_join_points = "";
			
			if (typeof parent.getRegionBlockParamValues == "function") {
				var rb_objs = parent.getRegionBlockParamValues(region, block, rb_index);
				
				if (!$.isEmptyObject(rb_objs))
					page_region_block_params = JSON.stringify(rb_objs);
			}
			
			if (typeof parent.getRegionBlockJoinPoints == "function") {
				var rb_objs =parent.getRegionBlockJoinPoints(region, block, rb_index);
				
				if (!$.isEmptyObject(rb_objs))
					page_region_block_join_points = JSON.stringify(rb_objs);
			}
			
			//preparing urls
			var get_url = system_get_page_block_simulated_html_url.replace(/#project#/g, p).replace(/#block#/g, b).replace(/#page_region_block_params#/g, page_region_block_params).replace(/#page_region_block_join_points#/g, page_region_block_join_points);
			var save_url = system_save_page_block_simulated_html_setting_url.replace(/#project#/g, p).replace(/#block#/g, b);
			
			getAjaxRequest({
				url: get_url, 
				callback_func: function(XMLRequestObject, response) {
					if (response && typeof response == "string" && response.charAt(0) == "{" && response.charAt(response.length - 1) == "}") {
						var response_obj = JSON.parse(response)
						//console.log(response_obj);
						
						if (typeof response_obj == "object" && response_obj && response_obj.hasOwnProperty("html") && response_obj["html"]) { //response_obj could be null. Null is an object!
							var simulated_html = response_obj["html"];
				  			
				  			//prepare block_simulated_html div
				  			if (!block_simulated_html) {
				  				var doc = item.ownerDocument || document;
				  				block_simulated_html = doc.createElement("DIV");
								block_simulated_html.className = "block_simulated_html";
								item.appendChild(block_simulated_html);
				  			}
				  			
				  			block_simulated_html.innerHTML = simulated_html;
					  		
					  		convertBlockSimulatedHtmlIntoEditableContent(block_simulated_html, response_obj, save_url);
				  			
				  			if (typeof parent.disableLinksAndButtonClickEvent == "function")
					  			parent.disableLinksAndButtonClickEvent(block_simulated_html);
				  		}
		  			}
		  		}
			});
		}
	}
}

function convertBlockSimulatedHtmlIntoEditableContent(block_simulated_html, response_obj, save_url) {
	block_simulated_html.setAttribute("block_code_id", response_obj["block_code_id"]);
	block_simulated_html.setAttribute("block_code_time", response_obj["block_code_time"]);
	
	//disable edition in block_simulated_html
	block_simulated_html.setAttribute("contenteditable", "false");
	block_simulated_html.setAttribute("spellcheck", "false");
	
	if (typeof response_obj["editable_settings"] == "object" && response_obj["editable_settings"]) { //it could be null. Null is an object!
		var editable_settings = response_obj["editable_settings"];
		var editable_elements = editable_settings["elements"];
		
		includeCodeForBlockSimulatedHtmlSetting(block_simulated_html, editable_settings);
		
		if (typeof editable_elements == "object")
			for (var setting_selector in editable_elements) {
				var setting_path = editable_elements[setting_selector];
				var elms = !setting_selector ? [block_simulated_html] : block_simulated_html.querySelectorAll(setting_selector);
				
				for (var i = 0; i < elms.length; i++) 
					convertBlockSimulatedHtmlSettingIntoEditableContent(elms[i], editable_settings, setting_selector, setting_path, save_url);
			}
	}
}

function convertBlockSimulatedHtmlSettingIntoEditableContent(elm, editable_settings, setting_selector, setting_path, save_url) {
	elm.setAttribute("contenteditable", "true");
	elm.setAttribute("spellcheck", "false");
	
	var value = getBlockSimulatedHtmlSettingValue(elm);
	var value_hash = ("" + value).hashCode();
	elm.setAttribute("saved_value_hash", value_hash);
	
	var func = function(e) {
		onBlurBlockSimulatedHtmlSetting(e, elm, editable_settings, setting_selector, setting_path, save_url);
	};
	
	if (elm.addEventListener) 
		elm.addEventListener("blur", func);
	else if (elm.attachEvent) 
	   	elm.attachEvent('blur', func);
}

function onBlurBlockSimulatedHtmlSetting(e, elm, editable_settings, setting_selector, setting_path, save_url) {
	e.preventDefault ? e.preventDefault() : e.returnValue = false;
	e.stopPropagation();
	
	var value = getBlockSimulatedHtmlSettingValue(elm);
	var value_hash = ("" + value).hashCode();
	
	if (elm.getAttribute("saved_value_hash") != value_hash) {
		showMessage("Saving this block property... Wait a while...");
		
		var block_simulated_html = elm.closest(".block_simulated_html");
		var block_code_id = block_simulated_html.getAttribute("block_code_id");
		var block_code_time = block_simulated_html.getAttribute("block_code_time");
		//console.log(block_code_id+", "+block_code_time+", "+setting_selector+", "+setting_path+", "+save_url+", "+value);
		
		var post_data = {
			block_code_id: block_code_id,
			block_code_time: block_code_time,
			setting_selector: setting_selector,
			setting_path: setting_path,
			setting_value: value,
		};
		
		var handlers = editable_settings["handlers"];
		handlers = typeof handlers == "object" ? handlers : {};
		eval('var on_prepare_post_data = handlers["on_prepare_post_data"] && typeof ' + handlers["on_prepare_post_data"] + ' == "function" ? ' + handlers["on_prepare_post_data"] + ' : null;');
		
		if (on_prepare_post_data)
			post_data = on_prepare_post_data(elm, post_data);
		
		if (post_data) {
			getAjaxRequest({
				url: save_url, 
				method: "POST",
				data: post_data,
				callback_func: function(XMLRequestObject, response) {
					if (parent.StatusMessageHandler)
						parent.StatusMessageHandler.removeLastShownMessage("info");
					
					if (response && typeof response == "string" && response.charAt(0) == "{" && response.charAt(response.length - 1) == "}") {
						var response_obj = JSON.parse(response);
						
						if (response_obj) {
							if (response_obj.old_block_code_id == block_code_id && response_obj.old_block_code_time == block_code_time) {
								if (response_obj.status) {
									block_simulated_html.setAttribute("block_code_id", response_obj.new_block_code_id);
									block_simulated_html.setAttribute("block_code_time", response_obj.new_block_code_time);
									
									elm.setAttribute("saved_value_hash", value_hash)
									
									showMessage("Block setting saved successfully!");
								}
								else
									showError("Error trying to save this block property. Please try again!");
							}
							else
								showError("Error: Could NOT save this block property because meanwhile someone already did some other change and this block UI is deprecated. To proceed, please refresh this block and execute your changes again!");
						}
						else
							showError("Error trying to save this block property. Please try again!");
					}
					else
						showError("Error trying to save this block property. Please try again!");
				}
			});
		}
	}
}

function includeCodeForBlockSimulatedHtmlSetting(block_simulated_html, editable_settings) {
	for (var k in editable_settings) {
		var v = editable_settings[k];
		
		if ((k == "css" || k == "js") && v) {
			var doc = block_simulated_html.ownerDocument || document;
			var created_elm = doc.createElement(k == "css" ? "STYLE" : "SCRIPT");
			created_elm.textContent = v;
    			block_simulated_html.appendChild(created_elm);
		}
		else if (k == "files" && v) {
			for (var vk in v) {
				var files = v[vk];
				
				if ((vk == "css" || vk == "js") && files) {
					//files could be an array or an object, so we convert it to an object, just in case
					if ((typeof Array.isArray == "function" && Array.isArray(files)) || Object.prototype.toString.call(files) === '[object Array]') {
						var obj = {};
						
						for (var i = 0; i < data.length; i++)
							obj[i] = data[i];
						
						data = obj;
					}
					
					for (var idx in files) {
						var file = files[idx];
						
						if (file) {
							var doc = block_simulated_html.ownerDocument || document;
							var created_elm = null;
							
							if (vk == "css") {
								created_elm = doc.createElement("LINK");
								created_elm.setAttribute("rel", "stylesheet");
								created_elm.setAttribute("type", "text/css");
								created_elm.setAttribute("href", file);
					    		}
					    		else {
					    			created_elm = doc.createElement("SCRIPT");
								created_elm.setAttribute("language", "javascript");
								created_elm.setAttribute("type", "text/javascript");
								created_elm.setAttribute("src", file);
					    		}
					    		
					    		block_simulated_html.appendChild(created_elm);
						}
					}
				}
			}
		}
	}
}

function getBlockSimulatedHtmlSettingValue(elm) {
	var inputs = ["input", "textarea", "select"];
	var tag_name = elm.tagName.toLowerCase();
	var value = elm.innerHTML;
	
	if (inputs.indexOf(tag_name) != -1) {
		var type = tag_name == "input" ? ("" + elm.type).toLowerCase() : "";
		
		if (type == "checkbox" || type == "radio") {
			if (elm.checked)
				value = elm.value;
			else
				value = "";
		}
		else
			value = elm.value;
	}
	
	return value;
}

function showMessage(msg) {
	if (parent.StatusMessageHandler)
		parent.StatusMessageHandler.showMessage(msg);
}

function showError(msg) {
	if (parent.StatusMessageHandler)
		parent.StatusMessageHandler.showError(msg);
	else
		alert(msg);
}

function createRegionsBlocksHtmlEditor(elms) {
	var selects = [];
	
	if (elms)
		for (var i = 0; i < elms.length; i++) {
			var elm = elms[i];
			var elm_selects = elm.classList.contains("item") ? elm.querySelectorAll(".region_block_type") : elm.querySelectorAll(".item .region_block_type");
			
			if (elm_selects)
				for (var j = 0; j < elm_selects.length; j++)
					selects.push(elm_selects[j]);
		}
	else
		selects = document.querySelectorAll(".item .region_block_type");
	
	for (var i = 0; i < selects.length; i++) {
		select = selects[i];
		
		if (select) {
			var type = select.value;
			
			if (type == "html")
				createRegionBlockHtmlEditor( filterSelectorInNodes(select.parentNode.childNodes, ".block_html") );
		}
	};
}

function createRegionBlockHtmlEditor(block_html) {
	var textarea = filterSelectorInNodes(block_html.childNodes, "textarea");
	textarea.style.display = "none";
	
	var editor_elm = filterSelectorInNodes(block_html.childNodes, ".block_html_editor");
	
	if (!editor_elm) {
		var doc = block_html.ownerDocument || document;
		editor_elm = doc.createElement("DIV");
		editor_elm.className = "block_html_editor";
		block_html.appendChild(editor_elm);
	}
	
	editor_elm.innerHTML = textarea.value;
	
	var tinymce_opts = parent.getTinyMCEOptions(block_html);
	tinymce_opts["target"] = editor_elm;
	tinymce_opts["selector"] = null;
	delete tinymce_opts["selector"];
	
	tinyMCE.init(tinymce_opts);
}

function onChangeRegionBlock(elm) {
	var p = elm.parentNode.closest(".item");
	var opt = elm.querySelector("option:checked");
	var block = opt ? opt.getAttribute("value") : "";
	
	if (block != "") {
		p.classList.add("active", "has_edit");
		
		if (opt.hasAttribute("invalid"))
			p.classList.add("invalid");
		else
			p.classList.remove("invalid");
	}
	else
		p.classList.remove("active", "invalid", "has_edit");
	
	prepareRegionBlockSimulatedHtml(p);
}

function onBlurRegionBlock(elm) {
	var block = elm.value;
	var p = elm.parentNode.closest(".item");
	
	if (block) 
		p.classList.add("active");
	else
		p.classList.remove("active");
	
	prepareRegionBlockSimulatedHtml(p);
}

function onChangeRegionBlockType(elm) {
	parent.onChangeRegionBlockType(elm);
	var p = elm.parentNode.closest(".item");
	
	if (elm.value == "html") {
		var block_html = filterSelectorInNodes(elm.parentNode.childNodes, ".block_html");
		
		if (!parent.hasRegionBlockHtmlEditor(block_html))
			createRegionBlockHtmlEditor(block_html);
	}
	
	prepareRegionBlockSimulatedHtml(p);
}

function addRepeatedRegionBlock(elm) {
	parent.addRepeatedRegionBlock(elm);
	
	//This is very important otherwise these fields will have the handlers from the parent window. The following code makes the handlers to the functions of this file
	var item = elm.parentNode.closest(".item");
	
	if (item) {
		var new_region = null;
		
		do {
			new_region = item.nextElementSibling;
		}
		while (new_region && !new_region.classList.contains("item"));
		
		if (new_region) {
			var children = new_region.childNodes;
			var block_text = filterSelectorInNodes(children, ".block_text");
			
			filterSelectorInNodes(children, ".block_options").setAttribute("onChange", "onChangeRegionBlock(this)");
			filterSelectorInNodes(block_text.childNodes, "textarea").setAttribute("onBlur", "onBlurRegionBlock(this)");
			filterSelectorInNodes(children, ".block").setAttribute("onBlur", "onBlurRegionBlock(this)");
			filterSelectorInNodes(children, ".region_block_type").setAttribute("onChange", "onChangeRegionBlockType(this)");
			filterSelectorInNodes(children, ".add").setAttribute("onClick", "addRepeatedRegionBlock(this)");
			filterSelectorInNodes(children, ".up").setAttribute("onClick", "moveUpRegionBlock(this)");
			filterSelectorInNodes(children, ".down").setAttribute("onClick", "moveDownRegionBlock(this)");
			filterSelectorInNodes(children, ".delete").setAttribute("onClick", "deleteRegionBlock(this)");
			
			var block_html = filterSelectorInNodes(new_region.childNodes, ".block_html");
			block_html.classList.remove("editor");
			
			prepareRegionBlockOverHandlers(new_region);
		}
	}
}

function openTemplateRegionInfoPopup(elm) {
	parent.openTemplateRegionInfoPopup(elm);
}

function editRegionBlock(elm) {
	var item = elm.parentNode;
	var block_html = filterSelectorInNodes(item.childNodes, ".block_html");
	
	parent.editRegionBlock(elm, {
		on_popup_close_func: function(html) {
			parent.setRegionBlockHtmlEditorValue(block_html, html);
		}
	});
}

function moveUpRegionBlock(elm) {
	parent.moveUpRegionBlock(elm);
}

function moveDownRegionBlock(elm) {
	parent.moveDownRegionBlock(elm);
}

function deleteRegionBlock(elm) {
	parent.deleteRegionBlock(elm);
}

function getTemplateRegionsBlocks() {
	var regions = [];
	var regions_blocks = [];
	
	var items = document.querySelectorAll(".template_region > .items > .item");
	//console.log(items);
	
	for (var i = 0; i < items.length; i++) {
		var item = items[i];
		var values = parent.getSettingsTemplateRegionBlockValues(item);
		
		if (values) {
			var region = values["region"];
			var type = values["type"];
			var block = values["block"];
			var project = values["project"];
			var rb_index = values["rb_index"];
			
			regions.push(region);
			
			if (block != "")
				regions_blocks.push([region, block, project, type == "html", rb_index]);
		}
	}
	
	var params = [];
	if (parent.template_params_values_list)
		for (var k in parent.template_params_values_list) 
			params.push(k);
	
	return {"regions": regions, "regions_blocks": regions_blocks, "params": params};
}

function isRegionBlockInDarkBackground(elm) {
	var bg = getStyle(elm, "backgroundColor");
	
	//rgba(0, 0, 0, 0) is the default background-color return, which is transparent. More info in: https://stackoverflow.com/questions/52572823/getcomputedstylebackground-color-returns-transparent-does-not-inherit-ances and https://www.py4u.net/discuss/990140
	if (bg && (("" + bg).toLowerCase() == "transparent" || ("" + bg).replace(/\s+/g, "").toLowerCase() == "rgba(0,0,0,0)"))
		bg = "";
	
	var is_dark = bg ? isDarkColor(bg) : false;
	
	return elm.parentNode && elm.parentNode.nodeName.toLowerCase() != "html" && elm.parentNode != document && !is_dark ? isRegionBlockInDarkBackground(elm.parentNode) : is_dark;
}

function getStyle(elm, style_prop_name) {
	var value;
	var doc = elm.ownerDocument || document;
	var win = doc.defaultView || doc.parentWindow;
	
	// W3C standard way:
	if (win && win.getComputedStyle) {
		// sanitize property name to css notation
		// (hypen separated words eg. font-Size)
		style_prop_name = style_prop_name.replace(/([A-Z])/g, "-$1").toLowerCase();
		
		return win.getComputedStyle(elm, null).getPropertyValue(style_prop_name);
	} 
	else if (elm.currentStyle) { // IE
		// sanitize property name to camelCase
		style_prop_name = style_prop_name.replace(/\-(\w)/g, function(str, letter) {
			return letter.toUpperCase();
		});
		
		value = elm.currentStyle[style_prop_name];
		
		// convert other units to pixels on IE
		if (/^\d+(em|pt|%|ex)?$/i.test(value)) { 
			return (function(value) {
				var old_left = elm.style.left;
				var old_rs_left = elm.runtimeStyle.left;
				
				elm.runtimeStyle.left = elm.currentStyle.left;
				elm.style.left = value || 0;
				value = elm.style.pixelLeft + "px";
				
				elm.style.left = old_left;
				elm.runtimeStyle.left = old_rs_left;
				
				return value;
			})(value);
		}
		
		return value;
	}
	else if (elm.style)
		return elm.style[style_prop_name];
}

function isDarkColor(color) {
	// Variables for red, green, blue values
	var r, g, b, hsp;
	
	// Check the format of the color, HEX or RGB?
	if (color.match(/^rgb/)) {
		// If RGB --> store the red, green, blue values in separate variables
		color = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);

		r = color[1];
		g = color[2];
		b = color[3];
	} 
	else {
		// If hex --> Convert it to RGB: http://gist.github.com/983661
		color = +("0x" + color.slice(1).replace( 
		color.length < 5 && /./g, '$&$&'));

		r = color >> 16;
		g = color >> 8 & 255;
		b = color & 255;
	}

	// HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
	hsp = Math.sqrt(
		0.299 * (r * r) +
		0.587 * (g * g) +
		0.114 * (b * b)
	);

	// Using the HSP value, determine whether the color is light or dark
	return hsp > 127.5 ? false : true;
}

function getAjaxRequest(options) {
	if(!options) 
		options = {};
	
	var method = options.method ? ("" + options.method).toUpperCase() : "GET";
	var url = options.url ? options.url : null;
	var data = method == "POST" && options.data ? options.data : null;
	var result_type = options.result_type ? ("" + options.result_type).toLowerCase() : "text";
	var assync = options.hasOwnProperty("assync") ? options.assync : true;
	var username = options.hasOwnProperty("username") ? options.username : null;
	var password = options.hasOwnProperty("password") ? options.password : null;
	var callback_func = options.callback_func ? options.callback_func : null;
	
	if (!url)
		return false;
	
	var XMLRequestObject = null; /* XMLHttpRequest Object */
	
	if (window.XMLHttpRequest) { /* Mozilla, Safari,...*/
		XMLRequestObject = new XMLHttpRequest();
		
		if (XMLRequestObject.overrideMimeType)
			XMLRequestObject.overrideMimeType("text/xml");
	
	} 
	else if (window.ActiveXObject) { /* IE */
		try {
			XMLRequestObject = new ActiveXObject("Msxml2.XMLHTTP");
		} 
		catch (e) {
			try {
				XMLRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
			} 
			catch (e) {}
		}
	}
	
	if(!XMLRequestObject) {
		//alert("Giving up :( Cannot create an XMLHTTP instance");
		return false;
	}
	
	//fix Firefox error reporting when it tries to parse the response as XML, bc if the server doesn't return the correct response Content-type, by default the XMLRequestObject will try to parse the response as XML.
	if (typeof XMLRequestObject.overrideMimeType == "function")
		XMLRequestObject.overrideMimeType("text/plain");
	
	/*
	open(method, url, async, user, psw)
		method: the request type GET or POST
		url: the file location
		async: true (asynchronous) or false (synchronous)
		user: optional user name
		psw: optional password
	*/
	XMLRequestObject.open(method, url, assync, username, password);
	
	if(method == "POST") {
		XMLRequestObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		
		if(data) {
			data = convertDataToQueryString(data);
			
			//XMLRequestObject.setRequestHeader("Content-length", data.length); //browser error saying: Refused to set unsafe header "Content-length"
		}
		
		//XMLRequestObject.setRequestHeader("Connection", "close"); //browser error saying: Refused to set unsafe header "Connection"
	}
	
	XMLRequestObject.onreadystatechange = function(){
		if(XMLRequestObject.readyState == 4) {
			var res = result_type == "xml" ? XMLRequestObject.responseXML : XMLRequestObject.responseText;
			
			if (typeof callback_func == "function")
				callback_func(XMLRequestObject, res);
		}
	};
	
	XMLRequestObject.send(data);
	
	return true;
}

function convertDataToQueryString(data, prefix) {
	var query_string = "";
	
	if (typeof data == "object") {
		if ((typeof Array.isArray == "function" && Array.isArray(data)) || Object.prototype.toString.call(data) === '[object Array]') {
			var obj = {};
			
			for (var i = 0; i < data.length; i++)
				obj[i] = data[i];
			
			data = obj;
		}
		
		for(name in data) {
			var value = data[name];
			
			if (prefix)
				name = prefix + "[" + name + "]";
			
			query_string += (query_string ? "&" : "");
			
			if (typeof value == "object")
				query_string += convertDataObjectToString(value, name);
			else
				query_string += encodeURIComponent(name) + '=' + encodeURIComponent(value);
		}
	}
	else
		query_string = data;
	
	return query_string;
}
