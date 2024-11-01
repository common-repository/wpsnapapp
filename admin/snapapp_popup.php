<?php /*
	Plugin Name: SnapApp
	Plugin URI: http://www.snapapp.com
	Description: Plugin for displaying a snapapp from 'www.snapapp.com'
	Author: Dhibakar
	Version: 1.5
	Author URI: http://www.snapapp.com
*/

  require_once '../../../../wp-config.php';
  require_once '../../../../wp-settings.php';  
  
  global $post, $wpdb;
  $table_name   = $wpdb->prefix . "snapapp";
  
  $act          = $_REQUEST['act'];
  $msg          = $_REQUEST['msg'];
  $snapapp      = $_POST['snapapp'];
  
  $snapapp_name = $_POST['snapapp_name'];
  $snapapp_id   = $_POST['snapapp_id'];
  
  // after validation complete insert the value in table
  
  if($snapapp=="yes") 
  {
    $check_q = $wpdb->get_row("SELECT * FROM $table_name WHERE snapapp_name='$snapapp_name' LIMIT 1");
    $this_id = $check_q->id;
    if($check_q != NULL) 
	{
      $msg = "Sorry, this SnapApp Name already exist.";
    } 
	else 
	{
      $wpdb->query("INSERT INTO $table_name (snapapp_name,snapapp_id)VALUES ('$snapapp_name','$snapapp_id')");
      $msg =  "Successfully Inserted.";
    }
  }  
  
function media_send_to_editor($html) 
{
?>
	<script type="text/javascript">
    /* <![CDATA[ */
    var win = window.dialogArguments || opener || parent || top;
    win.send_to_editor('<?php echo addslashes($html); ?>');
    /* ]]> */
    </script>
<?php
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Insert a SnapApp Widget</title>
<style type='text/css'>
body {
	background: #f1f1f1;
}
#button-dialog {
	width:95%
}
#button-dialog div {
	padding: 10px 0;
}
#button-dialog label {
	display: block;
	margin: 0 8px 8px 0;
	color: #333;
}
#button-dialog input[type=text] {
	display: block;
	padding: 3px 5px;
	width: 80%;
}
#button-dialog input[type=submit], input[type=button] {
	padding: 5px;
	font-size: 12px;
}
.textbox {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.error {
	color:#FF0000;
}
body, td, th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.title {
	font-size: 18px;
	font-weight: bold;
}
</style>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js'></script>

<script>

function getObj(name){
    if (parent.window.document.getElementById){
    this.obj = parent.window.document.getElementById(name);
    this.style = parent.window.document.getElementById(name).style;
    }
    else if (parent.window.document.all){
    this.obj = parent.window.document.all[name];
    this.style = parent.window.document.all[name].style;
    }
    }
     
    function getWinSize(){
    var iWidth = 300, iHeight = 300;
     
    if (parent.window.document.documentElement && parent.window.document.documentElement.clientHeight){
    iWidth = parseInt(parent.window..innerWidth,10);
    iHeight = parseInt(parent.window..innerHeight,10);
    }
    else if (parent.window.document.body){
    iWidth = parseInt(parent.window.document.body.offsetWidth,10);
    iHeight = parseInt(parent.window.document.body.offsetHeight,10);
    }
     
    return {width:iWidth, height:iHeight};
    }
     
    function resize_id(obj) {
    var oContent = new getObj(obj);
    var oWinSize = getWinSize();
    if ((oWinSize.height - parseInt(oContent.obj.offsetTop,10))>0)
    oContent.style.height = (oWinSize.height - parseInt(oContent.obj.offsetTop,10));
    }
     
    window.onresize = function() { resize_id('TB_window'); }

</script>

<script type="text/javascript">
	
    function valid() {
  		var e = document.newsnapapp
  		
  		if(e.snapapp_name.value=="")
  		{
  			document.getElementById("errormsg").innerHTML = "Please enter the name.";
  			e.snapapp_name.focus();
  			return false;
  		}
  		if(e.snapapp_id.value=="")
  		{
  			document.getElementById("errormsg").innerHTML = "Please enter the widget code.";
  			e.snapapp_id.focus();
  			return false;
  		}			
  	}
	 
	function insertSnap()
	{
		if(document.getElementById("spid").value == "") {
			alert("Please select any one widget");
			document.getElementById("spid").focus();
			return false;
		}		
	}
	
	 
	
	</script>
<?php 
	if($act=="") :  
		if($_REQUEST['insert']=='yes') :
		$html = '[SnapApp id=' . $_REQUEST['spid'] . ']';		
		media_send_to_editor($html);			
		endif;
	endif 
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<table class="form-table" style="width:100%" id="button-dialog">

<tr><td>
  <?php if($act=="") : ?>
  <form action="" method="get" accept-charset="utf-8">
   <table class="form-table" style="width:100%" cellspacing="0" cellpadding="5">
  <tr><td  class="title">SnapApp Widget</td>
  </tr>
  <tr>
    <td><label for="spid"><span class="textbox">Please select SnapApp Widget</span>: </label></td>
    <td height="25" align="right"><a style="color:#333333" href="snapapp_popup.php?ver=3393&act=new">Add a New SnapApp</a></td>
  </tr>
  <tr><td colspan="2">
  <?php 
          $results=$wpdb->get_results("SELECT * FROM $table_name ORDER BY Id ASC");
          if($wpdb->num_rows) {
            $i = 1;
            echo '<select name="spid" id="spid" class="textbox" style="width:99%"><option value="">-------Select-------</option>';
            foreach ($results as $result) {
              echo '<option value="'.$result->id.'">'.$result->snapapp_name.'</option>';              		
              $i++;
            }	
            echo '</select>';
          } else { 
            echo 'Record not found.';				 
          }
		  
        ?>
  </td></tr>
  <tr>
    <td colspan="2" align="center"> <input type="hidden" name="insert" value="yes" />
      <input type='submit' value='Insert SnapApp'  class="textbox" onClick="return insertSnap();"  /></td>
  </tr>
    </table>
  </form>
  
  <?php  else: ?>
  <form  action="" name="newsnapapp" method="POST">
    <input type="hidden" name="snapapp" value="yes" />
    <table class="form-table" style="width:100%" cellpadding="5">
    <tr><td colspan="2"><b class="title">Add SnapApp Widget</b></td>
    </tr>
      <tr valign="top">
        <th scope="row"  width="90"align="right">Name: </th>
        <td><input type="text" name="snapapp_name" id="snapapp_name"  style="width:97%" value="<?=htmlspecialchars($snapapp_name)?>" />
          <script>document.getElementById("snapapp_name").focus();</script>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"  align="right">Widget Code: </th>
        <td><textarea name="snapapp_id" id="snapapp_id" style="width:97%; height:90px;padding: 3px 5px;" value="<?=htmlspecialchars($snapapp_id)?>"><?=htmlspecialchars($snapapp_id)?>
</textarea></td>
      </tr>
    </table>
    <p class="submit" align="center">
      <input type="submit" value="Insert" class="textbox" onClick="return valid();"/>
      <input type='button' value='Back' class="textbox" onClick="javascript:window.location.href='snapapp_popup.php?ver=3393'" />
    </p>
    <p align="center" ><span class="error" id="errormsg">
      <?=htmlspecialchars($msg)?>
    </span></p>
  </form>
  <?php endif ?>
  
</td></tr></table>
</body>
</html>
