<?PHP

class rep_process {
  private $search_terms = '';
  
  function __construct() {
		
	$this->search_terms = explode("\n",$_POST['keywords']);
	
	// check for exact search settings and if enabled encapsulate search terms in double quotes
	$new_terms = array();
	if($_POST['exact_srch'] == 1) {
		foreach($this->search_terms as $cur_term) {
			if(!empty($cur_term)) $new_terms[] = '"'.urlencode(trim($cur_term)).'"';
		}
		$this->search_terms = $new_terms;
	} else {
		foreach($this->search_terms as $cur_term) {
			if(!empty($cur_term)) $new_terms[] = urlencode(trim($cur_term));
		}
		$this->search_terms = $new_terms;
	}
	
	$this->proc_rpt();
		
  }
  
  function proc_rpt() {
	global $goog_scrap, $data_grab, $get_pagerank;
	
	$headers_arr = array(
//						 '#',
						 'Rank',
						 'Link',
						 'PR',
						 'Backlinks',
						 'All-in / Partial',
						 );
	
  
	$report_output = '<table align="center" border="0" cellspacing="0" cellpadding="4" class="tbl_results"><thead><tr>';
  
	foreach($headers_arr as $id => $hdr_val) {
	  $report_output .= '<th class="hdr_ste_'.$id.'">'.$hdr_val.'</th>';
	}	
  
	$report_output .= '</tr></thead><tbody>';
	
	if(!empty($_SESSION['domain'])) {
		
	  $row_array = array();
	
	  // current row value
	  $row_array[] = 1;
	
	  // current search term
	  $row_array[] = $_SESSION['domain'];
	  $row_array[] = $get_pagerank->get_assigned_pagerank($_SESSION['domain']);
	  
	  // print current search term	  	  	  
	  $report_output .= '<tr><td>'.implode('</td><td>',$row_array).'</td></tr>';
		
	}
	
	$cur_ret = 0;
	foreach($this->search_terms as $cur_srch_val) {

// print current search term
	  $report_output .= $goog_scrap->proc_domains($cur_srch_val);
	
	}
	
	$report_output .= '</tbody></table>';
	
	if($_POST['verbose_opt'] == 1) $report_output .= $data_grab->vb_output;
	
	echo $report_output;
	echo '<center><a href="javascript:void(0);" onclick="printpop();">Click to Print Results</a></center>';
	
	if(!empty($_POST['email_address'])) {
		
	  $email_data = array();
	  $email_data['content'] = '<link rel="stylesheet" type="text/css" href="http://www.cheaplocaldeals.com:1144/css/def.css" media="screen" />'.$report_output;
	  $email_data['subject'] = 'CM Goog Keyword Search Tool Results '.date("m-d-Y");
	  $email_data['to_addresses'] = $_POST['email_address'];
	  
	  send_email($email_data);
	}
  }
	  
}

?>