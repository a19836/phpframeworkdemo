<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

// Do not include the WorkerPoolWork or WorkerPoolUtil files here, because they are already included in the WorkerPoolHandler.php
include_once get_lib("org.phpframework.util.web.SmtpEmail");
include_once get_lib("org.phpframework.util.text.TextValidator");

class SendEmailWorkerPoolWork extends WorkerPoolWork {
	
	protected function run() {
		if ($this->args) {
			$from = isset($this->args["from"]) ? $this->args["from"] : null;
			$to = isset($this->args["to"]) ? $this->args["to"] : null;
			$subject = isset($this->args["subject"]) ? $this->args["subject"] : null;
			$content = isset($this->args["content"]) ? $this->args["content"] : null;
			$smtp_host = isset($this->args["smtp_host"]) ? $this->args["smtp_host"] : null;
			$smtp_port = isset($this->args["smtp_port"]) ? $this->args["smtp_port"] : null;
			$smtp_user = isset($this->args["smtp_user"]) ? $this->args["smtp_user"] : null;
			$smtp_pass = isset($this->args["smtp_pass"]) ? $this->args["smtp_pass"] : null;
			$smtp_secure = isset($this->args["smtp_secure"]) ? $this->args["smtp_secure"] : null;
			
			$to_emails = explode(",", $to);
			
			$Email = new SmtpEmail($smtp_host, $smtp_port, $smtp_user, $smtp_pass, $smtp_secure);
            		
			$failed_emails = array();
			
			foreach ($to_emails as $to_email)
				if(TextValidator::isEmail(trim($to_email))) {
					$to_email = trim($to_email);
					
					try {
						if ($Email->send($from, null, $from, null, $to_email, null, $subject, $content))
							$this->log("Email sent to $to_email width subject: '$subject'");
						else {
							
							$this->log("Email Error: " . $Email->getErrorInfo(), "error");
							
							$failed_emails[] = $to_email;
						}
					}
					catch (Exception $e) {
						$failed_emails[] = $to_email;
					}
        			}
        		
        		if ($failed_emails) {
        			$args = $this->args;
        			$args["to"] = implode(",", $failed_emails);
        			
        			$worker = array(
        				"class" => isset($this->worker["class"]) ? $this->worker["class"] : null,
        				"args" => $args,
        				"description" => "Recreate worker based in worker id: " . (isset($this->worker["worker_id"]) ? $this->worker["worker_id"] : null),
        			);
        			return WorkerPoolUtil::insertWorker($this->EVC->getPresentationLayer()->getBrokers(), $worker);
        		}
        		
        		return empty($failed_emails);
		}
	}
}
?>
