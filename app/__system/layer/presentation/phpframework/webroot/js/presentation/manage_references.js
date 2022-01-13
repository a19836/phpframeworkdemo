$(function() {
	prepareFileTreeCheckbox( $(".layout_type_permissions_content input[type=checkbox]") );
	
	updateLayoutTypePermissionsById(layout_type_id);
});

function saveProjectLayoutTypePermissions() {
	return confirm('Do you wish to save these permissions for this project?');
}
