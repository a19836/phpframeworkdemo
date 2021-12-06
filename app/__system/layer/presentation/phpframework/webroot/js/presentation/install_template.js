$(function () {
	onChangeLayer( $(".file_upload .layer select")[0] );
});

function onChangeLayer(elm) {
	elm = $(elm);
	var bn = elm.val();
	
	$("form").hide();
	$("#form_" + bn).show();
}
