<?php
include_once dirname(__DIR__) . "/common/CommonSettings.php";

class WorkerPoolSettings extends CommonSettings {
	const RUNNING_PROCESSES_MAXIMUM_NUMBER = 10;//only works if not windows os.
	const PROCESS_MAXIMUM_EXECUTION_TIME = 3600;//in secs. 3600secs = 60secs * 60min = 1h. Only works if not windows os.
	const PROCESS_MAXIMUM_EXPIRATION_EXECUTION_TIME = 604800;//in secs. 604800 = 3600secs * 24h * 7days = 1 week. Note that this variable only make sense if the workers get executed in less time than this value, otherwise the process will be killed during the execution of a worker. Additionally this value must be bigger than $WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME. (Only works if not windows os)
	
	const WORKERS_MAXIMUM_NUMBER_PER_PROCESS = 100;
	const WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER = 5;
	const WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME = 604800;//in secs. 604800 = 3600secs * 24h * 7days = 1 week.
	
	const WORKER_AVAILABLE_STATUSES = array(0 => "pending", 1 => "executing", 2 => "success", 3 => "failed");
}
?>
