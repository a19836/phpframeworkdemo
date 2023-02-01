<?php
include_once get_lib("org.phpframework.workflow.WorkFlowTask");

//VALIDATE REQUESTED RESOURCE
$resource_name = $_GET["resource"];

if (!$resource_name && $resource_name !== 0) {
	header("HTTP/1.0 404 Not Found");
	launch_exception(new Exception("Resource cannot be undefined!"));
	die();
}

//PREPARE CONTROLLER
$EVC->setController( basename(__FILE__, ".php") );

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
$default_template = $GLOBALS["project_default_template"];

$EVC->setEntity($default_entity);

if ($default_template)
	$EVC->setTemplate($default_template);

//GET CACHED FILE
$UserCacheHandler = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
$default_entity_code = substr($default_entity, 0, 1) == "/" ? $default_entity : $page_prefix . $default_entity;
$cache_key = $EVC->getController() . "_controller/" . $EVC->getPresentationLayer()->getSelectedPresentationId() . "/" . str_replace("/", "_", $default_entity_code) . "." . $EVC->getPresentationLayer()->getPresentationFileExtension();
$file_included = false;

ob_start();

if ($UserCacheHandler) {
	$UserCacheHandler->config(false, false);
	
	if ($UserCacheHandler->isValid($cache_key)) {
		$cached_file_path = CacheHandlerUtil::getCacheFilePath($UserCacheHandler->getRootPath() . $cache_key);
		//echo "cached_file_path:$cached_file_path:".file_exists($cached_file_path);die();
		
		if (file_exists($cached_file_path)) {
			include $cached_file_path;
			$file_included = true;
		}
	}
}

if (!$file_included) {
	$main_contents = "";
	
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
			
			if (file_exists($entity_path)) {
				$contents = file_get_contents($entity_path);
				$parsed_contents = parseFileContents($contents);
				
				if ($contents != $parsed_contents) {
					$temp = tmpfile();
					fwrite($temp, $parsed_contents);
					
					$path = stream_get_meta_data($temp)['uri'];
					include $path;
					
					fclose($temp); // this removes the file
				}
				else
					include $entity_path;
				
				$main_contents .= $parsed_contents;
			}
			else {
				header("HTTP/1.0 404 Not Found");
				launch_exception(new EVCException(2, $entity_path));
			}
			
			//Each entity can add or remove other entities, so we need to update the $entities everytime we call an entity
			$entities = $EVC->getEntities(); 
			$entities_params = $EVC->getEntitiesParams();
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
			
			if (file_exists($template_path)) {
				$contents = file_get_contents($template_path);
				$parsed_contents = parseFileContents($contents);
				
				if ($contents != $parsed_contents) {
					$temp = tmpfile();
					fwrite($temp, $parsed_contents);
					
					$path = stream_get_meta_data($temp)['uri'];
					include $path;
					
					fclose($temp); // this removes the file
				}
				else
					include $template_path;
				
				$main_contents .= $parsed_contents;
			}
			else {
				header("HTTP/1.0 404 Not Found");
				launch_exception(new EVCException(4, $template_path));
			}
			
			//Each template can add or remove other templates, so we need to update the $templates everytime we call an template
			$templates = $EVC->getTemplates(); 
			$templates_params = $EVC->getTemplatesParams();
		}
	}
	
	//CACHE MAIN CONTENTS
	if ($UserCacheHandler && $main_contents) {
		$UserCacheHandler->config(false, false); //just in case the entities or template code changed this cobfig
		$UserCacheHandler->write($cache_key, $main_contents);
	}
}

ob_end_clean();

//if ($file_included) echo "from cache\n<br>";
//print_r($EVC->getCMSLayer()->getCMSSequentialLogicalActivityLayer()->getSLASettings());die();

$output = $EVC->getCMSLayer()->getCMSSequentialLogicalActivityLayer()->executeSequentialLogicalActivities(); //This method only executes the sla_settings once, if they were not executed yet.

$sla_results = $EVC->getCMSLayer()->getCMSSequentialLogicalActivityLayer()->getSLAResults();
//print_r($sla_results);

if ($sla_results && array_key_exists($resource_name, $sla_results)) {
	$result = $sla_results[$resource_name];
	
	//if result is from an insert, update or delete action, do not enter here, bc the $result will be true or false or numeric in case of auto_incremented primary keys.
	if (is_array($result) || is_object($result))
		$result = json_encode($result);
	
	echo $result;
}

//note that we sould leave the includes bc the sla might be inside of another files. However we should remove the includes for blocks, bc we don't want to execute the blocks.
function parseFileContents($contents) {
	//remove include block from entity code
	$includes_block = CMSFileHandler::getIncludesBlock($contents);
	//echo "<pre>";print_r($includes_block);die();
	$t = count($includes_block);
	
	for ($i = 0; $i < $t; $i++) {
		$include_block_code = $includes_block[$i]["match"];
		$contents = str_replace($include_block_code, "", $contents);
	}
	
	//remove echo of hard coded blocks from entity code
	$hard_coded_regions_blocks = CMSFileHandler::getHardCodedRegionsBlocks($contents);
	//echo "<pre>";print_r($hard_coded_regions_blocks);die();
	$t = count($hard_coded_regions_blocks);
	
	for ($i = 0; $i < $t; $i++) {
		$hard_coded_region_block_code = $hard_coded_regions_blocks[$i]["match"];
		$contents = str_replace($hard_coded_region_block_code, '""', $contents);
	}
	
	//reset all template regions
	$regions_blocks = CMSFileHandler::getRegionsBlocks($contents);
	//echo "<pre>";print_r($regions_blocks);die();
	$t = count($regions_blocks);
	$reset_region_code = "";
	$repeated_region_code = array();
	
	for ($i = 0; $i < $t; $i++) {
		$region_block = $regions_blocks[$i];
		$region_code = WorkFlowTask::getVariableValueCode($region_block["region"], $region_block["region_type"]);
		
		if (!in_array($region_code, $repeated_region_code)) {
			$repeated_region_code[] = $region_code;
			$reset_region_code .= "\$EVC->getCMSLayer()->getCMSTemplateLayer()->resetRegion($region_code);\n";
		}
	}
	
	if ($reset_region_code)
		$contents .= "<?php\n$reset_region_code?>";
	
	//remove all rendered regions
	$rendered_regions = CMSFileHandler::getRegions($contents);
	//echo "<pre>";print_r($rendered_regions);die();
	$t = count($rendered_regions);
	
	for ($i = 0; $i < $t; $i++) {
		$rendered_region_code = $rendered_regions[$i]["match"];
		$contents = str_replace($rendered_region_code, '""', $contents);
	}
	
	return $contents;
}
?>
