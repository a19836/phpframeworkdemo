<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"str" => "<ul class=\"list-group me-2 ms-2 mr-2 small\">
	<li class=\"list-group-item\" style=\"min-width: 20px; min-height: 20px;\">
		<a class=\"d-block\" href=\"{$project_url_prefix}\" style=\"color: inherit;\">All Schools</a>
	</li>
	<li class=\"list-group-item\" style=\"min-width: 20px; min-height: 20px;\">
		<a class=\"d-block\" href=\"{$project_url_prefix}teachers\" style=\"color: inherit;\">All Teachers</a>
	</li>
</ul>",
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("echostr", $block_id, $block_settings[$block_id]);
?>