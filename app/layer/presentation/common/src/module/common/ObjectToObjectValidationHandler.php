<?php
class ObjectToObjectValidationHandler {
	
	public static function validate($EVC, $status, $settings = null) {
		if (empty($settings["validation_condition_type"]) || !empty($settings["validation_condition"])) {
			$head = '';
			
			if (empty($settings["style_type"])) {
				include $EVC->getConfigPath("config");
				$head .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/validate_object_to_object.css" type="text/css" charset="utf-8" />';
			}
			
			$head .= !empty($settings["css"]) ? '<style>' . $settings["css"] . '</style>' : '';
			$head .= !empty($settings["js"]) ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
			
			$validation = self::validateStatus($EVC, $status, $settings);
			$html = $validation[0];
			$die = $validation[1];
			
			if ($die) {
				if ($html)
					echo '<html>
						<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
							' . $head . '
						</head>
						<body>
							' . $html . '
						</body>
					</html>';
				
				die();
			}
			
			return $html ? $head . $html : '';
		}
	}
	
	private static function validateStatus($EVC, $status, $settings = null) {
		$html = isset($settings["html"]) ? $settings["html"] : null;
		
		if ($status) {
			if (empty($settings["validation_action"])) {
				$settings["validation_action"] = "do_nothing";
			}
			
			$validation_message = isset($settings["validation_message"]) ? $settings["validation_message"] : null;
			$validation_class = isset($settings["validation_class"]) ? $settings["validation_class"] : null;
			$validation_redirect = isset($settings["validation_redirect"]) ? $settings["validation_redirect"] : null;
			$validation_ttl = isset($settings["validation_ttl"]) ? $settings["validation_ttl"] : null;
			$validation_blocks_execution = isset($settings["validation_blocks_execution"]) ? $settings["validation_blocks_execution"] : null;
			
			$action = self::parseAction($EVC, $settings["validation_action"], $validation_message, $validation_class, $validation_redirect, $validation_ttl, $validation_blocks_execution, false);
			$html .= $action[0];
			$die = $action[1];
		}
		else {
			$non_validation_action = isset($settings["non_validation_action"]) ? $settings["non_validation_action"] : null;
			$non_validation_message = isset($settings["non_validation_message"]) ? $settings["non_validation_message"] : null;
			$non_validation_class = isset($settings["non_validation_class"]) ? $settings["non_validation_class"] : null;
			$non_validation_redirect = isset($settings["non_validation_redirect"]) ? $settings["non_validation_redirect"] : null;
			$non_validation_ttl = isset($settings["non_validation_ttl"]) ? $settings["non_validation_ttl"] : null;
			$non_validation_blocks_execution = isset($settings["non_validation_blocks_execution"]) ? $settings["non_validation_blocks_execution"] : null;
			
			$action = self::parseAction($EVC, $non_validation_action, $non_validation_message, $non_validation_class, $non_validation_redirect, $non_validation_ttl, $non_validation_blocks_execution);
			$html .= $action[0];
			$die = $action[1];
		}
		
		return array($html, $die);
	}
	
	private static function parseAction($EVC, $action, $message, $class, $redirect_url, $ttl, $blocks_execution, $is_non = true) {
		$class = ($is_non ? "non_" : "") . "validation_message" . ($class ? " $class" : "");
		$html = $die = $alert_msg = $blocks_execution_active = null;
		
		if (trim($message)) {
			$message = translateProjectText($EVC, $message);
			$alert_msg = "alert('" . addcslashes($message, "\\'") . "');";
		}
		
		switch ($action) {
			case "show_message": 
				$html = '<div class="' . $class . '">' . str_replace("\n", "<br/>", $message) . '</div>';
				$blocks_execution_active = true;
				break;
			case "show_message_and_redirect":
				$html = '<div class="' . $class . '">' . str_replace("\n", "<br/>", $message) . '</div>';
				
				if ($ttl > 0)
					$html .= '<script>
						setTimeout(function() {
							document.location = "' . $redirect_url . '";
						}, ' . ($ttl * 1000) . ');
					</script>';
				else
					$html .= '<script>
						document.location = "' . $redirect_url . '";
					</script>';
				
				$blocks_execution_active = true;
				break;
			case "alert_message": 
				$html = "<script>$alert_msg</script>";
				$blocks_execution_active = true;
				break;
			case "alert_message_and_redirect":
				$html = "<script>
					$alert_msg
					document.location = '" . $redirect_url . "';
				</script>";
				$die = true;
				break;
			case "redirect": 
				header("Location: " . $redirect_url);
				$html = "<script>document.location = '" . $redirect_url . "';</script>";
				$die = true;
				break;
			case "alert_message_and_die":
				header('HTTP/1.0 403 Forbidden');
				$html ="<script>$alert_msg</script>";
				$die = true;
				break;
			case "die": 
				header('HTTP/1.0 403 Forbidden');
				$html ='<div class="' . $class . '">' . str_replace("\n", "<br/>", $message) . '</div>';
				$die = true;
				break;
			case "": //Native
				header('HTTP/1.0 403 Forbidden');
				$html ='<div class="' . $class . '">' . translateProjectText($EVC, "Forbidden") . '</div>';
				$die = true;
				break;
		}
		
		if ($die)
			$EVC->getCMSLayer()->getCMSBlockLayer()->stopAllBlocks();
		else if ($blocks_execution_active && $blocks_execution)
			switch ($blocks_execution) {
				case "stop_all_blocks": 
					$EVC->getCMSLayer()->getCMSBlockLayer()->stopAllBlocks();
					break;
				case "stop_current_block_regions": 
					$EVC->getCMSLayer()->getCMSBlockLayer()->stopCurrentBlockRegions();
					break;
			}
		
		return array($html, $die);
	}
}
?>
