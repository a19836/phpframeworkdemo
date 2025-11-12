/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$(function () {
	initObjectBlockSettings("manage_chat_settings", saveManageChat, "saveManageChat");
});

function loadManageChatBlockSettings(settings_elm, settings_values) {
	//console.log(settings_values);
	loadObjectBlockSettings(settings_elm, settings_values, "manage_chat_settings");
	
	onChangeAction( settings_elm.find(".action select")[0] );
}

function onChangeAction(elm) {
	elm = $(elm);
	var p = elm.parent().parent();
	
	if (elm.val() == "load_messages")
		p.children(".maximum_number_of_loaded_messages, .to_user_id").show();
	else if (elm.val() == "get_existent_chat_users")
		p.children(".maximum_number_of_loaded_messages, .to_user_id").hide();
	else if (elm.val() == "get_last_chats") {
		p.children(".maximum_number_of_loaded_messages").show();
		p.children(".to_user_id").hide();
	}
	else {
		p.children(".maximum_number_of_loaded_messages").hide();
		p.children(".to_user_id").show();
	}
}

function saveManageChat(button) {
	saveObjectBlock(button, "manage_chat_settings");
}
