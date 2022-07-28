<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include_once get_lib("org.phpframework.cms.wordpress.WordPressCMSBlockHandler");
	
	$wordpress_installation_name = $_GET["wordpress_installation_name"] ? $_GET["wordpress_installation_name"] : $GLOBALS["default_db_driver"];
	$action = $_GET["action"];
	
	if ($wordpress_installation_name) {
		$WordPressCMSBlockHandler = new \WordPressCMSBlockHandler($PEVC, array(
			"wordpress_folder" => $wordpress_installation_name,
			"cookies_prefix" => "system_wp_" . $wordpress_installation_name,
		));
		
		if ($action == "get_widget_options") {
			$widget_id = $_GET["widget_id"];
			
			if ($widget_id) {
				//prepare widget_instance
				$widget_instance = $_POST["widget_instance"];
				
				if (!isset($widget_instance) && $_POST["widget_options"]) {
					$widget_options = $_POST["widget_options"];
					$id_base = $widget_options["id_base"];
					$multi_number = $widget_options["multi_number"];
					$widget_instance = $widget_options["widget-" . $id_base];
					
					if (is_array($widget_instance) && isset($multi_number)) 
						$widget_instance = $widget_instance[$multi_number];
				}
				
				//get widget control options html
				$options = array(
					"widget_options" => array("widget_id" => $widget_id, "widget_instance" => $widget_instance),
				);
				$content = $WordPressCMSBlockHandler->getBlockContent("", $url_query, $options);
				$results = $content && $content["results"] ? $content["results"] : null;
				//print_r($results);
				
				if ($results)
					$data = array(
						"widget_id" => $widget_id,
						"widget_options" => $results["widget_options"],
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
			
			$content = $WordPressCMSBlockHandler->getBlockContent("", $url_query, array(
				"functions" => $functions,
			));
			$results = $content && $content["results"] ? $content["results"]["functions"] : null;
			//print_r($results);
			
			if ($results) {
				$pages = $results[0];
				$categories = $results[1];
				$tags = $results[2];
				$posts = $results[3];
				$widgets = $results[4];
				$side_bars = $results[5];
				$menus = $results[6];
				$menu_locations = $results[7];
				$site_url = $results[8];
				$themes = $results[9];
				$dates = array();
				
				if ($pages) {
					$new_pages = array();
					
					foreach ($pages as $wp_post_obj)
						//$new_pages[ $wp_post_obj->ID ] = $wp_post_obj->post_title;
						$new_pages[ $wp_post_obj->post_name ] = $wp_post_obj->post_title;
					
					$pages = $new_pages;
				}
				
				if ($categories) {
					$new_categories = array();
					
					foreach ($categories as $wp_term_obj)
						//$new_categories[ $wp_term_obj->term_id ] = $wp_term_obj->name;
						$new_categories[ $wp_term_obj->slug ] = $wp_term_obj->name;
					
					$categories = $new_categories;
				}
				
				if ($tags) {
					$new_tags = array();
					
					foreach ($tags as $wp_term_obj)
						//$new_tags[ $wp_term_obj->term_id ] = $wp_term_obj->name;
						$new_tags[ $wp_term_obj->slug ] = $wp_term_obj->name;
					
					$tags = $new_tags;
				}
				
				if ($posts) {
					$new_posts = array();
					
					foreach ($posts as $wp_post_obj) {
						//$new_posts[ $wp_post_obj->ID ] = $wp_post_obj->post_title;
						$new_posts[ $wp_post_obj->post_name ] = $wp_post_obj->post_title;
						
						if ($wp_post_obj->post_date) {
							$date = substr($wp_post_obj->post_date, 0, 10);
							$dates[$date] = $date;
						}
					}
					
					$posts = $new_posts;
				}
				
				if ($widgets) {
					$new_widgets = array();
					
					foreach ($widgets as $widget_id => $widget_props)
						$new_widgets[ $widget_id ] = $widget_props["name"];
					
					$widgets = $new_widgets;
				}
				
				if ($side_bars) {
					$new_side_bars = array();
					
					foreach ($side_bars as $side_bar_id => $side_bar_props)
						$new_side_bars[ $side_bar_id ] = $side_bar_props["name"];
					
					$side_bars = $new_side_bars;
				}
				
				if ($menus) {
					$new_menus = array();
					
					foreach ($menus as $wp_term_obj)
						//$new_menus[ $wp_term_obj->term_id ] = $wp_term_obj->name;
						$new_menus[ $wp_term_obj->slug ] = $wp_term_obj->name;
					
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

echo $data ? json_encode($data) : "";
?>
