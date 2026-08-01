var loading_str = '<center><img src="../js/30-1.gif"></center>';
var timer_ld = '<center><div id="timer"></div></center>';

var sec = 0;
var min = 0;
function stopwatch() {
  sec++;
  if (sec == 60) {
	 sec = 0;
	 min += 1;
  }
  totalTime = ((min<=9) ? "0" + min : min) + " : " + ((sec<=9) ? "0" + sec : sec);
  document.getElementById("timer").innerHTML = totalTime;
  start = setTimeout("stopwatch()", 1000);
}
   
function srch_frm_sbmt() {
   
   var srch_txt = '<center><img src="../js/perform_srch.jpg"></center>';
   var srch_finish_txt = '<center>Report generated. <input name="Generate" value="Generate Another" type="button" onclick="reload_pg()" /><br><a href="http://74.208.195.229/">Generate Different Report</a></center>';

  var domain = $("#domain").val();
  var keywords = $("#keywords").val();
  var email_address = $("#email_address").val();
  if ($('#exact_srch').is(':checked')) {
	var exact_srch = $("#exact_srch").val();
  } else {
	var exact_srch = 0;
  }
   
  $(".frm_area").empty().html(srch_txt);
  $(".reports_rtn").empty().html(loading_str+timer_ld);
  sec = 0;
  min = 0;
  stopwatch();
  document.title = 'Performing requested search.';
  
   $.ajax({
	 type: "POST",
	 url: "ajx/process.php",
	 data: "domain="+domain+"&exact_srch="+exact_srch+"&keywords="+keywords+"&email_address="+email_address,
	 success: function(msg){
	   $(".frm_area").empty().html('<center>Total Search Time: '+$('#timer').html()+'</center>'+srch_finish_txt);
	   $(".reports_rtn").html(msg);
	  document.title = 'Report Generated.';
	 }
   });
   
}

function reload_pg() {
 location.reload(true);
}

function ld_frm() {
  document.title = 'CM DRANK Search Tool';
  
  $(".frm_area").empty().html(loading_str);
  
   $.ajax({
	 url: "ajx/form.php",
	 success: function(msg){
		 $(".frm_area").html(msg);
	 }
   });
   
}

$(function() {
  $(".tbl_results").tablesorter();
  ld_frm();
});

function printpop() {
  my_window= window.open ("",
	"mywindow1","status=1,width=800,height=250,resizable=1,scrollbars=1");
  var html = '<html><head><title>Print Your Results</title></head><link rel="stylesheet" type="text/css" href="/sales-tool/def.css" media="screen" /><body><table border="0" cellspacing="0" cellpadding="4" class="tbl_results">'+$(".tbl_results").html()+'</table></body></html>';
  my_window.document.write(html); 
}

