<?PHP

class goog_scrap {
 private $illegal_sites = array();
 private $goog_dom = 'http://74.125.67.100/search?';
 private $per_page = 100;
 private $listing = 10;
 public $vb_output = '';
 private $html_grab = '';
 private $selected_domain = '';
 
  function __construct() {
	
	$this->illegal_sites = array(
						'booksearch.google.com',
						'books.google.com',
						'news.google.com',
						'blogsearch.google.com',
						'maps.google.com',
						'images.google.com',
						);
	
	$this->selected_domain = $_POST['domain'];
	
  }
  
  function get_domains_list($cur_srch_val) {
	global $data_grab;
	
	// create HTML DOM
    $Url=$this->goog_dom.'q='.$cur_srch_val.'&num='.$this->per_page;
	$data_grab->attemp = 1;
	$data_grab->get_page_data($Url);
	$this->html_grab = $data_grab->html_grab;
    $html = str_get_html($this->html_grab);

	// get news block
	foreach($html->find('h3.r') as $article) {
		// get title
		$domain = $this->getdomain(trim($article->find('a', 0)->href));
		if(!in_array($domain,$this->illegal_sites)) {
		  $item['title'] = trim($article->find('a', 0)->plaintext);
		  $item['link'] = trim($article->find('a', 0)->href);
  
		  $ret[] = $item;
		}
	}
	
	// clean up memory
	$html->clear();
	unset($html);

  return $ret;
  }
  
  public function proc_domains($cur_srch_val) {
	global $get_pagerank;
	  
	$ret = $this->get_domains_list($cur_srch_val);
  
	// check for domain return
	$anchor_num = 0;
	$anchor_arr = array();
	foreach($ret as $v) {
	  $anchor_num++;
	  
	  $anchor_arr[$anchor_num] = $anchor_num;
	  $anchor_arr[$anchor_num] = $get_pagerank->get_assigned_pagerank($v['link']);
	  $anchor_arr[$anchor_num] = $this->get_backlinks($v['link']);
	  $anchor_arr[$anchor_num] = $this->allinanchor($v['link']).' / '.$this->inanchor($v['link']);
	
	}
	
	$report_output .= '<tr><td>'.implode('</td><td>',$anchor_arr).'</td></tr>';

  return $report_output;
  }
  
  // search backlinks
  public function get_backlinks($cur_srch_val) {
	global $data_grab;
	
//	sleep(5);
	
    $Url=$this->goog_dom.'q=link:'.$cur_srch_val.'&num='.$this->listing;
	$data_grab->attemp = 1;
	$data_grab->get_page_data($Url);
	$this->html_grab = $data_grab->html_grab;
	$results = $this->results_cnt();
//echo $Url.' ';
  return $results;
  }
  
  // search allinanchor
  public function allinanchor($cur_srch_val) {
	global $data_grab;
		
	//	sleep(5);

	// create HTML DOM
    $Url=$this->goog_dom.'q=allinanchor:'.$cur_srch_val.'&num='.$this->listing;
	$data_grab->attemp = 1;
	$data_grab->get_page_data($Url);
	$this->html_grab = $data_grab->html_grab;
    $html = str_get_html($this->html_grab);

    // get news block
    foreach($html->find('h3.r') as $article) {
        // get title
 		$domain = $this->getdomain(trim($article->find('a', 0)->href));
		if(!in_array($domain,$this->illegal_sites)) {
	       $item['link'] = trim($article->find('a', 0)->href);

	        $ret[] = $item;
		}
    }
    
    // clean up memory
    $html->clear();
    unset($html);

  return $ret;
  }
  
  // search inanchor
  public function inanchor($cur_srch_val) {
	global $data_grab;
		
//	sleep(5);
	
    $Url=$this->goog_dom.'q=inanchor:'.$cur_srch_val.'&num='.$this->listing;
	$data_grab->attemp = 1;
	$data_grab->get_page_data($Url);
	$this->html_grab = $data_grab->html_grab;
	$results = $this->results_cnt();

  return $results;
  }
  
  private function results_cnt() {
	
	if(strpos($this->html_grab,' of about ') > 0 || strpos($this->html_grab,' of ') > 0) {
		
	  if (strpos($this->html_grab,' of about ') > 0) $min = 0; else $min = 1;
	
	  $html = str_get_html($this->html_grab);
	  
	  $results = trim($html->find('p#resultStats', 0)->plaintext);
	
	  $results = explode(' ',$results);
	  $results = (int)str_replace(',', '', ($min == 0 ? $results[6] : $results[5]));
	  
	  unset($html);
	} else {
	  $results = 0;
	}

  return $results;
  }
  
  public function getdomain($url) {
	 $url = str_replace("http://", "", str_replace("https://", "", $url));
	 $url = substr($url, 0, strpos($url, "/"));
	 return $url;
  }
	
}

?>