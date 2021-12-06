<?php
namespace Module\Workerpool;

if (!class_exists("WorkerDBDAOServiceUtil")) {
	class WorkerDBDAOServiceUtil {
		
		public static function update_failed_and_to_parse_workers($data = array()) {
			return "update mwp_worker set status=3, modified_date='" . $data["modified_date"] . "' where status=0 and failed_attempts>=" . $data["maximum_failed_attempts"];
		}
	
		public static function update_failed_and_expired_workers($data = array()) {
			return "update mwp_worker set status=3, failed_attempts=failed_attempts+1, modified_date='" . $data["modified_date"] . "' where status=1 and begin_time<" . $data["expiration_time"] . " and failed_attempts+1>=" . $data["maximum_failed_attempts"];
		}
	
		public static function reset_expired_workers($data = array()) {
			return "update mwp_worker set status=0, thread_id='', begin_time=0, end_time=0, failed_attempts=failed_attempts+1, modified_date='" . $data["modified_date"] . "' where status=1 and begin_time<" . $data["begin_time"];
		}
	
		public static function update_thread_worker($data = array()) {
			return "update mwp_worker set status=1, thread_id='" . $data["thread_id"] . "', begin_time=" . $data["begin_time"] . ", modified_date='" . $data["modified_date"] . "' where worker_id=" . $data["worker_id"] . " and status=0 and thread_id=''";
		}
	
		public static function update_closed_worker($data = array()) {
			return "update mwp_worker set status=2, end_time=" . $data["end_time"] . ", modified_date='" . $data["modified_date"] . "' where worker_id=" . $data["worker_id"];
		}
	
		public static function update_failed_worker($data = array()) {
			return "update mwp_worker set status=3, failed_attempts=failed_attempts+1, modified_date='" . $data["modified_date"] . "' where worker_id=" . $data["worker_id"];
		}
	
		public static function reset_failed_worker($data = array()) {
			return "update mwp_worker set status=0, thread_id='', begin_time=0, end_time=0, failed_attempts=failed_attempts+1, modified_date='" . $data["modified_date"] . "' where worker_id=" . $data["worker_id"];
		}
	
	}
}
?>