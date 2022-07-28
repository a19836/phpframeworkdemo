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
	
	setTimeout(function () {
		checkNewUsers(on_check_new_users_function);
	}, 10000);
});

function openUserChat(user_id, func) {
	if (show_chat_url) {
		var module_show_chats = $(".module_show_chats");
		var chat_details = module_show_chats.children(".chat_details");
	
		chat_details.find(".module_show_chat .chat_header .chat_close").click();
		chat_details.html('<div class="loading"></div>');
	
		module_show_chats.removeClass("new_chat_openned");
		module_show_chats.children(".chats_list").children(".user_chat").removeClass("selected");
		
		if (user_id) {
			module_show_chats.children(".chats_list").children(".user_chat[user_id='" + user_id + "']").addClass("selected");
			
			var url = show_chat_url + user_id;
			var d = new Date();
			url += (url.indexOf('?') != -1 ? '&' : '?') + 'time=' + d.getTime();
			
			$.ajax({
				type : "get",
				url : url,
				dataType : "text",
				success : function(html, textStatus, jqXHR) {
					if (html) {
						chat_details.html( $(html) );
			
						if (typeof func == "function")
							func(chat_details);
					}
				},
			});
		}
	}
}

function checkNewUsers(func) {
	if (load_existent_chat_users_url) {
		var url = load_existent_chat_users_url;
		var d = new Date();
		url += (url.indexOf('?') != -1 ? '&' : '?') + 'time=' + d.getTime();
		
		$.ajax({
			type : "get",
			url : url,
			dataType : "json",
			success : function(users, textStatus, jqXHR) {
				if (users) {
					var chats_list = $(".module_show_chats .chats_list");
					var existent_users = chats_list.children(".user_chat");
					var first_user = null;
					var user_ids = [];
					
					$.each(existent_users, function(idx, user) {
						user = $(user);
						
						if (!first_user)
							first_user = user;
						
						var user_id = parseInt(user.attr("user_id"));
						if (user_id)
							user_ids.push(user_id);
					});
					
					chats_list.children(".empty_items").hide();
					
					var date = new Date();
					var current_date = date.getFullYear() + "-" + ('0' + (date.getMonth() + 1)).slice(-2) + "-" + ('0' + date.getDate()).slice(-2);
					
					$.each(users, function(idx, user) {
						if ($.inArray(parseInt(user["user_id"]), user_ids) == -1) {
							var last_chat_date = user["last_chat_date"];
							var cd = last_chat_date.split(" ");
							if (cd[0] == current_date)
								last_chat_date = cd[1];
							last_chat_date = last_chat_date.lastIndexOf(":") ? last_chat_date.substr(0, last_chat_date.lastIndexOf(":")) : last_chat_date;
						
							var user_html = $( chat_list_user_html.replace(/#user_id#/g, user["user_id"]).replace(/#name#/g, user["name"]).replace(/#photo_url#/g, user["photo_url"]).replace(/#last_chat_date#/g, last_chat_date) );
						
							if (first_user && first_user[0])
								first_user.before(user_html);
							else
								chats_list.append(user_html);
						}
					});
				}
				
				if (typeof func == "function")
					func(users);
			
				setTimeout(checkNewUsers, 10000);
			},
			error : function() { 
				if (typeof func == "function")
					func(null);
					
				setTimeout(checkNewUsers, 10000);
			},
		});
	}
}

function deleteChat(elm, func, event) {
	event = event || window.event
	event.stopPropagation();
	
	if (delete_chat_url && (!on_delete_chat_confirmation_message || confirm(on_delete_chat_confirmation_message))) {
		var user_chat = $(elm).parent().closest('.user_chat');
		var user_id = parseInt( user_chat.attr('user_id') );
		
		if (user_id) {
			var url = delete_chat_url + user_id;
			var d = new Date();
			url += (url.indexOf('?') != -1 ? '&' : '?') + 'time=' + d.getTime();
			
			$.ajax({
				type : "get",
				url : url,
				dataType : "json",
				success : function(status, textStatus, jqXHR) {
					if(status == 1) {
						user_chat.parent().closest('.module_show_chats').find('> .chat_details .module_show_chat').each(function (idx, item) {
							var patt = new RegExp('module_show_chat_from_([0-9]+)_to_' + user_id);
							
							if (patt.test(item.className))
								$(item).parent().closest('.chat_details').html('');
						});
						
						user_chat.remove();
					}
					else if (on_delete_chat_error_message)
						alert(on_delete_chat_error_message);
			
					if (typeof func == "function")
						func(elm, status);
				},
				error : function() { 
					if (on_delete_chat_error_message)
						alert(on_delete_chat_error_message);
				
					if (typeof func == "function")
						func(elm, null);
				},
			});
		}
	}
}
