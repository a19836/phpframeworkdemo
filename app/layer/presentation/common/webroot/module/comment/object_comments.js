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

function addObjectComment(elm) {
	elm = $(elm);
	
	var textarea = elm.parent().closest(".add_object_comment").children("textarea");
	var comment = textarea.val();
	
	elm.attr("disabled", "disabled");
	textarea.attr("disabled", "disabled");
	
	$.ajax({
		type : "post",
		url : add_comment_url,
		data : {"event": "insert", "comment": comment}, 
		dataType : "text",
		success : function(data, textStatus, jqXHR) {
			if (data && ("" + data).indexOf("<status>1</status>") != -1) {
				var user_id = "", photo_url = "", name = "";
				
				if (current_user_data) {
					user_id = current_user_data.hasOwnProperty("user_id") ? current_user_data["user_id"] : "";
					photo_url = current_user_data.hasOwnProperty("photo_url") ? current_user_data["photo_url"] : "";
					name = current_user_data.hasOwnProperty("name") ? current_user_data["name"] : "";
				}
				
				var date = new Date();
				var current_date = date.getFullYear() + "-" + ('0' + (date.getMonth() + 1)).slice(-2) + "-" + ('0' + date.getDate()).slice(-2) + " " + date.getHours() + ":" + date.getMinutes();
				comment = comment.replace(/\n/g, "<br/>");
				
				var comment_html_elm = $(comment_html.replace(/%user_id%/g, user_id).replace(/%photo_url%/g, ("" + photo_url).replace(/"/g, "&quot;")).replace(/%name%/g, name).replace(/%date%/g, current_date).replace(/%comment%/g, comment));
				
				if (!photo_url)
					comment_html_elm.find(".user_photo img").remove();
				
				var ul = elm.parent().closest(".module_object_comments").children(".comments").children("ul");
				ul.prepend(comment_html_elm);
				
				if (ul.children("li").length % 2 != 0)
					comment_html_elm.addClass("hovered");
				
				ul.children(".empty_comments").hide();
				
				textarea.val("");
			}
			else
				alert(add_comment_error_message);
			
			elm.removeAttr("disabled");
			textarea.removeAttr("disabled");
		},
		error : function() { 
			alert(add_comment_error_message);
			
			elm.removeAttr("disabled");
			textarea.removeAttr("disabled");
		},
	});
}
