var StatusMessageHandler = { 
	message_html_obj : null,
	
	init : function() {
		this.message_html_obj = $('<div class="status_message" onClick="StatusMessageHandler.removeMessages();"></div>');
		
		$(document.body).append(this.message_html_obj);
	},
	
	showMessage : function(message) {
		var status_message = this.getMessageElement(message, "status_message_info");
		
		try { //if message contains a full html page with head and body we will get a javascript error. So we need to catch it.
			this.message_html_obj.append(status_message);
		}
		catch(e) {
			this.message_html_obj = $(document.body).children('.status_message'); //sometimes the this.message_html_obj looses the reference for the object
			
			if (console && console.log)
				console.log(e);
		}
		
		this.prepareMessage(status_message, 5000);
	},

	showError : function(message) {
		var status_message = this.getMessageElement(message, "status_message_error");
		
		try { //if message contains a full html page with head and body we will get a javascript error. So we need to catch it.
			this.message_html_obj.append(status_message);
		}
		catch(e) {
			this.message_html_obj = $(document.body).children('.status_message'); //sometimes the this.message_html_obj looses the reference for the object
			
			if (console && console.log)
				console.log(e);
		}
		
		this.prepareMessage(status_message, 10000);
	},
	
	closeMessage : function(elm) {
		event.stopPropagation(); //avoids to call the onClick event from .status_message
		
		this.removeMessage(elm);
	},
	
	getMessageElement : function(message, message_class) {
		var width = $(window).width();
		
		message = this.parseMessage(message);
		var parts = message.split("\n");
		var height = parts.length * 20 + (message.indexOf("<br") != -1 ? message.split("<br").length * 20 : 0);
		
		var status_message = $('<div class="' + message_class + '">' + message.replace(/\n/g, "<br/>") + '<span class="close_message" onClick="StatusMessageHandler.closeMessage(this);">close</span></div>');
		
		status_message.css("width", width + "px"); //must be width, bc if is min-width the message won't be centered and the close button won't appear.
		status_message.css("min-height", height + "px"); //min-height are important bc if the message is bigger than the height, the message will appear without background
		
		return status_message;
	},
	
	//sometimes the message may contain a full page html with doctype, html, head and body tags. In this case we must remove these tags and leave it with only the body innerHTML, otherwise when we append the message, will throw an exception.
	parseMessage : function(message) {
		if (message) {
			var message_lower = message.toLowerCase();
			
			var pos = message_lower.indexOf("<!doctype");
			if (pos != -1) {
				var end = message_lower.indexOf(">", pos);
				end = end != -1 ? end : message_lower.length;
				message = message.substr(0, pos) + message.substr(end + 1);
				message = message.replace(/(\s)\s+/g, "$1");
				message_lower = message.toLowerCase();
			}
			
			var html_code = this.getMessageHtmlTagContent(message, message_lower.indexOf("<html"), "html");
			html_code = html_code ? html_code[1] : null;
			
			if (html_code) {
				message = message.replace(html_code, "").replace(/(\s)\s+/g, "$1");
				
				var html_code_lower = html_code.toLowerCase();
				var body_code = this.getMessageHtmlTagContent(html_code, html_code_lower.indexOf("<body"), "body");
				body_code = body_code ? body_code[0] : null;
				message += body_code;
			}
			else {
				var head_code = this.getMessageHtmlTagContent(message, message_lower.indexOf("<head"), "head");
				head_code = head_code ? head_code[1] : null;
				
				if (head_code) {
					message = message.replace(head_code, "").replace(/(\s)\s+/g, "$1");
					message_lower = message.toLowerCase();
				}
				
				var body_code = this.getMessageHtmlTagContent(message, message_lower.indexOf("<body"), "body");
				
				if (body_code)
					message = message.replace(body_code[0], body_code[1]).replace(/(\s)\s+/g, "$1");
			}
		}
		
		return message;
	},
	
	getMessageHtmlTagContent : function(text, idx, tag_name) {
		if (typeof MyHtmlBeautify != "undefined") {
			var code = MyHtmlBeautify.getTagContent(text, idx, tag_name);
			return code ? [ code[0], code[2] ] : null;
		}
		
		var text_lower = text.toLowerCase();
		var outer_start = text_lower.indexOf("<" + tag_name, idx);
		
		if (outer_start != -1) {
			var inner_start = text_lower.indexOf(">", outer_start);
			inner_start = inner_start != -1 ? inner_start : text.length;
			var inner_end = inner_start;
			var outer_end = inner_start;
			var is_single = text_lower.substr(outer_start, inner_start - outer_start).match(/[\/]\s*$/);
			
			if (!is_single) {
				inner_end = text_lower.indexOf("</" + tag_name, inner_start);
				inner_end = inner_end != -1 ? inner_end : text.length;
				
				outer_end = text_lower.indexOf(">", inner_end);
				outer_end = outer_end != -1 ? outer_end : text.length;
			}
				
			var inner_code = text.substr(inner_start + 1, (inner_end - 1) - inner_start);
			var outer_code = text.substr(outer_start, (outer_end + 1) - outer_start);
			
			return [inner_code, outer_code];
		}
		
		return null;
	},
	
	prepareMessage : function(status_message, timeout) {
		var max_height = parseInt(status_message.css("max-height"));
		var height = parseInt(status_message.css("min-height"));
		
		if (height && max_height && height > max_height)
			status_message.css("min-height", max_height + "px");
		
		setTimeout(function() { 
			status_message.remove(); 
		}, timeout);
	},
	
	removeMessage : function(elm) {
		$(elm).parent().remove();
	},
	
	removeLastShownMessage : function(type) {
		var selector = type ? ".status_message_" + type : ".status_message_info, .status_message_error";
		this.message_html_obj.children(selector).last().remove();
	},
	
	removeMessages : function(type) {
		var selector = type ? ".status_message_" + type : ".status_message_info, .status_message_error";
		this.message_html_obj.children(selector).remove();
	},
};
