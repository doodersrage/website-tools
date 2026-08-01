<?PHP

class rep_process {
  private $search_terms = '';
  
  function __construct() {
		
	$this->search_terms = explode("\n",$_POST['search_text']);
	
	// check for exact search settings and if enabled encapsulate search terms in double quotes
	$new_terms = array();
	if($_POST['exact_srch'] == 1) {
		foreach($this->search_terms as $cur_term) {
			$new_terms[] = '"'.urlencode(trim($cur_term)).'"';
		}
		$this->search_terms = $new_terms;
	} else {
		foreach($this->search_terms as $cur_term) {
			$new_terms[] = urlencode(trim($cur_term));
		}
		$this->search_terms = $new_terms;
	}
	
	$this->proc_rpt();
		
  }
  
  function proc_rpt() {
	global $goog_scrap, $data_grab;
	
	$all_rate_arr = array(
						  10 => 99999999999999999999999999999999,
						  9 => 99999999,
						  8 => 49999999,
						  7 => 19999999,
						  6 => 699999,
						  5 => 299999,
						  4 => 99999,
						  3 => 49999,
						  2 => 9999,
						  1 => 499,
						  );
	
	$str_tp_tn_ste_arr = array(
							   1 => 999999999999999999999999999999999,
							   2 => 899,
							   3 => 799,
							   4 => 699,
							   5 => 599,
							   6 => 499,
							   7 => 399,
							   8 => 199,
							   9 => 119,
							   10 => 69,
							   );
	
	$comp_score_arr = array(
							1 => 99999,
							2 => 991,
							3 => 981,
							4 => 972,
							5 => 962,
							6 => 953,
							7 => 943,
							8 => 934,
							9 => 924,
							10 => 915,
							11 => 906,
							12 => 896,
							13 => 887,
							14 => 877,
							15 => 868,
							16 => 858,
							17 => 849,
							18 => 839,
							19 => 830,
							20 => 820,
							21 => 811,
							22 => 802,
							23 => 792,
							24 => 783,
							25 => 773,
							26 => 764,
							27 => 754,
							28 => 745,
							29 => 735,
							30 => 726,
							31 => 716,
							32 => 707,
							33 => 698,
							34 => 688,
							35 => 679,
							36 => 669,
							37 => 660,
							38 => 650,
							39 => 641,
							40 => 631,
							41 => 622,
							42 => 613,
							43 => 603,
							44 => 594,
							45 => 584,
							46 => 575,
							47 => 565,
							48 => 556,
							49 => 546,
							50 => 537,
							51 => 527,
							52 => 518,
							53 => 509,
							54 => 499,
							55 => 490,
							56 => 480,
							57 => 471,
							58 => 461,
							59 => 452,
							60 => 434,
							61 => 433,
							62 => 424,
							63 => 414,
							64 => 405,
							65 => 395,
							66 => 386,
							67 => 376,
							68 => 367,
							69 => 357,
							70 => 348,
							71 => 338,
							72 => 329,
							73 => 320,
							74 => 310,
							75 => 301,
							76 => 291,
							77 => 282,
							78 => 272,
							79 => 263,
							80 => 253,
							81 => 244,
							82 => 235,
							83 => 225,
							84 => 216,
							85 => 206,
							86 => 197,
							87 => 187,
							88 => 178,
							89 => 168,
							90 => 159,
							91 => 149,
							92 => 140,
							93 => 131,
							94 => 121,
							95 => 112,
							96 => 102,
							97 => 93,
							98 => 83,
							99 => 74,
							100 => 64,
							);
	
	$headers_arr = array(
						 '#',
						 'Search Terms',
						 'Strength of Top Ten Sites',
						 'inanchor count',
						 'allintitle count',
						 'Strength of Top Ten Sites rating',
						 'inanchor rating',
						 'allintitle rating',
						 'Overall Comp. Score',
						 'Est Monthly',
						 'Est Yearly',
						 'Min. Annual Links',
						 );
	
  
	$report_output = '<table border="0" cellspacing="0" cellpadding="4" class="tbl_results"><thead><tr>';
  
	foreach($headers_arr as $id => $hdr_val) {
	  $report_output .= '<th class="hdr_ste_'.$id.'">'.$hdr_val.'</th>';
	}	
  
	$report_output .= '</tr></thead><tbody>';
	
	$cur_ret = 0;
	foreach($this->search_terms as $cur_srch_val) {
	  $cur_ret++;
		
	  $row_array = array();
	
	  // current row value
	  $row_array[] = $cur_ret;
	  
	  // print current search term
	  $row_array[] = strip_tags(str_replace(array('%22','+'),array('"',' '),$cur_srch_val));
	  
	  // print strength of top ten sites
	  $str_tp_tn_cnt = $goog_scrap->proc_allinanchor($cur_srch_val);
	  // assign new score based on return
	  foreach($comp_score_arr as $id => $cur_val) {
		  if($str_tp_tn_cnt <= $cur_val) $fnl_tp_tn_cnt = $id;
	  }
	  
	  $row_array[] = $fnl_tp_tn_cnt;
	  
	  // print in anchor count
	  $anchor_ret = $goog_scrap->inanchor($cur_srch_val);
	  $row_array[] = number_format($anchor_ret);
		
	  // print allintitle count
	  $title_ret = $goog_scrap->intitle($cur_srch_val);
	  $row_array[] = number_format($title_ret);
	  
	  // set strength of top ten rating
	  if(!empty($str_tp_tn_cnt)) {
		foreach($str_tp_tn_ste_arr as $id => $cur_val) {
			if($str_tp_tn_cnt <= $cur_val) $str_tp_tn_rate = $id;
		}
	  } else {
		  $str_tp_tn_rate = 10;
	  }
	  
	  $row_array[] = $str_tp_tn_rate;
	  
	  // set anchor rating
	  if(!empty($anchor_ret)) {
		foreach($all_rate_arr as $id => $cur_val) {
			if($anchor_ret <= $cur_val) $anch_rate = $id;
		}
	  } else {
		  $anch_rate = 1;
	  }
	  
	  $row_array[] = $anch_rate;
	  
	  // set allintitle rating
	  if(!empty($title_ret)) {
		foreach($all_rate_arr as $id => $cur_val) {
			if($title_ret <= $cur_val) $title_rate = $id;
		}
	  } else {
		  $title_rate = 1;
	  }
	  
	  $row_array[] = $title_rate;
	  
	  $comp_score = round((($anch_rate+$title_rate+$str_tp_tn_rate)/3),1);
	  $row_array[] = $comp_score;

	  require('../prices.php');

	  // print monthly est pricing
	  $row_array[] = '$'.number_format($comp_score_def['val_'.str_replace('.','_',$comp_score)], 2, ".", ",");
	  // print yearly est pricing
	  $row_array[] = '$'.number_format(($comp_score_def['val_'.str_replace('.','_',$comp_score)]*12), 2, ".", ",");
	  // print yearly est link cost
	  $row_array[] = number_format((($comp_score_def['val_'.str_replace('.','_',$comp_score)]*12)/35), 0, ".", ",");
	  
	  $report_output .= '<tr><td>'.implode('</td><td>',$row_array).'</td></tr>';
	
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