//$(function () {
window.addEventListener("load", function() {
	//if no jquery lib, tries to load it automatically
	if (typeof $ != "function") {
		if (jquery_lib_url && (typeof autoload_jquery_lib == "undefined" || autoload_jquery_lib)) {
			var exists = false;
			var scripts = document.head.getElementsByTagName("scripts");
			
			for (var i = 0; i < scripts.length; i++)
				if (scripts[i].getAttribute("src") == jquery_lib_url) {
					exists = true;
					break;
				}
			
			if (!exists) {
				var script = document.createElement("script");
				script.setAttribute("src", jquery_lib_url);
				document.head.appendChild(script);
				
				if (typeof console != "undefined" && typeof console.log == "function")
					console.log("Loading jquery lib automatically with url: " + jquery_lib_url);
			}
		}
		else
			alert("jQuery lib must be loaded first!");
	}
});

function initChat(chat_obj) {
	var chat_messages_elm = $("#" + chat_obj.id).children(".chat_messages");
	
	//This is very important to be inside of this setTimeout, otherwise the scrollTop won't work on Chrome because it cannot detect correctly the chat_messages_elm.prop("scrollHeight")
	setTimeout(function() {
		chat_messages_elm.scrollTop( chat_messages_elm.prop("scrollHeight") );
	}, 10);
	
	if (chat_obj.load_messages_url) {
		chat_messages_elm.scroll(function() {
			if (chat_messages_elm.scrollTop() == 0) {
				loadPreviousMessages(chat_obj, on_load_previous_messages_function);
			}
		});
	}
	
	setTimeout(function() {
		loadNextMessages(chat_obj, true, on_load_next_messages_function);
	}, 5000);
}

function closeChat(elm, chat_obj_id) {
	$(elm).parent().parent().remove();
	
	eval("window." + chat_obj_id + ".load_messages_url = null;"); //so it stops the loadNextMessages function
}

function sendNewMessage(elm, chat_obj, func) {
	if (chat_obj.send_message_url && !chat_obj.sending_message) {
		chat_obj.sending_message = true;
		
		elm = $(elm);
		elm.attr("disabled", "disabled");
		var textarea = elm.parent().closest(".chat_footer").children("textarea");
		var content = textarea.val();
		
		if (content != "") {
			var url = chat_obj.send_message_url;
			var d = new Date();
			url += (url.indexOf('?') != -1 ? '&' : '?') + 'time=' + d.getTime();
			
			$.ajax({
				type : "post",
				url : url,
				data : {"content" : content},
				dataType : "json",
				success : function(message_id, textStatus, jqXHR) {
					if($.isNumeric(message_id) && message_id > 0) {
						var chat_messages_elm = elm.parent().closest(".module_show_chat").children(".chat_messages");
						chat_messages_elm.scrollTop( chat_messages_elm.prop("scrollHeight") );
					
						textarea.val("");
					
						loadNextMessages(chat_obj, false, on_load_next_messages_function);
					}
					else if (chat_obj.on_send_error_message)
						alert(chat_obj.on_send_error_message);
					
					if (typeof func == "function")
						func(elm, chat_obj, message_id);
					
					elm.removeAttr("disabled");
					chat_obj.sending_message = false;
				},
				error : function() { 
					if (chat_obj.on_send_error_message)
						alert(chat_obj.on_send_error_message);
					
					if (typeof func == "function")
						func(elm, chat_obj, null);
				
					elm.removeAttr("disabled");
					chat_obj.sending_message = false;
				},
			});
		}
		else {
			elm.removeAttr("disabled");
			chat_obj.sending_message = false;
		}
	}
}

function loadPreviousMessages(chat_obj, func) {
	if (chat_obj.load_messages_url && !chat_obj.loading_previous_messages && $("#" + chat_obj.id)[0]) {
		chat_obj.loading_previous_messages = true;
		
		var chat_messages_elm = $("#" + chat_obj.id + " .chat_messages").first();
		chat_messages_elm.prepend('<div class="loading"></div>');
		chat_messages_elm.children(".load_previous_messages").hide();
		
		var first_msg = chat_messages_elm.children("ul").children(".message").first();	
		var message_id = first_msg.attr("message_id");
		
		if ($.isNumeric(message_id)) {
			var url = chat_obj.load_messages_url;
			var d = new Date();
			url += (url.indexOf('?') != -1 ? '&' : '?') + 'time=' + d.getTime();
			
			$.ajax({
				type : "post",
				url : url,
				data : {"message_id" : message_id, "direction" : -1},
				dataType : "json",
				success : function(messages, textStatus, jqXHR) {
					if(messages) {
						var date = new Date();
						var current_date = date.getFullYear() + "-" + ('0' + (date.getMonth() + 1)).slice(-2) + "-" + ('0' + date.getDate()).slice(-2);
						
						$.each(messages, function(idx, message) {
							var prev_message = messages[idx + 1];
							var same_user = prev_message && prev_message.from_user_id == message.from_user_id && prev_message.to_user_id == message.to_user_id;
							
							var new_message = getMessageHtml(chat_obj, message, current_date, same_user);
							chat_messages_elm.children("ul").prepend(new_message);
						});
						
						if (!$.isEmptyObject(messages)) {
							chat_messages_elm.scrollTop(first_msg.offset().top - chat_messages_elm.offset().top);
							
							setTimeout(function() {
								chat_obj.loading_previous_messages = false;
								chat_messages_elm.children(".load_previous_messages").show();
							}, 300);
						}
					}
					else if (chat_obj.on_load_error_message) {
						chat_messages_elm.children(".load_previous_messages").show();
						alert(chat_obj.on_load_error_message);
					}
					
					if (typeof func == "function")
						func(chat_obj, messages);
					
					chat_messages_elm.children(".loading").remove();
				},
				error : function() { 
					if (chat_obj.on_load_error_message)
						alert(chat_obj.on_load_error_message);
					
					if (typeof func == "function")
						func(chat_obj, null);
					
					chat_messages_elm.children(".load_previous_messages").show();
					chat_messages_elm.children(".loading").remove();
					chat_obj.loading_previous_messages = false;
				},
			});
		}
		else
			chat_messages_elm.children(".loading").remove();
			
	}
}

