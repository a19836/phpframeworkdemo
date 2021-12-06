//$(function () {
window.addEventListener("load", function() {
	//var main_elm = $(".module_edit_user");
	var main_elms = document.querySelectorAll(".module_register");
	
	if (main_elms)
		for (var i = 0; i < main_elms.length; i++) {
			var main_elm = main_elms[i];
			
			if (typeof initAttachments == "function") { //on delete this function is not loaded
				//var tbody = main_elm.find(".module_edit_object_attachments .attachments table tbody");
				var tbody = main_elm.querySelector(".module_edit_object_attachments .attachments table tbody");
				
				if (tbody)
					initAttachments(tbody);
			}
		}
});
