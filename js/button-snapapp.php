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
  
  if($snapapp=="yes") {
    $check_q = $wpdb->get_row("SELECT * FROM $table_name WHERE snapapp_name='$snapapp_name' LIMIT 1");
    $this_id = $check_q->id;
    if($check_q != NULL) {
      $msg = "Sorry, this SnapApp Name already exist.";
    } else {
      $wpdb->query("INSERT INTO $table_name (snapapp_name,snapapp_id)VALUES ('$snapapp_name','$snapapp_id')");
      $msg =  "Successfully Inserted.";
    }
  }  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Insert a SnapApp Widget</title>
	
	<style type='text/css' src='<?php bloginfo('url'); ?>/wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css'></style>
	<style type='text/css'>
  	body { background: #f1f1f1; }
  	#button-dialog { }
  	#button-dialog div { padding: 10px 0; }
  	#button-dialog label { display: block; margin: 0 8px 8px 0; color: #333; }
  	#button-dialog input[type=text] { display: block; padding: 3px 5px; width: 80%; }
  	#button-dialog input[type=submit],input[type=button] { padding: 5px; font-size: 12px; } 
  	.textbox {	font-family: Arial, Helvetica, sans-serif;	font-size: 12px;}
  	.error { color:#FF0000;}
  </style>
    
	
	<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js'></script>
	<script type='text/javascript' src='<?php bloginfo('url'); ?>/wp-includes/js/tinymce/tiny_mce_popup.js'></script>
	<?php if($act=="") : ?>
  	<script type="text/javascript">
    	$(function(){
    	
    		$('form').submit(function(e){
    			if(document.getElementById("spid").value == "") {
      			alert("Please select any one widget");
      			document.getElementById("spid").focus();
      			return false;
    			}
    			ButtonDialog.insert(ButtonDialog.local_ed)			
    			e.preventDefault();
    		});
    		
    		var ButtonDialog = {
    			local_ed : 'ed',
    			init : function(ed) {
    				ButtonDialog.local_ed = ed;
    				tinyMCEPopup.resizeToInnerSize();
    		  },
    			insert : function insertButton(ed) {
    				// Try and remove existing style / blockquote
    				tinyMCEPopup.execCommand('mceRemoveNode', false, null);
    				// set up variables to contain our input values
    				//var snapappid = jQuery('input[name="spid"]').val();
    				var snapappid = document.getElementById("spid").value;
    
    				var output = '';
    				// setup the output of our shortcode
    				output = '[SnapApp id=' + snapappid + ']';
    				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
    				// Return
    				tinyMCEPopup.close();
    
    				return false
    			}
    		};
    		tinyMCEPopup.onInit.add(ButtonDialog.init, ButtonDialog);
    	});
  	</script>
  <?php endif ?>
  
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
	</script>
</head>

<body>
	<div id="button-dialog">
    
    <?php if($act=="") : ?>
    
    <form action="/" method="get" accept-charset="utf-8">
      <div style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:0px; margin:0px;">
        <b>SnapApp Widget</b>
      </div>  
      
      <div>
        <label for="spid">
          <span class="textbox">Please select SnapApp Widget</span>:<br>
          <a style="font-size: 11px" href="button-snapapp.php?ver=3393&act=new">Add a New SnapApp</a>	
        </label>
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
      </div>
      
      <div  align="center">
        <input type='submit' value='Insert SnapApp'  class="textbox"  />
      </div>
      
    </form> 
      
    <?php  else: ?>
      
    <form  action="" name="newsnapapp" method="POST">
      <input type="hidden" name="snapapp" value="yes" />
      
	    <table class="form-table" style="width:100%">		
        <tr valign="top">
    			<th scope="row"  width="90"align="right">Name: </th>
    			<td>
            <input type="text" name="snapapp_name" id="snapapp_name"  style="width:97%" value="<?=htmlspecialchars($snapapp_name)?>" />
            <script>document.getElementById("snapapp_name").focus();</script>				 
    			</td>
    		</tr>
        
    		<tr valign="top">
    			<th scope="row"  align="right">Widget Code: </th>
    			<td><textarea name="snapapp_id" id="snapapp_id" style="width:97%; height:90px;padding: 3px 5px;" value="<?=htmlspecialchars($snapapp_id)?>"><?=htmlspecialchars($snapapp_id)?></textarea></td>
    		</tr>
      </table>
		
    	<p class="submit" align="center">
        <input type="submit" class="button-primary" value="Insert" class="textbox" onclick="return valid();"/>
        <input type='button' value='Back' class="textbox" onclick="javascript:window.location.href='button-snapapp.php?ver=3393'" />
    	</p>
      <p align="center" ><span class="error" id="errormsg"><?=htmlspecialchars($msg)?></span></p>
    </form>
    
    <?php endif ?>
    
	</div>
</body>
</html>
