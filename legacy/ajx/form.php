<?PHP
session_start();
?>
<form id="form1" name="form1" method="post" action="">
  <table border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <th colspan="2" align="center" valign="top"><strong>CM Sera Tool</strong></th>
    </tr>
    <tr>
      <th align="right" valign="top"><strong>Domain:</strong></th>
      <td><input name="domain" type="text" id="domain" size="50" value="<?PHP if($_SESSION['domain'] != '') echo $_SESSION['domain']; ?>" /></td>
    </tr>
    <tr>
      <th align="right" valign="top"><strong>Keywords:</strong></th>
      <td><textarea name="keywords" id="keywords" cols="45" rows="7"><?PHP if($_SESSION['keywords'] != '') echo $_SESSION['keywords']; else echo 'blue thursday monkey
norfolk insurance
search engine optimization'; ?></textarea></td>
    </tr>
    <tr>
      <th align="right" valign="top">Exact Search:</th><td valign="top"><input type="checkbox" name="exact_srch" id="exact_srch" value="1" <?PHP if($_SESSION['exact_srch'] == 1) echo 'checked="checked"'; ?> />
      </td>
    </tr>
    <tr>
      <th align="right" valign="top"><strong>Email Address:</strong></th>
      <td><input name="email_address" type="text" id="email_address" size="50" value="<?PHP if($_SESSION['email_address'] != '') echo $_SESSION['email_address']; ?>" /></td>
    </tr>
<!--    <tr>
      <th align="right" valign="top">Verbose Output:</th>
      <td valign="top"><input type="checkbox" name="verbose_opt" id="verbose_opt" value="1" <?PHP if($_SESSION['verbose_opt'] == 1) echo 'checked="checked"'; ?> /></td>
    </tr> --> 
   <tr>
      <th colspan="2" align="center"><input type="button" name="button" id="button" value="Submit" onclick="srch_frm_sbmt();" /></th>
    </tr>
  </table>
</form>