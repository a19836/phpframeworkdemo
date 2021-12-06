var current_url = "" + document.location;
var hovered_element = null;
var clicked_element = null;
var iframe = null;
var is_page_url_loaded = false;
var events_set = false;
var regions = {};
var params = {};

$(function () {
	iframe = $(".convert_url_to_template .page_html iframe");
	
	iframe.mouseout(function() {
		unsetIframeHoveredElement();
	});
});

function loadUrl() {
	var convert_url_to_template = $(".convert_url_to_template");
	var url = convert_url_to_template.find(".page_url input").val();
	
	if (url) {
		var iframe_doc = iframe[0].contentWindow.document;
		var iframe_contents = iframe.contents();
		var iframe_html = iframe_contents.find("html");
		var iframe_head = iframe_contents.find("head");
		var iframe_body = iframe_contents.find("body");
		
		iframe_head.html("");
		iframe_body.html("");
		
		//remove body attributes
		if (iframe_body[0].attributes)
			for (var i = iframe_body[0].attributes.length - 1; i >= 0; i--)
				iframe_body[0].removeAttribute( iframe_body[0].attributes[i].name );
		
		//remove html attributes
		if (iframe_html[0].attributes)
			for (var i = iframe_html[0].attributes.length - 1; i >= 0; i--)
				iframe_html[0].removeAttribute( iframe_html[0].attributes[i].name );
		
		//set elements selection
		if (!events_set) {
			events_set = true;
			
			iframe_body.bind("mousemove", function(event) {
				unsetIframeHoveredElement();
				setIframeHoveredElement(event.target);
			});
			
			iframe_body.bind("click", function(event) {
				unsetIframeClickedElement();
				setIframeClickedElement(event.target);
			});
			
			//catch iframe errors
			iframe[0].contentWindow.onerror = function(msg, url, lineNo, columnNo, error) {
				//alert("Javascript Error was found!");
				
				return true; //return true, avoids the error to be shown and other scripts to stop.
			}
		}
		
		$.ajax({
			type : "post",
			url : current_url,
			data : {
				url: url,
				load: "load",
			},
			dataType : "text",
			success : function(parsed_html, textStatus, jqXHR) {
				if (parsed_html) {
					var pos = parsed_html.indexOf("\n");
					url = parsed_html.substr(0, pos);
					parsed_html = parsed_html.substr(pos + 1);
					//console.log(url);
					//console.log(parsed_html);
					
					var doc_type = "";
					var head_html = "";
					var body_html = "";
					var body_attributes = [];
					var html_attributes = [];
					//console.log(parsed_html);
					
					if (parsed_html) {
						var phl = parsed_html.toLowerCase();
						
						if (phl.indexOf("<head") == -1 && phl.indexOf("<body") == -1)
							body_html = parsed_html;
						else {
							head_html = getTemplateHtmlTagContent(parsed_html, "head");
							body_html = getTemplateHtmlTagContent(parsed_html, "body");
							
							body_attributes = getTemplateHtmlTagAttributes(parsed_html, "body");
						}
						
						if (phl.indexOf("<html") != -1)
							html_attributes = getTemplateHtmlTagAttributes(parsed_html, "html");
						
						var doc_type_pos = phl.indexOf("<!doctype");
						if (doc_type_pos != -1)
							doc_type = parsed_html.substr(doc_type_pos, parsed_html.indexOf(">", doc_type_pos + 1) + 1);
					}
					
					head_html += getIframeExtraStyle();
					
					//console.log(head_html);
					iframe_head[0].innerHTML = head_html; //Do not use .html(head_html), bc in some cases it breaks
					iframe_body[0].innerHTML = body_html; //Do not use .html(body_html), bc in some cases it breaks
					
					//set body attributes
					if (body_attributes)
						$.each(body_attributes, function(idx, attr) {
							iframe_body.attr(attr["name"], attr["value"]);
						});
					
					//set html attributes
					if (html_attributes)
						$.each(html_attributes, function(idx, attr) {
							iframe_html.attr(attr["name"], attr["value"]);
						});
					
					//save doctype
					iframe.data("doc_type", doc_type).data("url", url);
					
					prepareIframeBodyHtml(iframe_body);
					
					//reset regions and params
					regions = {};
					params = {};
					
					is_page_url_loaded = true;
				}
				else
					alert("Error loading url. Please try again...");
			},
			error: function (jqXHR, textStatus, errorThrown) {
				var msg = "Error loading url. Please try again...";
				alert(msg);
				
				if (jqXHR.responseText)
					StatusMessageHandler.showError(msg + "\n" + jqXHR.responseText);
			},
		});
	}
	else 
		alert("Please write a valid url...");
}

function getIframeExtraStyle() {
	return '<style data-reserved="1">'
		+ '.template-element-hovered:not(.template-element-clicked) { outline: 1px solid red !important; outline-offset: -2px !important; }'
		+ '.template-element-clicked { outline: 3px solid red !important; outline-offset: -2px !important; }'
		+ '.template-region, .template-param { background:#fff; color:#000; text-align:center; vertical-align:middle; opacity:.9; padding:5px; border:1px solid #ccc; box-sizing: border-box; overflow:hidden; z-index:999999999999; }'
		+ '.template-region:hover, .template-param:hover { opacity:1; }'
		+ '.template-region .close, .template-param .close { position:absolute; top:0px; right:0px; cursor:pointer; }'
	+ '</style>';
}

