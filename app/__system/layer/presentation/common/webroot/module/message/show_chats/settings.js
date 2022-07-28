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
