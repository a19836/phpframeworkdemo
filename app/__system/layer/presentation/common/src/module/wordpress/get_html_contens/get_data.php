<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if (!empty($PEVC)) {
	include_once get_lib("org.phpframework.cms.wordpress.WordPressCMSBlockHandler");
	
	$wordpress_installation_name = !empty($_GET["wordpress_installation_name"]) ? $_GET["wordpress_installation_name"] : (isset($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : null);
	$action = isset($_GET["action"]) ? $_GET["action"] : null;
	
	if ($wordpress_installation_name) {
		$WordPressCMSBlockHandler = new \WordPressCMSBlockHandler($PEVC, array(
			"wordpress_folder" => $wordpress_installation_name,
			"cookies_prefix" => "system_wp_" . $wordpress_installation_name,
		));
		
		if ($action == "get_widget_options") {
			$widget_id = isset($_GET["widget_id"]) ? $_GET["widget_id"] : null;
			
			if ($widget_id) {
				//prepare widget_instance
				$widget_instance = isset($_POST["widget_instance"]) ? $_POST["widget_instance"] : null;
				
				if (!isset($widget_instance) && !empty($_POST["widget_options"])) {
					$widget_options = isset($_POST["widget_options"]) ? $_POST["widget_options"] : null;
					$id_base = isset($widget_options["id_base"]) ? $widget_options["id_base"] : null;
					$multi_number = isset($widget_options["multi_number"]) ? $widget_options["multi_number"] : null;
					$widget_instance = isset($widget_options["widget-" . $id_base]) ? $widget_options["widget-" . $id_base] : null;
					
					if (is_array($widget_instance) && isset($multi_number)) 
						$widget_instance = isset($widget_instance[$multi_number]) ? $widget_instance[$multi_number] : null;
				}
				
				//get widget control options html
				$options = array(
					"widget_options" => array("widget_id" => $widget_id, "widget_instance" => $widget_instance),
				);
				$content = $WordPressCMSBlockHandler->getBlockContent("", isset($url_query) ? $url_query : null, $options);
				$results = $content && !empty($content["results"]) ? $content["results"] : null;
				//print_r($results);
				
				if ($results)
					$data = array(
						"widget_id" => $widget_id,
						"widget_options" => isset($results["widget_options"]) ? $results["widget_options"] : null,
					);
			}
		}
		else {
			$functions = array(
				array("name" => "getPages"),
				array("name" => "getCategories"),
				array("name" => "getTags"),
				array(
					"name" => "getAllPosts",
					"args" => array(
						array("numberposts" => 10000000)
					), //numberposts default is 5. change to a big number to get all posts
				),
				array("name" => "getAvailableWidgets"),
				array("name" => "getAvailableSideBars"),
				array("name" => "getAvailableMenus"),
				array("name" => "getAvailableMenuLocations"),
				array("name" => "getSiteUrl"),
				array("name" => "getAvailableThemes"),
			);
			
			$content = $WordPressCMSBlockHandler->getBlockContent("", isset($url_query) ? $url_query : null, array(
				"functions" => $functions,
			));
			$results = $content && !empty($content["results"]) && isset($content["results"]["functions"]) ? $content["results"]["functions"] : null;
			//print_r($results);
			
			if ($results) {
				$pages = isset($results[0]) ? $results[0] : null;
				$categories = isset($results[1]) ? $results[1] : null;
				$tags = isset($results[2]) ? $results[2] : null;
				$posts = isset($results[3]) ? $results[3] : null;
				$widgets = isset($results[4]) ? $results[4] : null;
				$side_bars = isset($results[5]) ? $results[5] : null;
				$menus = isset($results[6]) ? $results[6] : null;
				$menu_locations = isset($results[7]) ? $results[7] : null;
				$site_url = isset($results[8]) ? $results[8] : null;
				$themes = isset($results[9]) ? $results[9] : null;
				$dates = array();
				
				if ($pages) {
					$new_pages = array();
					
					foreach ($pages as $wp_post_obj)
						if (isset($wp_post_obj->post_name))
							$new_pages[ $wp_post_obj->post_name ] = isset($wp_post_obj->post_title) ? $wp_post_obj->post_title : null;
						//if (isset($wp_post_obj->ID))
							//$new_pages[ $wp_post_obj->ID ] = isset($wp_post_obj->post_title) ? $wp_post_obj->post_title : null;
					
					$pages = $new_pages;
				}
				
				if ($categories) {
					$new_categories = array();
					
					foreach ($categories as $wp_term_obj)
						if (isset($wp_term_obj->slug))
							$new_categories[ $wp_term_obj->slug ] = isset($wp_term_obj->name) ? $wp_term_obj->name : null;
						//if (isset($wp_term_obj->term_id))
							//$new_categories[ $wp_term_obj->term_id ] = isset($wp_term_obj->name) ? $wp_term_obj->name : null;
					
					$categories = $new_categories;
				}
				
				if ($tags) {
					$new_tags = array();
					
					foreach ($tags as $wp_term_obj)
						if (isset($wp_term_obj->slug))
							$new_tags[ $wp_term_obj->slug ] = isset($wp_term_obj->name) ? $wp_term_obj->name : null;
						//if (isset($wp_term_obj->term_id))
							//$new_tags[ $wp_term_obj->term_id ] = isset($wp_term_obj->name) ? $wp_term_obj->name : null;
					
					$tags = $new_tags;
				}
				
				if ($posts) {
					$new_posts = array();
					
					foreach ($posts as $wp_post_obj) {
						if (isset($wp_post_obj->post_name))
							$new_posts[ $wp_post_obj->post_name ] = isset($wp_post_obj->post_title) ? $wp_post_obj->post_title : null;
						//if (isset($wp_post_obj->ID))
							//$new_posts[ $wp_post_obj->ID ] = isset($wp_post_obj->post_title) ? $wp_post_obj->post_title : null;
						
						if (!empty($wp_post_obj->post_date)) {
							$date = substr($wp_post_obj->post_date, 0, 10);
							$dates[$date] = $date;
						}
					}
					
					$posts = $new_posts;
				}
				
				if ($widgets) {
					$new_widgets = array();
					
					foreach ($widgets as $widget_id => $widget_props)
						$new_widgets[ $widget_id ] = isset($widget_props["name"]) ? $widget_props["name"] : null;
					
					$widgets = $new_widgets;
				}
				
				if ($side_bars) {
					$new_side_bars = array();
					
					foreach ($side_bars as $side_bar_id => $side_bar_props)
						$new_side_bars[ $side_bar_id ] = isset($side_bar_props["name"]) ? $side_bar_props["name"] : null;
					
					$side_bars = $new_side_bars;
				}
				
				if ($menus) {
					$new_menus = array();
					
					foreach ($menus as $wp_term_obj)
						if (isset($wp_term_obj->slug))
							$new_menus[ $wp_term_obj->slug ] = isset($wp_term_obj->name) ? $wp_term_obj->name : null;
						//if (isset($wp_term_obj->term_id))
							//$new_menus[ $wp_term_obj->term_id ] = isset($wp_term_obj->name) ? $wp_term_obj->name : null;
					
					$menus = $new_menus;
				}
				
				if ($themes) {
					$new_themes = array();
					
					foreach ($themes as $theme_id => $wp_theme_obj)
						if ($theme_id != "phpframework")
							$new_themes[ $theme_id ] = $wp_theme_obj->get("Name");
					
					$themes = $new_themes;
				}
				
				$data = array(
					"themes" => $themes,
					"pages" => $pages,
					"categories" => $categories,
					"tags" => $tags,
					"dates" => $dates,
					"posts" => $posts,
					"widgets" => $widgets,
					"side_bars" => $side_bars,
					"menus" => $menus,
					"menu_locations" => $menu_locations,
					"site_url" => $site_url,
				);
			}
		}
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

echo !empty($data) ? json_encode($data) : "";
?>
