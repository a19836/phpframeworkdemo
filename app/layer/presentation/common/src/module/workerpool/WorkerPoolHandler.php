<?php
include_once get_lib("org.phpframework.encryption.CryptoKeyHandler");
include_once $EVC->getModulePath("workerpool/WorkerPoolUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("workerpool/work/WorkerPoolWork", $EVC->getCommonProjectName());

class WorkerPoolHandler {
	private $EVC;
	private $script_name; 
	
	public function __construct($EVC, $script_name = "workerpool/run_worker_pool_script.php") {
		$this->EVC = $EVC;
		$this->script_name = $script_name;
	}
	
	public function start() {
		$this->run();
	}
	
	protected function run() {
		global $GlobalExceptionLogHandler;
		
		$start_time = microtime(true);
		$status = true;
		$continue = true;
		
		$is_windows_os = strtoupper(substr(PHP_OS, 0, 3)) === "WIN";//Detect if OS is Windows
		
		$RUNNING_PROCESSES_MAXIMUM_NUMBER = WorkerPoolUtil::getConstantVariable("RUNNING_PROCESSES_MAXIMUM_NUMBER");
		$PROCESS_MAXIMUM_EXECUTION_TIME = WorkerPoolUtil::getConstantVariable("PROCESS_MAXIMUM_EXECUTION_TIME");
		$PROCESS_MAXIMUM_EXPIRATION_EXECUTION_TIME = WorkerPoolUtil::getConstantVariable("PROCESS_MAXIMUM_EXPIRATION_EXECUTION_TIME");
		$WORKERS_MAXIMUM_NUMBER_PER_PROCESS = WorkerPoolUtil::getConstantVariable("WORKERS_MAXIMUM_NUMBER_PER_PROCESS");
		$WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER = WorkerPoolUtil::getConstantVariable("WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER");
		$WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME = WorkerPoolUtil::getConstantVariable("WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME");
		
		$brokers = $this->EVC->getPresentationLayer()->getBrokers();
		
		/*
		 * 1st Step: Avoid multiple processes running at the same nano second to have weird behaviours...
		 */
		
		//Sleep randomly in case exists multiple processes started at the same exact moment. This will assure that the "current number of running process" bellow is more accurated.
		$micro_secs = rand(0, 500000);//maximum half sec (1 sec = 1.000.000 micro secs)
		usleep($micro_secs);
		
		/*
		 * 2nd Step: Check running processes
		 */
		//TODO: find a way to do this but with native php function, instead of the exec function. Maybe I can use the php posix functions.
		if ($RUNNING_PROCESSES_MAXIMUM_NUMBER && !$is_windows_os && function_exists("exec")) { //maybe exec function was disabled in the php.ini for security reasons
			//Get the current number of running processes
			exec("pgrep '{$this->script_name}'", $pids, $return);
			$current_running_processes_total = $pids ? count($pids) : 0;
			
			if ($current_running_processes_total > $RUNNING_PROCESSES_MAXIMUM_NUMBER) {
				$continue = false;
				
				if ($PROCESS_MAXIMUM_EXPIRATION_EXECUTION_TIME) {
					//Check if there are expired processes with the execution time more than PROCESS_MAXIMUM_EXPIRATION_EXECUTION_TIME
					exec("ps -eo pid,lstart,cmd | grep '{$this->script_name}' | grep -v grep", $processes, $return);
					
					if ($return == 0 && $processes) {
						$ct = time();
						$exists_expired = false;
						
						foreach ($processes as $process) {
							$parts = explode(" ", trim($process));
							$pid = $parts[0];
							$week_day = $parts[1];
							$month = $parts[2];
							$day = $parts[3];
							$hour = $parts[4];
							$minute = $parts[5];
							$second = $parts[6];
							$year = $parts[7];
							
							$time = strtotime("$year-$month-$day $hour:$minute:$second");
							
							//kill expired processes
							if ($time + $PROCESS_MAXIMUM_EXPIRATION_EXECUTION_TIME < $ct) {
								exec("kill $pid");
								$exists_expired = true;
							}
						}
						
						//If exists any expired processes
						if ($exists_expired) {
							//Get again new current number of running processes
							exec("pgrep '{$this->script_name}'", $pids, $return);
							$current_running_processes_total = $pids ? count($pids) : 0;
							
							//Check if the running process are not bigger than the RUNNING_PROCESSES_MAXIMUM_NUMBER
							$continue = $current_running_processes_total < $RUNNING_PROCESSES_MAXIMUM_NUMBER;
						}
					}
				}
			}
		}
		
		if ($continue) {
			$thread_id = CryptoKeyHandler::getHexKey();
			debug_log("[WorkerPoolUtil::startWorkerPool][$thread_id] Starting thread.", "debug");
			
			/*
			 * 3th Step: Prepare DB Workers:
			 */
			
			if ($WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER)
				//Update all DB Workers that contains the status=0 and failed_attempts >= $WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER, with the new values: status=3 (failed status)
				WorkerPoolUtil::updateFailedAndToParseWorkers($brokers, $WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER);
			
			if ($WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME) {
				if ($WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER)
					//Update all DB Workers that contains the status==1 and begin_time < time() - $WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME and failed_attempts + 1 >= $WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER, with the new values: status=3, failed_attempts+=1
					WorkerPoolUtil::updateFailedAndExpiredWorkers($brokers, $WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER, $WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME);
				
				//Update all DB Workers that contains status==1 and begin_time < time() - $WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME, with the new values: status=0 (to parse status), thread_id='', begin_time=0, end_time=0, failed_attempts+=1;
				WorkerPoolUtil::resetExpiredWorkers($brokers, $WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME);
			}
			
			/*
			 * 4th Step: Parse DB Workers
			 */
			
			//Get workers with status==0 limit WORKERS_MAXIMUM_NUMBER_PER_PROCESS
			$options = $WORKERS_MAXIMUM_NUMBER_PER_PROCESS ? array("limit" => $WORKERS_MAXIMUM_NUMBER_PER_PROCESS) : false;
			$workers = WorkerPoolUtil::getWorkersByConditions($brokers, array("status" => 0), null, $options, true);
			
			$t = $workers ? count($workers) : 0;
			for ($i = 0; $i < $t && $continue; $i++) {
				$worker = $workers[$i];
				
				debug_log("[WorkerPoolUtil::startWorkerPool][$thread_id] Parseing worker: " . json_encode($worker), "info");
				
				/*
				 * 4.1th Step: Avoid 2 different processes (running or not in different servers) to execute the same worker
				 */
				
				//Update worker with status=1, thread_id=process_id, begin_time=time() but only if this worker still has the status==0 and thread_id is null and worker_id==$worker["worker_id"]. Maybe other process already change this and the status is not 0 or thread_id is not null anymore...
				$begin_time = time();
				$worker_status = WorkerPoolUtil::updateThreadWorker($brokers, $worker["worker_id"], $thread_id, $begin_time);
				//echo "worker_status:$worker_status\n";
				
				if ($worker_status) {
					//Avoid processes conflits, in case the DB architecture contains as master and slave servers and multiple processes are trying to access the same worker at the same nano second. This will give time to the DB slaves be updated... (I think step is not necessary, but just in case, is better leave it here.)
					$micro_secs = rand(0, 300000);//maximum 300 milli-sec (1 sec = 1.000.000 micro secs)
					usleep($micro_secs);
					
					//Check if this worker still belongs to this process, this is, query the DB and get this worker with the status==1 and thread_id==process_id and worker_id==$worker["worker_id"]. This will detect if another process already took this worker...
					$exists = WorkerPoolUtil::countWorkersByConditions($brokers, array("worker_id" => $worker["worker_id"], "status" => 1, "thread_id" => $thread_id), null, true);
					//echo "exists:$exists\n";
					
					if ($exists) {
						$worker["thread_id"] = $thread_id;
						$worker["begin_time"] = $begin_time;
						$worker_status = false;
						
						try {
							/*
							 * 4.2th Step: Execute Worker
							 */
							
							//echo "worker id:".$worker["worker_id"]."\n";
							debug_log("[WorkerPoolUtil::startWorkerPool][$thread_id] Executing worker id: " . $worker["worker_id"], "info");
							$worker_status = $this->executeWorker($worker);
						}
						catch(Exception $e) {
							$status = false;
							
							debug_log("[WorkerPoolUtil::startWorkerPool][$thread_id] Exception executing worker id: " . $worker["worker_id"], "info");
							$die_when_throw_exception = $GlobalExceptionLogHandler->getDieWhenThrowException();
							$GlobalExceptionLogHandler->log($e);
							$GlobalExceptionLogHandler->setDieWhenThrowException($die_when_throw_exception);
						}
						finally {
							/*
							 * 4.3th Step: Update DB Worker according with execution status
							 */
							$create_repeated_worker = false;
							
							if ($worker_status) {
								debug_log("[WorkerPoolUtil::startWorkerPool][$thread_id] Worker executed correctly with worker id: " . $worker["worker_id"], "info");
								
								//Update DB Worker with status=2 (closed status), end_time=time()
								if (!WorkerPoolUtil::updateClosedWorker($brokers, $worker["worker_id"], time()))
									$status = false;
								else 
									$create_repeated_worker = true;
							}
							else if ($WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER && $worker["failed_attempts"] + 1 >= $WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER) {
								//Update DB Worker with status=3 (failed status) 
								if (!WorkerPoolUtil::updateFailedWorker($brokers, $worker["worker_id"]))
									$status = false;
								else
									$create_repeated_worker = true;
							}
							else {
								//Update DB Worker by resetting his attributes, this is, with status=0, thread_id='', begin_time=0, end_time=0 and failed_attempts=failed_attempts+1
								if (!WorkerPoolUtil::resetFailedWorker($brokers, $worker["worker_id"]))
									$status = false;
							}
							
							/*
							 * 4.4th Step: Create a new worker if it is repeated
							 */
							if ($create_repeated_worker) {
								//TODO: check if worker should be repeated and if yes creates a new worker clone.
							}
						}
					}
				}
				
				//Check if the $PROCESS_MAXIMUM_EXECUTION_TIME already passed
				if ($PROCESS_MAXIMUM_EXECUTION_TIME && $start_time + $PROCESS_MAXIMUM_EXECUTION_TIME <= time()) {
					$continue = false;
					break;
				}
			}
		}
		
		$end_time = microtime(true);
		$time = $end_time - $start_time;
		debug_log("[WorkerPoolUtil::startWorkerPool][$thread_id] Ending thread (in $time seconds).", "debug");
		
		return $status;
	}
	
	//Call/Execute worker based in the $worker["class"] and $worker["args"]
	//class: /var/www/html/xxx/SendMessage.php or lib.util.SendMessage
	//If class contains / in the beggining must be absolute path, otherwise must have the import style (this is: lib.xxx.yy.Foo)
	private function executeWorker($worker) {
		$path = trim($worker["class"]);
		
		if (substr($path, 0, 1) != "/")
			$path = get_lib($path);
		
		//echo "path:$path\n";
		if (file_exists($path)) {
			include_once $path;
			$class = pathinfo($path, PATHINFO_FILENAME);
			//echo "class:$class\n";
			
			if (class_exists($class) && is_subclass_of($class, "WorkerPoolWork")) {
				$obj = new $class();
				//echo "obj:".get_class($obj)."\n";
				
				if ($obj) {
					debug_log("[WorkerPoolUtil::executeWorker][{$worker['thread_id']}] Executing work with path: $path", "info");
					
					$obj->setEVC($EVC);
					$obj->setArgs($worker["args"]);
					$obj->setWorker($worker);
					
					$status = $obj->start();
				}
			}
		}
		else
			throw new Exception("Work File Class does NOT exist. File Path: $path");
		
		return $status;
	}
}
?>
