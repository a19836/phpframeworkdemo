<?php
if (!empty($GLOBALS["force_https"]) && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) { 
    $actual_link = "https://" . $_SERVER["HTTP_HOST"] . (isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "");
    header('Location: ' . $actual_link, true, 302);
    echo "<script>document.location='$actual_link';</script>";
    die();
}

$presentation_id = isset($GLOBALS["presentation_id"]) ? $GLOBALS["presentation_id"] : null;
$project_protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? "https://" : "http://"; //Do not add " || $_SERVER['SERVER_PORT'] == 443" bc the ssl port may not be 443 depending of the server configuration

$uri = explode("?", (isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : ""));
$uri = $uri[0]; //be sure that uri does not have the query string
$parts = explode("/$presentation_id/", $uri);

if (count($parts) <= 1) //Very important: The __system needs this, bc when the __system is trying to figure out the project url, it needs to have this check, when we have multiple installations in the same host accessable through different REQUEST_URI. This is to be used for the cases where the framework is installed in: localhost/foo/bar/__system/
	$parts = explode("/__system/", $uri);

if (count($parts) > 1) {
	$project_relative_url_prefix = $parts[0] . "/$presentation_id/";
	$project_common_relative_url_prefix = $parts[0] . "/" . $EVC->getCommonProjectName() . "/";
}
else {
	$document_root = str_replace("//", "/", (isset($_SERVER["CONTEXT_DOCUMENT_ROOT"]) ? $_SERVER["CONTEXT_DOCUMENT_ROOT"] : (isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] : "") ) . "/");
	$project_relative_url_prefix = "/" . (strpos($document_root, "/$presentation_id/") !== false ? "" : "$presentation_id/"); //if is a direct domain to the project, doesn't add the presentation_id.
	$project_common_relative_url_prefix = "/" . $EVC->getCommonProjectName() . "/"; //if is a direct domain to the project, the vhosts need to have the /common/ path defined to the right folder, otherwise this won't work correctly.
}

$project_url_prefix = $project_protocol . $_SERVER["HTTP_HOST"] . $project_relative_url_prefix;
$project_common_url_prefix = $project_protocol . $_SERVER["HTTP_HOST"] . $project_common_relative_url_prefix;

$original_project_url_prefix = $project_url_prefix; //When a page use a template from another project, the system will change this variable with the original project url prefix. This variable should be used to set the images, css and js files in the html code of templates.

$sanitize_html_in_post_request = true; //This is very important bc it protects against xss attacks
?>
