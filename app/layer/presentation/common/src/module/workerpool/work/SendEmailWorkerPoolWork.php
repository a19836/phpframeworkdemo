<?php
// Do not include the WorkerPoolWork or WorkerPoolUtil files here, because they are already included in the WorkerPoolHandler.php
include_once get_lib("org.phpframework.util.web.SmtpEmail");
include_once get_lib("org.phpframework.util.text.TextValidator");

class SendEmailWorkerPoolWork extends WorkerPoolWork {
	
	protected function run() {
		if ($this->args) {
			$from = $this->args["from"];
			$to = $this->args["to"];
			$subject = $this->args["subject"];
			$content = $this->args["content"];
			$smtp_host = $this->args["smtp_host"];
			$smtp_port = $this->args["smtp_port"];
			$smtp_user = $this->args["smtp_user"];
			$smtp_pass = $this->args["smtp_pass"];
			$smtp_secure = $this->args["smtp_secure"];
			
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
        				"class" => $this->worker["class"],
        				"args" => $args,
        				"description" => "Recreate worker based in worker id: " . $this->worker["worker_id"],
        			);
        			return WorkerPoolUtil::insertWorker($this->EVC->getPresentationLayer()->getBrokers(), $worker);
        		}
        		
        		return empty($failed_emails);
		}
	}
}
?>
