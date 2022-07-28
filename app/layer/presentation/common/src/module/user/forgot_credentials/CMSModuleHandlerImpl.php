<?php
namespace CMSModule\user\forgot_credentials;

include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");
include_once get_lib("org.phpframework.util.web.SmtpEmail");

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$username_attribute_label = $settings["username_attribute_label"] ? $settings["username_attribute_label"] : "Username";
		$password_attribute_label = $settings["password_attribute_label"] ? $settings["password_attribute_label"] : "Password";
		
		$step = $_GET["step"];
		$step = !$settings["show_recover_username_through_email"] && $step == "recover_username_through_email" ? null : $step;
		$step = !$settings["show_recover_username_through_email_and_security_questions"] && $step == "recover_username_through_email_and_security_questions" ? null : $step;
		$step = !$settings["show_recover_password_through_email"] && $step == "recover_password_through_email" ? null : $step;
		$step = !$settings["show_recover_password_through_security_questions"] && $step == "recover_password_through_security_questions" ? null : $step;
		
		//Including Stylesheet
		$html = '';
		if (empty($settings["style_type"]))
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/user/forgot_credentials.css" type="text/css" charset="utf-8" />';
		
		$html .= $settings["css"] ? '<style>' . $settings["css"] . '</style>' : '';
		$html .= $settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
		
		//Preparing HTML
		$html .= '<div class="module_forgot_credentials ' . ($settings["block_class"]) . '">';
		
		if ($step) {
			if ($_GET["answer_to_security_questions"]) {
				$username = $_POST["username"];
				$email = $_POST["email"];
				$html .= $this->showSecurityQuestions($settings, $step, $username, $email);
			}
			else
				$html .= $this->recoverUsername($settings, $step, $username_attribute_label);
		}
		else {
			$html .= '<div class="choices">
				<label class="main_label">' . translateProjectText($EVC, "Please choose one of the recovery options") . ':</label>
				<ul>';
		
			if ($settings["show_recover_username_through_email"]) {
				$text = translateProjectText($EVC, "Recover #username_attribute# throught email.");
				$html .= "<li><a href=\"?step=recover_username_through_email\">" . str_replace("#username_attribute#", $username_attribute_label, $text) . "</a></li>";
			}
			
			if ($settings["show_recover_username_through_email_and_security_questions"]) {
				$text = translateProjectText($EVC, "Recover #username_attribute# throught email and security questions.");
				$html .= "<li><a href=\"?step=recover_username_through_email_and_security_questions\">" . str_replace("#username_attribute#", $username_attribute_label, $text) . "</a></li>";
			}
			
			if ($settings["show_recover_password_through_email"]) {
				$text = translateProjectText($EVC, "Recover #password_attribute# throught email.");
				$html .= "<li><a href=\"?step=recover_password_through_email\">" . str_replace("#password_attribute#", $password_attribute_label, $text) . "</a></li>";
			}
			
			if ($settings["show_recover_password_through_security_questions"]) {
				$text = translateProjectText($EVC, "Recover #password_attribute# throught security questions.");
				$html .= "<li><a href=\"?step=recover_password_through_security_questions\">" . str_replace("#password_attribute#", $password_attribute_label, $text) . "</a></li>";
			}
		
			$html .= '</ul>
			</div>';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	private function showSuccessfullMessage($settings, $step, $user_data) {
		$EVC = $this->getEVC();
		
		$html = '<div class="info">';
		
		switch ($step) {
			case "recover_username_through_email": 
				$html .= translateProjectText($EVC, 'Email sent successfully.') . '<br/>' . translateProjectText($EVC, "Please check your email to get your username.");
				break;
			case "recover_username_through_email_and_security_questions":
				$html .= translateProjectText($EVC, 'Your username is') . ': "' . $user_data["username"] . '".';
				break;
			case "recover_password_through_email":
				$html .= translateProjectText($EVC, 'Email sent successfully.') . '<br/>' . translateProjectText($EVC, "Please check your email to get your new password.");
				break;
			case "recover_password_through_security_questions": 
				$html .= translateProjectText($EVC, 'Your password is') . ': "' . $user_data["password"] . '".';
				break;
		}
		
		if ($settings["redirect_page_url"]) {
			$html .= '<script>setTimeout(function() {document.location=\'' . $settings["redirect_page_url"] . '\';}, 5000);</script>';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	private function showSecurityQuestions($settings, $step, $username, $email) {
		$EVC = $this->getEVC();
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting user_data
		if ($username) {
			$users = \UserUtil::getUsersByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("username" => $username), null, null, true);
			$field_name = "username";
			$field_value = $username;
		}
		else if ($email) {
			$users = \UserUtil::getUsersByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("email" => $email), null, null, true);
			$field_name = "email";
			$field_value = $email;
		}
		
		$user_data = $users[0];
			
		if (!$user_data["user_id"])
			return '<div class="error">' . translateProjectText($EVC, "No user detected. Please start again this process from the beginning...") . '</div>';
		
		//Preparing POST
		if ($_POST["security_answers"]) {
			$security_answers = $_POST["security_answers"];
			
			if (is_array($security_answers) && count($security_answers) == 3) {
				$status = true;
				foreach ($security_answers as $idx => $security_answer) {
					if (empty($security_answer) || $security_answer != $user_data["security_answer_$idx"]) {
						$status = false;
						break;
					}
				}
				
				if (!$status)
					$message = translateProjectText($EVC, "Incorrect answers. Please try again...");
				else {
					if ($step == "recover_password_through_security_questions") {
						$user_data["password"] = uniqid();
						$user_data["do_not_encrypt_password"] = $settings["do_not_encrypt_password"];
						
						if (!\UserUtil::updateUserPassword($brokers, $user_data)) 
							$message = translateProjectText($EVC, "There was an error trying to save the new password to the DB. Please try again...");
					}
					
					if (!$message)
						return $this->showSuccessfullMessage($settings, $step, $user_data);
				}
			}
		}
		
		//Preparing HTML
		$html = '';
		
		if ($message)
			$html .= \CommonModuleUI::getModuleMessagesHtml($EVC, $message, null);
		
		$form_settings = array(
			"with_form" => 1,
			"form_id" => "",
			"form_method" => "post",
			"form_class" => "",
			"form_on_submit" => "",
			"form_action" => "?step=$step&answer_to_security_questions=1",
			"form_containers" => array(
				0 => array(
					"container" => array(
						"class" => "form_fields",
						"previous_html" => "",
						"next_html" => "",
						"elements" => array(
							0 => array(
								"field" => array(
									"class" => "form_field hidden",
									"input" => array(
										"type" => "hidden",
										"name" => $field_name,
										"value" => $field_value,
									)
								)
							),
						)
					)
				),
				1 => array(
					"container" => array(
						"class" => "buttons",
						"elements" => array(
							0 => array(
								"field" => array(
									"class" => "submit_button",
									"input" => array(
										"type" => "submit",
										"name" => "continue",
										"value" => "Continue",
									)
								)
							),
						)
					)
				),
			)
		);
		
		for ($i = 1; $i <= 3; $i++) {
			$form_settings["form_containers"][0]["container"]["elements"][] = array(
				"field" => array(
					"class" => "form_field",
					"label" => array(
						"value" => $user_data["security_question_$i"],
					),
					"input" => array(
						"type" => "text",
						"name" => "security_answers[$i]",
						"extra_attributes" => array(
							0 => array(
								"name" => "allowNull",
								"value" => "false"
							),
							1 => array(
								"name" => "validationMessage",
								"value" => "Answer cannot be undefined!"
							)
						),
					)
				)
			);
		}
	
		$html .= '<div class="write_security_answers">
			<label class="main_label">' . translateProjectText($EVC, "Please write the correspondent security answers") . ':</label>';
		translateProjectFormSettings($EVC, $form_settings);
		
		$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
	
		$html .= \HtmlFormHandler::createHtmlForm($form_settings);
		$html .= '</div>';
		
		return $html;
	}
	
	private function recoverUsername($settings, $step, $username_attribute_label) {
		$EVC = $this->getEVC();
		
		$field = "email";
		$label = "Email";
		if ($step == "recover_password_through_email" || $step == "recover_password_through_security_questions") {
			$field = "username";
			$label = $username_attribute_label;
		}
		
		//Preparing POST
		if ($_POST) {
			$username_or_email = $_POST[$field];
			
			if (empty($username_or_email)) {
				$message = str_replace("#label#", translateProjectText($EVC, $label), translateProjectText($EVC, "#label# cannot be undefined!"));
			}
			else {
				$brokers = $EVC->getPresentationLayer()->getBrokers();
		
				$users = \UserUtil::getUsersByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array($field => $username_or_email), null, null, true);
				$user_data = $users[0];
				
				if (!$user_data["user_id"]) {
					$message = translateProjectText($EVC, "Error: There is no user with this #label#. Please try again...");
					$message = str_replace("#label#", translateProjectText($EVC, $label), $message);
				}
				else {
					if ($step == "recover_username_through_email" || $step == "recover_password_through_email") {
						$email = $user_data["email"];
						if ($step == "recover_password_through_email") {
							$email = strpos($user_data["username"], "@") !== false ? $user_data["username"] : $user_data["email"];//gives precedence to the email in the username if applies...
						}
						
						if (strpos($email, "@") === false) {
							$message = translateProjectText($EVC, "Error: The system could not continue because this user does not have a valid email!");
						}
					}
					
					if (!$message) {
						if ($step == "recover_username_through_email") {
							$email_message = translateProjectText($EVC, "Your username is") . ": " . $user_data["username"];
							
							$Email = new \SmtpEmail($settings["smtp_host"], $settings["smtp_port"], $settings["smtp_user"], $settings["smtp_pass"], $settings["smtp_secure"]);
            						$status = $Email->send($settings["admin_email"], null, $settings["admin_email"], null, $email, null, translateProjectText($EVC, "Username recovery..."), $email_message);
							
							if ($status) {
								return $this->showSuccessfullMessage($settings, $step, $user_data);
							}
							else {
								$message = translateProjectText($EVC, "There was an error trying to send you an email with your username. Please try again later...");
							}
						}
						else if ($step == "recover_username_through_email_and_security_questions")
							return $this->showSecurityQuestions($settings, $step, null, $user_data["email"]);
						else if ($step == "recover_password_through_email") {
							$user_data["password"] = uniqid();
							$user_data["do_not_encrypt_password"] = $settings["do_not_encrypt_password"];
							
							$status = \UserUtil::updateUserPassword($brokers, $user_data);
							
							if ($status) {
								$email_message = translateProjectText($EVC, "Your new password is") . ": " . $user_data["password"];
								
								$Email = new \SmtpEmail($settings["smtp_host"], $settings["smtp_port"], $settings["smtp_user"], $settings["smtp_pass"], $settings["smtp_secure"]);
            							$status = $Email->send($settings["admin_email"], null, $settings["admin_email"], null, $email, null, translateProjectText($EVC, "Password recovery..."), $email_message);
							}
							
							if ($status) {
								return $this->showSuccessfullMessage($settings, $step, $user_data);
							}
							else {
								$message = translateProjectText($EVC, "There was an error trying to send you an email with the new password. Please try again later...");
							}
						}
						else if ($step == "recover_password_through_security_questions") {
							return $this->showSecurityQuestions($settings, $step, $user_data["username"], null);
						}
					
					}
				}
			}
		}
		
		//Preparing HTML
		$html = '';
		
		if ($message)
			$html .= \CommonModuleUI::getModuleMessagesHtml($EVC, $message, null);
		
		$form_settings = array(
			"with_form" => 1,
			"form_id" => "",
			"form_method" => "post",
			"form_class" => "",
			"form_on_submit" => "",
			"form_action" => "?step=$step",
			"form_containers" => array(
				0 => array(
					"container" => array(
						"class" => "form_fields",
						"previous_html" => "",
						"next_html" => "",
						"elements" => array(
							0 => array(
								"field" => array(
									"class" => "form_field",
									"label" => array(
										"value" => "$label: ",
									),
									"input" => array(
										"type" => "text",
										"name" => "$field",
										"extra_attributes" => array(
											0 => array(
												"name" => "allowNull",
												"value" => "false"
											),
											1 => array(
												"name" => "validationMessage",
												"value" => str_replace("#label#", translateProjectText($EVC, $label), translateProjectText($EVC, "#label# cannot be undefined!"))
											)
										),
									)
								)
							),
						)
					)
				),
				1 => array(
					"container" => array(
						"class" => "buttons",
						"elements" => array(
							0 => array(
								"field" => array(
									"class" => "submit_button",
									"input" => array(
										"type" => "submit",
										"name" => "continue",
										"value" => "Continue",
									)
								)
							),
						)
					)
				),
			)
		);
		
		$html .= '<div class="write_email">
			<label class="main_label">' . str_replace("#label#", translateProjectText($EVC, $label), translateProjectText($EVC, "Please write your #label#")) . ':</label>';
			
		translateProjectFormSettings($EVC, $form_settings);
	
		$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
	
		$html .= \HtmlFormHandler::createHtmlForm($form_settings);
		$html .= '</div>';
		
		return $html;
	}
}
?>
