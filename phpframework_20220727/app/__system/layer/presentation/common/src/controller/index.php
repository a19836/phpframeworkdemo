<?php
//PREPARE PAGE
$page_prefix = "";
$page = "";
$parameters_count = $parameters ? count($parameters) : 0;
$entities_path = $EVC->getEntitiesPath();
$controller_exists_in_url = preg_match("/^index\//", $url);

//if url is something like "example.com/index/article/list" the parameters will be array(article, list), without the index, bc index is a controller. So we want to add the index to the path_prefix if it is a folder. If index folder exists, takes priority!
if ($controller_exists_in_url && is_dir($entities_path . "index"))
	$page_prefix = "index/";

for ($i = 0; $i < $parameters_count; $i++) {
	$page = $parameters[$i];
	
	if ($page) {
		if (is_dir($entities_path . $page_prefix . $page)) {
			if ($i + 1 == $parameters_count && is_file($EVC->getEntityPath($page_prefix . $page))) //if last parameter and is a php file (besides a directory), gives priority to the file.
				break;
			
			$page_prefix .= $page . "/";
			$page = "";
		}
		else
			break;
	}
}

//PREPARE DEFAULTS
$default_entity = $page ? $page : ($GLOBALS["project_default_entity"] ? $GLOBALS["project_default_entity"] : "index");
$default_view = $default_entity && $GLOBALS["project_with_auto_view"] ? $default_entity : ($GLOBALS["project_default_view"] ? $GLOBALS["project_default_view"] : null);
$default_template = $GLOBALS["project_default_template"];

$EVC->setEntity($default_entity);

if ($default_view) 
	$EVC->setView($default_view);

if ($default_template)
	$EVC->setTemplate($default_template);

//PREPARE ENTITIES
$entities = $EVC->getEntities();
$entities_params = $EVC->getEntitiesParams();

for ($entity_index = 0; $entity_index < count($entities); ++$entity_index) {
	$entity = $entities[$entity_index];
	
	if ($entity) {
		$entity_params = $entities_params[$entity_index];
		$entity_project_id = $entity_params ? $entity_params["project_id"] : false;
		$entity = substr($entity, 0, 1) == "/" ? $entity : $page_prefix . $entity;
		$entity_path = $EVC->getEntityPath($entity, $entity_project_id);
		
		if (file_exists($entity_path)) 
			include $entity_path;
		else {
			header("HTTP/1.0 404 Not Found");
			launch_exception(new EVCException(2, $entity_path));
		}
		
		//Each entity can add or remove other entities, so we need to update the $entities everytime we call an entity
		$entities = $EVC->getEntities(); 
		$entities_params = $EVC->getEntitiesParams();
	}
}

//PREPARE VIEWS
$views = $EVC->getViews();
$views_params = $EVC->getViewsParams();

for ($view_index = 0; $view_index < count($views); ++$view_index) {
	$view = $views[$view_index];
	
	if ($view) {
		$view_params = $views_params[$view_index];
		$view_project_id = $view_params ? $view_params["project_id"] : false;
		$view = substr($view, 0, 1) == "/" ? $view : $page_prefix . $view;
		$view_path = $EVC->getViewPath($view, $view_project_id);
		
		if (file_exists($view_path)) 
			include $view_path;
		else if ($views[$view_index] != $default_view) { //if is equal to $default_entity or $project_default_view, means that the $default_view is optional and only gets included if exists. Note that the $project_default_view may exists but only for root, and not for a specific folder, so it must be optional.
			header("HTTP/1.0 404 Not Found");
			launch_exception(new EVCException(3, $view_path));
		}
		
		//Each view can add or remove other views, so we need to update the $views everytime we call an view
		$views = $EVC->getViews(); 
		$views_params = $EVC->getViewsParams();
	}
}

//PREPARE TEMPLATES
$templates = $EVC->getTemplates();
$templates_params = $EVC->getTemplatesParams();

for ($template_index = 0; $template_index < count($templates); ++$template_index) {
	$template = $templates[$template_index];
	
	if ($template) {
		$template_params = $templates_params[$template_index];
		$template_project_id = $template_params ? $template_params["project_id"] : false;
		$template_path = $EVC->getTemplatePath($template, $template_project_id);
		
		if (file_exists($template_path))
			include $template_path;
		else {
			header("HTTP/1.0 404 Not Found");
			launch_exception(new EVCException(4, $template_path));
		}
		
		//Each template can add or remove other templates, so we need to update the $templates everytime we call an template
		$templates = $EVC->getTemplates(); 
		$templates_params = $EVC->getTemplatesParams();
	}
}
?>
