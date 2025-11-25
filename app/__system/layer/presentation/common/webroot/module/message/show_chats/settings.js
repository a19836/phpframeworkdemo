/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

$(function () {
	initObjectBlockSettings("show_chats_settings", saveShowChats, "saveShowChats");
});

function loadShowChatsBlockSettings(settings_elm, settings_values) {
	//console.log(settings_values);
	loadObjectBlockSettings(settings_elm, settings_values, "show_chats_settings");
}

function saveShowChats(button) {
	saveObjectBlock(button, "show_chats_settings");
}
