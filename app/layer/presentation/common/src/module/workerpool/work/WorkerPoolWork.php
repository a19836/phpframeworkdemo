<?php
abstract class WorkerPoolWork {
	protected $EVC;
	protected $args;
	protected $worker;
	
	public function setEVC($EVC) {
		$this->EVC = $EVC;
	}
	
	public function setArgs($args) {
		$this->args = $args;
	}
	
	public function setWorker($worker) {
		$this->worker = $worker;
	}
	
	/*
	 * @return: should return the status of the worker/job that was executed.
	 */
	public function start() {
		return $this->run();
	}
	
	protected function log($message, $log_type = null) {
		$called_class = get_class($this);
		$called_method = debug_backtrace();
		$called_method = isset($called_method[1]['function']) ? $called_method[1]['function'] : null;
		
		debug_log("[WorkerPoolUtil::$called_class::$called_method][" . (isset($this->worker["thread_id"]) ? $this->worker["thread_id"] : null) . "][" . (isset($this->worker["worker_id"]) ? $this->worker["worker_id"] : null) . "] $message", $log_type);
	}
	
	abstract protected function run();
}
?>
