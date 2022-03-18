<?php
class PaginationHandler {
	
	public static $get_var_name = "page";
	
	/*
	$pagination_settings = array(
		"total_rows" => 100,
		"rows_per_page" => 20,
		"page_number" => $_GET["page"],
		"max_num_of_shown_pages" => 10,
	);
	*/
	public static function getPaginationHTML($data) {
		$html = "";
		
		//$data["total_rows"] = 500;//only for testing
		$pagination_data = self::calculatePages($data["total_rows"], $data["rows_per_page"], $data["page_number"], $data["max_num_of_shown_pages"]);
		
		$cnt = $pagination_data["pages"] ? count($pagination_data["pages"]) : 0;
		
		if ($cnt > 1) {
			$html .= '<div id="pagination">';
			
			//print_r($data);
			//print_r($pagination_data);
			
			$html .= '<span class="first"><a href="' . self::getPageUrl(1) . '">first</a></span>';
			
			if ($pagination_data["previous"] > 0 && $pagination_data["previous"] != $pagination_data["current"]) {
				$html .= '<span><a href="' . self::getPageUrl($pagination_data["previous"]) . '">previous</a></span>';
			}
			
			for ($i = 0; $i < $cnt; $i++) {
				$html .= '<span class="'. ($pagination_data["pages"][$i] == $pagination_data["current"] ? "current" : "") .'"><a href="' . self::getPageUrl($pagination_data["pages"][$i]) . '">' . $pagination_data["pages"][$i] . '</a></span>';
			}
			
			if ($pagination_data["next"] > 0 && $pagination_data["next"] != $pagination_data["current"]) {
				$html .= '<span><a href="' . self::getPageUrl($pagination_data["next"]) . '">next</a></span>';
			}
			
			$html .= '<span class="last"><a href="' . self::getPageUrl($pagination_data["last"]) . '">last</a></span>';
			
			$html .= '</div>';
		}
		
		return $html;
	}
	
	/*
	 * $total_rows: total number of records/lines
	 * $rows_per_page: how many records/lines do you want to show per page
	 * $page_num: current page number
	 * $max_num_of_shown_pages: how many boxes per pages
	 */
	public static function calculatePages($total_rows, $rows_per_page, $page_num, $max_num_of_shown_pages) {
		$arr = array();
		
		if ($total_rows <= 0) {
			$arr['sql_start'] = 0;
			$arr['sql_limit'] = 0;
			$arr['sql'] = 'LIMIT 0,0';
			$arr['current'] = 0;
			$arr['previous'] = 0;
			$arr['next'] = 0;
			$arr['last'] = 0;
			$arr['info'] = 'No Pages';
			$arr['pages'] = array();
		}
		else {
			// calculate last page
			$last_page = $rows_per_page > 0 ? ceil($total_rows / $rows_per_page) : 0;
		
			// make sure we are within limits
			$page_num = (int) $page_num;
		
			if ($last_page < 1) {
				$last_page = 1;
			}
		
			if ($page_num < 1) {
			   $page_num = 1;
			} 
			else if ($page_num > $last_page) {
			   $page_num = $last_page;
			}
		
			$upto = ($page_num - 1) * $rows_per_page;
		
			$arr['sql_start'] = $upto;
			$arr['sql_limit'] = $rows_per_page;
			$arr['sql'] = 'LIMIT '.$upto.',' .$rows_per_page;
			$arr['current'] = $page_num;
		
			if ($page_num == 1)
				$arr['previous'] = $page_num;
			else
				$arr['previous'] = $page_num - 1;
		
			if ($page_num == $last_page)
				$arr['next'] = $last_page;
			else if ($last_page < 1)
				$arr['next'] = $page_num;
			else
				$arr['next'] = $page_num + 1;
		
			$arr['last'] = $last_page;
			$arr['info'] = 'Page ('.$page_num.' of '.$last_page.')';
			$arr['pages'] = self::getSurroundingPages($page_num, $last_page, $arr['next'], $max_num_of_shown_pages);
		}
		
		return $arr;
	}
	
	public static function getSurroundingPages($page_num, $last_page, $next, $max_num_of_shown_pages) {
		$arr = array();
		
		// at first
		if ($page_num == 1) {
			// case of 1 page only
			if ($next == $page_num) 
				return array(1);
			
			for ($i = 0; $i < $max_num_of_shown_pages; $i++) {
				if ($i == $last_page) 
					break;
			
				array_push($arr, $i + 1);
			}
			
			return $arr;
		}
		
		// at last
		if ($page_num == $last_page) {
			$start = $last_page - $max_num_of_shown_pages;
			
			if ($start < 1) 
				$start = 0;
			
			for ($i = $start; $i < $last_page; $i++) {
				array_push($arr, $i + 1);
			}
			
			return $arr;
		}
		
		// at middle
		$start = $page_num - $max_num_of_shown_pages;
		
		if ($start < 1) 
			$start = 0;
		
		for ($i = $start; $i < $page_num; $i++) {
			array_push($arr, $i + 1);
		}
		
		for ($i = ($page_num + 1); $i < ($page_num + $max_num_of_shown_pages); $i++) {
			if ($i == ($last_page + 1)) 
				break;
			
			array_push($arr, $i);
		}
		
		return $arr;
	}
	
	public static function getPageUrl($page_num) {
		//$url = urldecode($_SERVER["HTTP_REFERER"]);
	
		//if (empty($url)) {
			$url = "?" . $_SERVER["QUERY_STRING"];
		//}
		
		if (strpos($url, self::$get_var_name . "=") !== false) {
			return preg_replace("/(&|\?)(" . self::$get_var_name . ")=([0-9])*/", "$1$2=$page_num", $url);
		}
		
		$query_string = self::$get_var_name . "=" . $page_num;
		
		if (strpos($url, "?") !== false) {
			return $url . "&" . $query_string;
		}
	
		return $url . "?" . $query_string;
	}
}
?>
