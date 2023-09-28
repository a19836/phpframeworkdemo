<?php
namespace CMSModule\menu\show_menu;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$html = "";
		$type = $settings["type"];
		$class = trim($settings["class"] . " " .  $settings["block_class"]);
		$ul_class = "";
		
		switch ($type) {
			case "accordion":
			case "accordion_black":
			case "accordion_blue":
			case "accordion_clean":
			case "accordion_demo":
			case "accordion_graphite":
			case "accordion_grey":
				$ul_class = "accordion";
				
				if ($type != "accordion") {
					$accordion_style_class .= str_replace("accordion_", "", $type);
					$class = trim($accordion_style_class . " $class");
					
					$html_aux = '<link href="' . $project_common_url_prefix . 'module/menu/jqueryverticalaccordionmenu/css/skins/' . $accordion_style_class . '.css" rel="stylesheet" type="text/css" />';
				}
				
				$html .= '
	<!-- Add VerticalAccordionMenu main JS and CSS files -->
	<link href="' . $project_common_url_prefix . 'module/menu/jqueryverticalaccordionmenu/css/dcaccordion.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="' . $project_common_url_prefix . 'module/menu/jqueryverticalaccordionmenu/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="' . $project_common_url_prefix . 'module/menu/jqueryverticalaccordionmenu/js/jquery.hoverIntent.minified.js"></script>
	<script type="text/javascript" src="' . $project_common_url_prefix . 'module/menu/jqueryverticalaccordionmenu/js/jquery.dcjqaccordion.2.7.min.js"></script>
	' . $html_aux . '
	<script type="text/javascript">
	(function($){ //create closure so we can safely use $ as alias for jQuery
		$(document).ready(function($){
			$(".module_menu' . ($class ? "." . implode(".", explode(" ", $class)) : "") . ' .menu_main_ul.' . $ul_class . '").dcAccordion({
				eventType: "click",
				autoClose: false,
				//menuClose: true,
				saveState: true,
				disableLink: true,
				speed: "slow",
				showCount: true,
				autoExpand: true,
				cookie: "dcjq-dropdown' . ($class ? "-$class" : "") . '",
				classExpand: "menu-open",
			});
		});
	})(jQuery);
	</script>';
				break;
			
			case "horizontal_simple_dropdown":
				$ul_class = "dropdown";
				
				$html .= '
	<!-- Add SimpleDropDown main JS and CSS files -->
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerysimpledropdowns/css/style.css" type="text/css" media="screen, projection"/>
	<!--[if lte IE 7]>
		<link rel="stylesheet" type="text/css" href="' . $project_common_url_prefix . 'vendor/jquerysimpledropdowns/css/ie.css" media="screen" />
	<![endif]-->
	<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerysimpledropdowns/js/jquery.dropdownPlain.js"></script>';
				break;
			case "horizontal_superfish_basic":
			case "horizontal_superfish_navbar":
			case "vertical_superfish":
				$ul_class = "sf-menu";
				
				if ($type == "horizontal_superfish_navbar") {
					$ul_class .= " sf-navbar";
					$html_aux = '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/menu/jquerysuperfishmenu/dist/css/superfish-navbar.css" media="screen">';
				}
				else if ($type == "vertical_superfish") {
					$ul_class .= " sf-vertical";
					$html_aux = '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/menu/jquerysuperfishmenu/dist/css/superfish-vertical.css" media="screen">';
				}
				
				$html .= '
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/menu/jquerysuperfishmenu/dist/css/superfish.css" media="screen">
	' . $html_aux . '
	<script src="' . $project_common_url_prefix . 'module/menu/jquerysuperfishmenu/dist/js/hoverIntent.js"></script>
	<script src="' . $project_common_url_prefix . 'module/menu/jquerysuperfishmenu/dist/js/superfish.js"></script>
	<script>
	(function($){ //create closure so we can safely use $ as alias for jQuery
		$(document).ready(function() {
			$(".module_menu' . ($class ? "." . implode(".", explode(" ", $class)) : "") . ' .menu_main_ul.' . implode(".", explode(" ", $ul_class)) . '").superfish({
				animation: {height:"show"},
				speed: "fast",
			});
		});
	})(jQuery);
	</script>';
				break;
			case "vertical":
				$ul_class = "vertical-menu";
				$css_main_class = '.module_menu' . ($class ? "." . implode(".", explode(" ", $class)) : "");
				$css_main_ul_class = $css_main_class . ' .menu_main_ul.' . $ul_class;
				
				$html .= '
	<style>
		' . $css_main_class . ' {
			background:inherit;
			display:inline-block;
		}
		' . $css_main_ul_class . ' {
			background:inherit;
			padding:0;
		}
		' . $css_main_ul_class . ' li {
			padding:10px 20px;
			position:relative;
			background:inherit;
			display:block;
		}
		' . $css_main_ul_class . ' li a {
			text-decoration:none;
		}
		' . $css_main_ul_class . ' li a label {
			cursor:pointer;
		}
		' . $css_main_ul_class . ' li ul {
			padding-left:0;
			position:absolute;
    			top:-1px;
			left:100%;
			background:inherit;
			border:1px solid transparent;
			border-top-left-radius:0;
			border-top-right-radius:5px;
			border-bottom-left-radius:0;
			border-bottom-right-radius:5px;
			z-index:1;
    			display:none;
		}
		' . $css_main_ul_class . ' li:hover > ul {
			display:block;
		}
	</style>';
				break;
			case "horizontal":
				$ul_class = "horizontal-menu";
				$css_main_class = '.module_menu' . ($class ? "." . implode(".", explode(" ", $class)) : "");
				$css_main_ul_class = $css_main_class . ' .menu_main_ul.' . $ul_class;
				
				$html .= '
	<style>
		' . $css_main_class . ' {
			background:inherit;
		}
		' . $css_main_ul_class . ' {
			background:inherit;
			padding:0;
		}
		' . $css_main_ul_class . ' li {
			padding:10px 20px;
			position:relative;
			display:inline-block;
			background:inherit;
		}
		' . $css_main_ul_class . ' li a {
			text-decoration:none;
		}
		' . $css_main_ul_class . ' li a label {
			cursor:pointer;
		}
		' . $css_main_ul_class . ' li ul {
			padding-left:0;
			position:absolute;
    			display:none;
			background:inherit;
			border:1px solid transparent;
			border-bottom-left-radius:5px;
			border-bottom-right-radius:5px;
			z-index:1;
		}
		' . $css_main_ul_class . ' li:hover > ul {
			display:block;
		}
		' . $css_main_ul_class . ' li ul li {
			display:block;
		}
		' . $css_main_ul_class . ' li ul li > ul {
			top:-1px;
			left:100%;
			border-top-left-radius:0;
			border-top-right-radius:5px;
			border-bottom-left-radius:0;
			border-bottom-right-radius:5px;
		}
	</style>';
				break;
		}
		
		$menus = $settings["items_type"] == "from_db" ? self::getMenuGroupItems($EVC, $settings) : $settings["menus"];
		self::prepareMenus($EVC, $menus);
		
		$extra_css = self::getMenuExtraStyles($settings, $class, $ul_class);
		if ($extra_css)
			$settings["css"] = $extra_css . "\n" . $settings["css"];
		
		$html .= ($settings["css"] ? '<style>' . $settings["css"] . '</style>' : '') . '
		' . ($settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '') . '
		
		<div class="module_menu ' . ($class ? $class : "") . '">
			' . ($settings["title"] ? '<h3>' . translateProjectText($EVC, $settings["title"]) . '</h3>' : '');
		
		$form_settings = array(
			"CacheHandler" => $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler")
		);
		
		if ($settings["template_type"] == "user_defined") {
			$form_settings["ptl"] = $settings["ptl"];
			$html .= \HtmlFormHandler::createHtmlForm($form_settings, $menus);
		}
		else {
			$settings["ptl"] = null;
			\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "menu/show_menu", $settings, array("ul_class" => $ul_class));
			
			if (isset($settings["ptl"])) {
				$form_settings["ptl"] = $settings["ptl"];
				$html .= \HtmlFormHandler::createHtmlForm($form_settings, $menus);
			}
			else
				$html .= '<ul class="menu_main_ul ' . $ul_class . '">' . self::getMenusHTML($menus) . '</ul>';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	private static function getMenuExtraStyles($settings, $class, $ul_class) {
		$css = "";
		
		$css_main_class = '.module_menu' . ($class ? "." . implode(".", explode(" ", $class)) : "");
		$css_main_ul_class = $css_main_class . ' .menu_main_ul.' . $ul_class;
		
		if ($settings["menu_background_color"])
			$css .= '
' . $css_main_class . ' {
	background-color:' . $settings["menu_background_color"] . ';
}';
		
		if ($settings["menu_background_image"])
			$css .= '
' . $css_main_class . ' {
	background-image:url("' . addcslashes($settings["menu_background_image"], '"') . '");
}';
		
		if ($settings["menu_text_color"])
			$css .= '
' . $css_main_class . ' li,
  ' . $css_main_class . ' li a,
  ' . $css_main_class . ' li a label {
	color:' . $settings["menu_text_color"] . ';
}';
		
		if ($settings["sub_menu_background_color"])
			$css .= '
' . $css_main_ul_class . ' li ul {
	background-color:' . $settings["sub_menu_background_color"] . ';
}';
		
		if ($settings["sub_menu_background_image"])
			$css .= '
' . $css_main_ul_class . ' li ul {
	background-image:url("' . addcslashes($settings["sub_menu_background_image"], '"') . ');
}';
		
		if ($settings["sub_menu_text_color"])
			$css .= '
' . $css_main_ul_class . ' li ul li,
  ' . $css_main_ul_class . ' li ul li a,
  ' . $css_main_ul_class . ' li ul li a label {
	color:' . $settings["sub_menu_text_color"] . ';
}';
		
		return $css;
	}
	
	private static function getMenuGroupItems($EVC, $settings) {
		$new_items =  array();
		
		include_once $EVC->getModulePath("menu/MenuUtil", $EVC->getCommonProjectName());
		include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
	
		$options = array("sort" => array(array("column" => "order", "order" => "asc")));
		
		switch($settings["menu_query_type"]) {
			case "first_menu_by_tag_and":
				$tags = $settings["tags"];
				if ($tags) {
					$data = \MenuUtil::getMenuGroupsWithAllTags($brokers, $tags, array(), null);
					$items = \MenuUtil::getMenuItemsByConditions($brokers, array("group_id" => $data[0]["group_id"]), null, $options, true);
				}
				break;
			case "first_menu_by_tag_or":
				$tags = $settings["tags"];
				if ($tags) {
					$data = \MenuUtil::getMenuGroupsByTags($brokers, $tags, array(), null);
					$items = \MenuUtil::getMenuItemsByConditions($brokers, array("group_id" => $data[0]["group_id"]), null, $options, true);
				}
				break;
			case "first_menu_by_parent":
				$items = \MenuUtil::getMenuItemsByFirstGroupIdOfObject($brokers, $settings["object_type_id"], $settings["object_id"], $options);
				break;
			case "first_menu_by_parent_group":
				$items = \MenuUtil::getMenuItemsByFirstGroupIdOfObjectGroup($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $options);
				break;
			case "user_defined":
			default://selected_menu
				$items = \MenuUtil::getMenuItemsByConditions($brokers, array("group_id" => $settings["menu_group_id"]), null, $options);
		}
		
		$item_label = translateProjectText($EVC, $settings["item_label"]);
		$item_title = translateProjectText($EVC, $settings["item_title"]);
		
		$HtmlFormHandler = new \HtmlFormHandler();
		
		if ($items) {
			$t = count($items);
			for ($i = 0; $i < $t; $i++) {
				$item = $items[$i];
				
				$new_items[] = array(
					"item_id" => $item["item_id"],
					"parent_id" => $item["parent_id"],
					"label" => $HtmlFormHandler->getParsedValueFromData($item_label, $item),
					"title" => $HtmlFormHandler->getParsedValueFromData($item_title, $item),
					"class" => $HtmlFormHandler->getParsedValueFromData($settings["item_class"], $item),
					"url" => $HtmlFormHandler->getParsedValueFromData($settings["item_url"], $item),
					"attrs" => $HtmlFormHandler->getParsedValueFromData($settings["item_attrs"], $item),
					"previous_html" => $HtmlFormHandler->getParsedValueFromData($settings["item_previous_html"], $item),
					"next_html" => $HtmlFormHandler->getParsedValueFromData($settings["item_next_html"], $item),
				);
			}
		}
		
		$new_items = \MenuUtil::encapsulateMenuGroupItems($new_items, 0, "menus");
		
		return $new_items;
	}
	
	private static function getMenusHTML($menus) {
		$html = '';
		
		if ($menus) {
			$t = count($menus);
			for ($i = 0; $i < $t; $i++) {
				$menu = $menus[$i];
				
				$title = $menu["title"] ? 'title="' . $menu["title"] . '"' : "";
				
				$html .= '<li class="module_menu_item_class ' . $menu["class"] . '" ' . $title . ' ' . $menu["attrs"] . '>
					' . $menu["previous_html"] . '
					<a href="' . ($menu["url"] ? str_replace(" ", "%20", $menu["url"]) : 'javascript:void(0)') . '"><label>' . $menu["label"] . '</label></a>';
				
				if ($menu["menus"])
					$html .= '<ul>' . self::getMenusHTML($menu["menus"]) . '</ul>';
				
				$html .= $menu["next_html"] . '
					</li>';
			}
		}
		
		return $html;
	}
	
	private static function prepareMenus($EVC, &$menus) {
		if ($menus) {
			$t = count($menus);
			for ($i = 0; $i < $t; $i++) {
				if ($menus[$i]["parent_id"]) //to be used by the ptl
					$menus[$i]["parent-id"] = $menus[$i]["parent_id"];
					
				if ($menus[$i]["previous_html"]) //to be used by the ptl
					$menus[$i]["previous-html"] = $menus[$i]["previous_html"];
					
				if ($menus[$i]["next_html"]) //to be used by the ptl
					$menus[$i]["next-html"] = $menus[$i]["next_html"];
					
				if ($menus[$i]["title"])
					$menus[$i]["title"] = translateProjectText($EVC, $menus[$i]["title"]);
			
				if ($menus[$i]["label"])
					$menus[$i]["label"] = translateProjectText($EVC, $menus[$i]["label"]);
				
				if ($menus[$i]["menus"])
					self::prepareMenus($EVC, $menus[$i]["menus"]);
			}
		}
	}
}
?>
