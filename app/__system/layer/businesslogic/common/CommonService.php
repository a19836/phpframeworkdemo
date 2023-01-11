<?php
namespace __system\businesslogic;

if (!class_exists("\__system\businesslogic\CommonService")) {
	include_once get_lib("org.phpframework.PHPFrameWorkHandler");
	
	/**
	 * @hidden
	 */
	abstract class CommonService {
		private $BusinessLogicLayer;
		private $UserCacheHandler;
		private $PHPFrameWorkHandler;
		
		private $options;
		
		private static $db_lib_included;
	
		/**
		 * @hidden
		 */
		public function __construct() {
			$this->PHPFrameWorkHandler = new \PHPFrameWorkHandler();
		}
	
		/**
		 * @hidden
		 */
		public function setPHPFrameWorkObjName($phpframework_obj_name) {$this->PHPFrameWorkHandler->setPHPFrameWorkObjName($phpframework_obj_name);}
	
		/**
		 * @hidden
		 */
		public function setUserCacheHandler($UserCacheHandler) {$this->UserCacheHandler = $UserCacheHandler;}
		/**
		 * @hidden
		 */
		public function getUserCacheHandler() {return $this->UserCacheHandler;}
	
		/**
		 * @hidden
		 */
		public function setBusinessLogicLayer($BusinessLogicLayer) {$this->BusinessLogicLayer = $BusinessLogicLayer;}
		/**
		 * @hidden
		 */
		public function getBusinessLogicLayer() {return $this->BusinessLogicLayer;}
	
		/**
		 * @hidden
		 */
		public function getBroker($broker_name_or_options = false) {
			$broker_name = is_array($broker_name_or_options) ? $broker_name_or_options["dal_broker"] : $broker_name_or_options;
		
			return $this->getBusinessLogicLayer()->getBroker($broker_name);
		}
	
		/**
		 * @hidden
		 * set the options of to the current called service
		 */
		public function setOptions($options) {$this->options = $options;}
		/**
		 * @hidden
		 * get the options of to the current called service
		 */
		public function getOptions() {return $this->options;}
		
		/**
		 * @hidden
		 * merge the argument $options with the correspondent options of the current called service.
		 */
		public function mergeOptionsWithBusinessLogicLayer(&$options) {
			$cso = $this->getOptions();
			
			if ($cso) { // Merge db_driver and no_cache options
				if ($options)
					$options = array_merge(is_array($options) ? $options : array($options), $cso);
				else
					$options = $cso;
			}
		}
		
		/**
		 * @hidden
		 */
		public static function getReservedSQLKeywords() {
			//return array("START_PAGINATION", "END_PAGINATION", "SIMPLE_PAGINATION", "START_SORTING", "END_SORTING", "SIMPLE_SORTING", "SEARCHING_CONDITION");
			return array("searching_condition");
		}
	
		/**
		 * @hidden
		 */
		protected static function prepareInputData(&$data) {
			self::prepareSearch($data);
			
			if ($data["conditions"]) {
				if (!self::$db_lib_included)
					include_once get_lib("org.phpframework.db.DB"); //leave this here, otherwise it could be over-loading for every request to include without need it...
				
				self::$db_lib_included = true;
				
				$cond = \DB::getSQLConditions($data["conditions"], $data["conditions_join"]);
				if ($cond)
					$data["searching_condition"] = ($data["searching_condition"] ? $data["searching_condition"] : "") . " and (" . $cond . ")";
			}
		}
	
		/**
		 * @hidden
		 */
		protected static function prepareSearch(&$data) {
			$condition = "";
		
			if($data["searching"] && is_array($data["searching"]) && $data["searching"]["fields"]) {
				if (!self::$db_lib_included)
					include_once get_lib("org.phpframework.db.DB"); //leave this here, otherwise it could be over-loading for every request to include without need it...
				
				self::$db_lib_included = true;
				
				$search_fields = $data["searching"]["fields"];
				$search_values = $data["searching"]["values"];
				$search_types = $data["searching"]["types"];
				
				$t = count($search_fields);
				for ($i = 0; $i < $t; $i++) {
					$field = $search_fields[$i];
					$value = strtolower($search_values[$i]);
					$type = $search_types[$i];
				
					$condition .= ($condition ? " and " : "") . "lower(" . \SQLQueryHandler::getParsedSqlColumnName($field) . ")";
				
					$with_quote = !is_numeric($value) ? "'" : "";
					$value = addcslashes($value, "\\'");
				
					switch ($type) {
						case "contains": 
							$condition .= " like '%$value%'";
							break;
					
						case "starts": 
							$condition .= " like '$value%'";
							break;
					
						case "ends": 
							$condition .= " like '%$value'";
							break;
					
						case "equal": 
							$condition .= "={$with_quote}$value{$with_quote}";
							break;
					
						case "bigger": 
							$condition .= ">{$with_quote}$value{$with_quote}";
							break;
					
						case "smaller": 
							$condition .= "<{$with_quote}$value{$with_quote}";
							break;
					
						default:
							 $condition .= "={$with_quote}$value{$with_quote}";
					}
				}
			}
		
			$data["searching_condition"] = $condition ? " and " . $condition : "";
		}
	}
}
?>