function unsetIframeHoveredElement() {
	if (hovered_element && $(hovered_element)[0].ownerDocument == iframe[0].contentWindow.document)
		$(hovered_element).removeClass("template-element-hovered");
	
	hovered_element = null;
}
function setIframeHoveredElement(elm) {
	hovered_element = elm;
	
	if (hovered_element && clicked_element != hovered_element) {
		he = $(hovered_element);
		
		if (!he.is(".template-region, .template-param") && !he.parent().is(".template-region, .template-param"))
			he.addClass("template-element-hovered");
	}
}
function unsetIframeClickedElement() {
	if (clicked_element && $(clicked_element)[0].ownerDocument == iframe[0].contentWindow.document)
		$(clicked_element).removeClass("template-element-clicked");
	
	clicked_element = null;
}
function setIframeClickedElement(elm) {
	if (clicked_element == elm)
		clicked_element = null;
	else {
		clicked_element = elm;
		
		if (clicked_element) {
			ce = $(clicked_element);
			
			if (!ce.is(".template-region, .template-param") && !ce.parent().is(".template-region, .template-param"))
				ce.addClass("template-element-clicked");
		}
	}
}

function prepareIframeBodyHtml(iframe_body) {
	//disable click events
	iframe_body.find("*").unbind("click").off('click');
	iframe_body.find("a, button, input").unbind("click").off('click').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		
		unsetIframeClickedElement();
		setIframeClickedElement(event.target);
		
		return false;
	});
}

function convertToRegion() {
	convertToType("region");
}

function convertToParam() {
	convertToType("param");
}

function convertToType(type) {
	if (clicked_element) {
		var type_label = type[0].toUpperCase() + type.substr(1);
		var name = prompt("Please type the name of the " + type + ":");
		name = name ? name.replace(/\s+/g, "") : "";
		
		if (name != "") {
			var items = type == "region" ? regions : params;
			var exists = items.hasOwnProperty(name);
			
			if (!exists) {
				var status = true;
				
				if (type == "region") {
					var single_nodes = ["img", "input", "textarea", "select", "button", "iframe", "meta", "base", "basefont", "link", "br", "wbr", "hr", "frame", "area", "source", "track", "circle", "col", "embed", "param", "style", "script", "link"];
					
					if ($.inArray(clicked_element.nodeName.toLowerCase(), single_nodes) != -1) {
						status = false;
					
						if (confirm("The selected element cannot be converted to a " + type + ". The system will select it's parent and create a region from it. Do you wish to proceed?")) {
							var p = $(clicked_element);
							
							do {
								p = p.parent();
								
								if (p.is("body")) {
									p = null;
									break;
								}
							}
							while ($.inArray(p[0].nodeName.toLowerCase(), single_nodes) != -1);
							
							unsetIframeHoveredElement();
							unsetIframeClickedElement();
							
							if (p[0]) {
								setIframeClickedElement(p[0]);
								status = true;
							}
						}
						else {
							unsetIframeHoveredElement();
							unsetIframeClickedElement();
						}
					}
				}
				
				if (status) {
					var ce = $(clicked_element);
					var position = ("" + ce.css("position")).toLowerCase() == "fixed" ? "fixed" : "absolute";
					var offset = ce.offset();
					
					var new_elm = $('<div class="template-' + type + '">' + type_label + ' "' + name + '" <span class="close">&times;</span></div>');
					new_elm.children(".close").click(function() {
						event.preventDefault();
						event.stopPropagation();
						
						new_elm.remove();
						
						items[name] = null;
						delete items[name];
						
						unsetIframeClickedElement();
					});
					
					var iframe_body = iframe.contents().find("body");
					iframe_body.append(new_elm);
					
					var w = ce.width() + parseInt(ce.css("padding-left")) + parseInt(ce.css("padding-right")) + parseInt(ce.css("border-left-width")) + parseInt(ce.css("border-right-width"));
					var h = ce.height() + parseInt(ce.css("padding-top")) + parseInt(ce.css("padding-bottom")) + parseInt(ce.css("border-top-width")) + parseInt(ce.css("border-bottom-width"));
					
					new_elm.css({
						position: position,
						top: offset.top,
						left: offset.left,
						width: parseInt(w) + "px",
						height: parseInt(h) + "px",
					});
					
					items[name] = clicked_element;
					
					ce.removeClass("template-element-hovered").removeClass("template-element-clicked");
					unsetIframeHoveredElement();
					unsetIframeClickedElement();
				}
			}
			else
				alert(type_label + " with this name already exists! Please choose another name...");
		}
		else
			alert(type_label + " name cannot be blank");
	}
	else
		alert("Please select an html element first!");
}

