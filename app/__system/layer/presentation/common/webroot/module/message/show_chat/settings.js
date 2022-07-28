$(function () {
	initObjectBlockSettings("show_chat_settings", saveShowChat, "saveShowChat");
});

function loadShowChatBlockSettings(settings_elm, settings_values) {
	//console.log(settings_values);
	loadObjectBlockSettings(settings_elm, settings_values, "show_chat_settings");
}

function saveShowChat(button) {
	saveObjectBlock(button, "show_chat_settings");
}
