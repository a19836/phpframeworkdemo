<?php
namespace CMSModule\form;

include_once get_lib("org.phpframework.layer.presentation.cms.SequentialLogicalActivities");

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		
		$html = '';
		
		//load old form settings - Do not remove this code until all the old forms have the new settings
		if ($settings[0]) {
			$form_settings = $settings[0];
			$input_data = $settings[1];
			
			translateProjectFormSettings($EVC, $form_settings);
			
			$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
			
			include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");
			
			$html = \HtmlFormHandler::createHtmlForm($form_settings, $input_data);
		}
		else if ($settings) {
			foreach ($settings as $type => $value) {
				switch ($type) {
					case "actions":
						$SequentialLogicalActivities = new \SequentialLogicalActivities();
						$SequentialLogicalActivities->setEVC($EVC);
						$html .= $SequentialLogicalActivities->execute($value);
						break;
					
					case "css":
						$html .= $value ? "<style>$value</style>" : "";
						break;
					
					case "js":
						$html .= $value ? "<script>$value</script>" : "";
						break;
				}
			}
		}
		
		return $html;
	}
}
?>