function saveTemplate() {
	var convert_url_to_template = $(".convert_url_to_template");
	var template_name = convert_url_to_template.find(".template input[name=template_name]").val();
	
	if (!is_page_url_loaded)
		alert("Page url must be loaded first.");
	else if (!template_name) 
		alert("Template name cannot be blank.");
	else {
		var save_button = convert_url_to_template.find(".template input[type=button]");
		var save_btn_value = save_button.val();
		save_button.val("Saving...").attr("disabled", "disabled");
		
		//prepare iframe html - prepare regions and params
		var iframe_contents = iframe.contents();
		var iframe_html = iframe_contents.find("html");
		
		if (regions)
			for (var name in regions) 
				if (regions[name] && $(regions[name])[0].ownerDocument == iframe[0].contentWindow.document)
					$(regions[name]).addClass("template-region-element-to-be-converted").attr("data-template-region-name", name);
		
		if (params)
			for (var name in params) 
				if (params[name] && $(params[name])[0].ownerDocument == iframe[0].contentWindow.document) 
					$(params[name]).addClass("template-param-element-to-be-converted").attr("data-template-param-name", name);
		
		//clone iframe html
		var clone = iframe_html.clone();
		
		//prepare cloned html
		var clone_body = clone.find("body");
		clone.find("head > [data-reserved=1]").remove();
		clone_body.find(".template-region, .template-param").remove();
		clone_body.find("*").removeClass("template-element-hovered template-element-clicked");
		
		var regions_html = {};
		$.each( clone_body.find(".template-region-element-to-be-converted"), function(idx, elm) {
			var name = $(elm).attr("data-template-region-name");
			
			if (name) {
				regions_html[name] = elm.innerHTML;
				elm.innerHTML = '&lt;? echo $EVC-&gt;getCMSLayer()-&gt;getCMSTemplateLayer()-&gt;renderRegion("' + name + '"); ?&gt;';
			}
		});
		
		var params_html = {};
		$.each( clone_body.find(".template-param-element-to-be-converted"), function(idx, elm) {
			var name = $(elm).attr("data-template-param-name");
			
			if (name) {
				params_html[name] = elm.innerHTML;
				elm.innerHTML = '&lt;? echo $EVC-&gt;getCMSLayer()-&gt;getCMSTemplateLayer()-&gt;getParam("' + name + '"); ?&gt;';
			}
		});
		
		clone_body.find(".template-region-element-to-be-converted, .template-param-element-to-be-converted").removeClass("template-region-element-to-be-converted, template-param-element-to-be-converted").removeAttr("data-template-region-name").removeAttr("data-template-param-name");
		
		var html = clone[0].outerHTML; //get html with the html tag
		html = MyHtmlBeautify.beautify(html);
		
		//put back the previous changes on the regions and params
		iframe_contents.find("body").find(".template-region-element-to-be-converted, .template-param-element-to-be-converted").removeClass("template-region-element-to-be-converted template-param-element-to-be-converted").removeAttr("data-template-region-name").removeAttr("data-template-param-name");
		
		//send post request to create template
		var data = {
			save: "save",
			url: iframe.data("url"),
			template_name: template_name,
			doc_type: iframe.data("doc_type"),
			regions: regions_html,
			params: params_html,
			html: html,
		};
		
		$.ajax({
			type : "post",
			url : current_url,
			data : JSON.stringify(data),
			dataType : "json",
			processData: false,
			contentType: 'application/json', //typically 'application/x-www-form-urlencoded', but the service you are calling may expect 'text/json'... check with the service to see what they expect as content-type in the HTTP header.
			success : function(status, textStatus, jqXHR) {
				if (status) {
					if (status == 1) {
						alert("Template Saved!");
						
						if (window.parent.refreshLastNodeChilds) {
							//Refreshing last node clicked in the entities folder.
							window.parent.refreshLastNodeChilds();
							
							//Refreshing templates and webroot folder
							var project = window.parent.$("#" + window.parent.last_selected_node_id).parent().closest("li[data-jstree=\'{\"icon\":\"project\"}\']");
							var templates_folder_id = project.children("ul").children("li[data-jstree=\'{\"icon\":\"templates_folder\"}\']").attr("id");
							var webroot_folder_id = project.children("ul").children("li[data-jstree=\'{\"icon\":\"webroot_folder\"}\']").attr("id");
							window.parent.refreshNodeChildsByNodeId(templates_folder_id);
							window.parent.refreshNodeChildsByNodeId(webroot_folder_id);
						}
					}
					else
						alert(status);
				}
				else
					alert("Error saving template. Please try again...");
				
				save_button.val(save_btn_value).removeAttr("disabled");
			},
			error: function (jqXHR, textStatus, errorThrown) {
				save_button.val(save_btn_value).removeAttr("disabled");
				
				var msg = "Error saving template. Please try again...";
				alert(msg);
				
				if (jqXHR.responseText)
					StatusMessageHandler.showError(msg + "\n" + jqXHR.responseText);
			},
		});
	}
}

function convertAttributesToAssociativeArray(attributes) {
	var arr = {};
	
	if (attributes)
		for (var i = 0, l = attributes.length; i < l; i++) 
			arr[ attributes[i].name ] = attributes[i].value;
	
	return arr;
}