function loadNextMessages(chat_obj, auto_load, func) {
	if (chat_obj.load_messages_url && !chat_obj.loading_next_messages && $("#" + chat_obj.id)[0]) {
		chat_obj.loading_next_messages = true;
		
		var chat_messages_elm = $("#" + chat_obj.id + " .chat_messages").first();
		
		var last_message = chat_messages_elm.children("ul").children(".message").last();
		var message_id = last_message.attr("message_id");
		message_id = $.isNumeric(message_id) ? message_id : 0;
		
		var url = chat_obj.load_messages_url;
		var d = new Date();
		url += (url.indexOf('?') != -1 ? '&' : '?') + 'time=' + d.getTime();
			
		$.ajax({
			type : "post",
			url : url,
			data : {"message_id" : message_id},
			dataType : "json",
			success : function(messages, textStatus, jqXHR) {
				var interval = 10000;
				
				if(messages) {
					interval = 5000;
					
					var date = new Date();
					var current_date = date.getFullYear() + "-" + ('0' + (date.getMonth() + 1)).slice(-2) + "-" + ('0' + date.getDate()).slice(-2);
					
					$.each(messages, function(idx, message) {
						var same_user = false;
						var prev_message = messages[idx - 1];
						if (prev_message)
							same_user = prev_message.from_user_id == message.from_user_id && prev_message.to_user_id == message.to_user_id;
						else if (last_message)
							same_user = (last_message.hasClass("message_to_user") && message.to_user_id == chat_obj.from_user_id) || (last_message.hasClass("message_from_user") && message.from_user_id == chat_obj.from_user_id);
						
						var new_message = getMessageHtml(chat_obj, message, current_date, same_user);
						chat_messages_elm.children("ul").append(new_message);
					});
					
					if (!$.isEmptyObject(messages)) {
						//scroll down a little bit so the user can see that are new messages
						chat_messages_elm.scrollTop( chat_messages_elm.scrollTop() + 50 );
					}
				}
				else if (chat_obj.on_load_error_message)
					alert(chat_obj.on_load_error_message);
				
				if (typeof func == "function")
					func(chat_obj, messages);
				
				chat_obj.loading_next_messages = false;
				
				if (auto_load)
					setTimeout(function() { loadNextMessages(chat_obj, auto_load, func); }, interval);
			},
			error : function() { 
				if (chat_obj.on_load_error_message)
					alert(chat_obj.on_load_error_message);
				
				if (typeof func == "function")
					func(chat_obj, null);
					
				chat_obj.loading_next_messages = false;
				
				if (auto_load)
					setTimeout(function() { loadNextMessages(chat_obj, auto_load, func); }, 5000);
			},
		});
	}
}

function getMessageHtml(chat_obj, message, current_date, same_user) {
	var message_id = message["message_id"];
	var subject = message["subject"] ? message["subject"] : "";
	var content = message["content"] ? message["content"] : "";
	var created_date = message["created_date"] ? message["created_date"] : "";
	var seen_date = message["seen_date"] ? message["seen_date"] : "";
	
	content = ("" + content).replace(/\n/g, '<br/>');
	
	var cd = created_date.split(" ");
	if (cd[0] == current_date)
		created_date = cd[1];
	created_date = created_date.lastIndexOf(":") ? created_date.substr(0, created_date.lastIndexOf(":")) : created_date;
	
	var sd = seen_date.split(" ");
	if (sd[0] == current_date)
		seen_date = sd[1];
	seen_date = seen_date.lastIndexOf(":") ? seen_date.substr(0, seen_date.lastIndexOf(":")) : seen_date;
	
	
	var msg_html = message["from_user_id"] == chat_obj.from_user_id ? chat_obj.from_user_message_html : chat_obj.to_user_message_html;
	var new_message = $(msg_html.replace(/#message_id#/g, message_id).replace(/#subject#/g, subject).replace(/#content#/g, content).replace(/#created_date#/g, created_date).replace(/#seen_date#/g, seen_date));
	
	if (!subject)
		new_message.children(".msg").children(".subject").remove();
	
	if (same_user)
		new_message.addClass("same_user_message");
		
	return new_message;
}
