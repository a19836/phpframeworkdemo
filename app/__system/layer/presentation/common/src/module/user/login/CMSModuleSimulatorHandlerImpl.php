<?php
namespace CMSModule\user\login;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		$s = $settings;
		
		$editable_settings = array(
			"elements" => array(
				".module_login .form_fields > .form_field.username > label" => "fields/username/field/label/value",
				".module_login .form_fields > .form_field.password > label" => "fields/password/field/label/value",
				".module_login .register a" => "register_attribute_label",
				".module_login .forgot_credentials a" => "forgot_credentials_attribute_label",
				".module_login .single_sign_on a" => "single_sign_on_attribute_label",
			)
		);
		
		return $this->getCMSModuleHandler()->execute($s);
	}
}
?>
