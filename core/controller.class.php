<?php

class Controller{

	public $data = [];
	
	function __construct(){	

	}

	public function view($view, $data=[], $header=true){		
		$data['activelink'] = $this->url_segments(1);
		$data['activelink_seg2'] = $this->url_segments(2);
		/* For session data	*/
		$sessData = $this->isLoggedIn();
		$data['active_session'] = $sessData;
		$data['active_role'] = $sessData['role'];
		
		$viewArr = explode('/', $view);
		if(count($viewArr)>1){
			$viewName = $viewArr[0].'/'.$viewArr[1];
		}else{
			$viewName = $viewArr[0];
		}
		/** $header=true for including header from template */
		if($header==true){
			include('./application/views/template/header.php');
		}	
		
		if(!isset($viewName)){
			include('./application/views/default.php');
		}else{
			include('./application/views/'.$viewName.'.php');
		}
		/** $header=true for including footer from template */
		if($header==true){
			include('./application/views/template/footer.php');
		}

		return true;
	}

	public function pr($data, $exit=false){
		if($exit){
			echo '<pre>';
			print_r($data);
			echo '</pre>';
			exit;
		}else{
			echo '<pre>';
			print_r($data);
			echo '</pre>';
		}
	}

	public function pagination($link, $total, $limit, $page){
	    $url = APPS_URL.$link;
	    $pages = ceil($total / $limit);

	    $offset = ($page - 1)  * $limit;

	    if($page==1 || $page<1){
	    	$prevlink = '<li class="page-item disabled">
						  <span class="page-link">Previous</span>
						</li>';
	    }else{
	    	$prevlink = '<li class="page-item">
						  <a class="page-link" href="'.$url.'/'.($page - 1).'" tabindex="-1">Previous</a>
						</li>';
		}

	    if($pages==$page){
	    	$nextlink = '<li class="page-item disabled">
			  <span class="page-link">Next</span>
			</li>';
	    }else{	    	
	    	$nextlink = '<li class="page-item">
			  <a class="page-link" href="'.$url.'/'.($page + 1).'">Next</a>
			</li>';
		}

	    $range = 5;
	    $pagination = '<ul class="pagination">';
	    $pagination .= $prevlink;
	    $startPagi = $page>$range ? $page - $range : $page;

	    for($i=($page - $range); $i<=(($page + $range) + 1); $i++){
	    	if (($i > 0) && ($i <= $pages)) {
	    		if($page==$i){
	    			$pagination .= '<li class="page-item disabled"><span class="page-link font-weight-bold">'.$i.'</span></li>';
	    		}else{
	    			$pagination .= '<li class="page-item"><a class="page-link" href="'.$url.'/'.$i.'">'.$i.'</a></li>';
	    		}
	    	}
	    }

	    $pagination .= $nextlink;
	    $pagination .= '</ul>';

	    return $pagination;

	}

	public function url_segments($seg){
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$splitUrl = explode('/',str_replace(APPS_URL, '', $actual_link));
		$res = '';
		if(count($splitUrl)>=$seg){
			if($splitUrl[0]=='index.php'){
				$res = $splitUrl[$seg];	
			}else{
				$res = $splitUrl[$seg - 1];	
			}
		}
		return $res;
	}

	public function queryString($seg){
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$queryString = explode('/?',str_replace(APPS_URL, '', $actual_link));
		$res = [];		
		
		if(count($queryString)>=$seg && $seg>0){
			if(!empty($queryString[$seg])){
				$splitVal = explode('=', $queryString[$seg]);
				$res['key'] = $splitVal[0];	
				$res['val'] = $splitVal[1];	
			}
		}
		return $res;
	}

	public function queryStringMulti($seg=0){
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$queryString = explode('/?',str_replace(APPS_URL, '', $actual_link));
		$res = [];	
		if(array_key_exists(1, $queryString)){
			$queryStringSplit = explode('&',str_replace(APPS_URL, '', $queryString[1]));		
			if(array_key_exists($seg, $queryStringSplit)){
				$splitVal = explode('=', $queryStringSplit[$seg]);	
				$res[$splitVal[0]] = $splitVal[1];	
			}
		}
		return $res;
	}
	
	public function isLoggedIn($sessionData='userdata'){
		if(!empty($_SESSION[$sessionData])){
			return $_SESSION[$sessionData];
		}else{
			return false;
		}
	}
}


?>