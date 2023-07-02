<?php
//all urls are already the url prefix, this is, without the query strings
function showBreadcrumbs($EVC, $curr_url, $project_url_prefix, $admin_url) {
	$url_prefix = $admin_url ? $admin_url : $project_url_prefix;
	$url_prefix = $url_prefix . (substr($url_prefix, -1) != "/" ? "/" : "");
	$url_prefix = prepareUrl($url_prefix);
	
	$curr_url = prepareUrl($curr_url);
	$curr_suffix_url = substr($curr_url, strlen($url_prefix));
	
	$parts = explode("/", $curr_suffix_url);
	
	echo '<li class="breadcrumb-item active"><a class="text-danger" href="' . $url_prefix . '">Dashboard</a></li>';
	
	$l = count($parts);
	for ($i = 0; $i < $l; $i++) {
		$part = $parts[$i];
		
		if (trim($part) && $part != "index") {
			if ($i + 1 == $l || $part == "batch")
				$url_prefix .= $part . "?" . $_SERVER["QUERY_STRING"];
			else
				$url_prefix .= $part . "/";
			
			$class = ($i == 0 && $part == "private") || ($i == 1 && $part == "admin") ? "hidden" : "";
			$label = ucwords(strtolower(str_replace(array("_", "-"), " ", $part)));
			
			echo '<li class="breadcrumb-item active ' . $class . '"><a class="text-danger" href="' . $url_prefix . '">' . translateProjectText($EVC, $label) . '</a></li>';
		}
	}
}

function prepareUrl($url) {
	$parsed_url = parse_url($url);
	$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
	$host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
	$user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
	$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
	$pass     = ($user || $pass) ? "$pass@" : '';
	$path     = isset($parsed_url['path']) ? preg_replace("/\/+/", "/", $parsed_url['path']) : '';
	$query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
	$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

	return "$scheme$user$pass$host$port$path$query$fragment";
}
?>
