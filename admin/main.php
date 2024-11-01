<?php
  global $wpdb;
  $table_name     = $wpdb->prefix . "snapapp";
  $snapvalue      = $_REQUEST['snapapp_id'];
  $snapapp_name   = $_REQUEST['snapapp_name'];
  $spid           = $_REQUEST['id'];
  $mode           = $_POST['snapapp'];
  $act            = $_POST['action'];
  
  if ( $spid == "" ) {
    $action   = "new";
    $btn      = "Submit";
  } else {
    $action   = "edit";
    $btn      = "Update";
    $results  = $wpdb->get_results("SELECT * FROM $table_name  WHERE  id = $spid ORDER BY Id ASC");
    
    foreach ($results as $row) {
      $snapname = $row->snapapp_id;
      $snapapp_name = $row->snapapp_name;
    }
  }
?>

<div class="wrap">
  <h2>SnapApp - <?php echo ucfirst($action); ?></h2>
  
  <?php
    if ($mode == 'yes') {
      if($snapapp_name == '') {
        echo '<div style="background-color:#ff0000" id="message" class="updated fade"><p>Please enter the name.</p></div>' ;
      } else if($snapvalue == "") {
        echo '<div style="background-color:#ff0000;" id="message" class="updated fade"><p>Please enter the widget code.</p></div>' ;
      }
    }
    
    if ($mode == 'yes' && $snapvalue != "" && $snapapp_name != "") 
	{
      echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade">'."<p>";
      save_gcsa(str_replace('"',"'",$snapvalue),$act,$spid,$_REQUEST['snapapp_name']);
      echo "</p></div>" ;
      echo '<div style="float:left;margin-top:30px;"><a href="'.admin_url('admin.php?page=snapapp_add_menu').'" class="button-secondary action">Add New SnapApp</a> <a href="'.admin_url('admin.php?page=snapapp_manage_snapapps').'" class="button-secondary action">Manage SnapApp</a></div>'; 
    } else {
  ?>
  
  <p style="width:650px">To add a new SnapApp, you must fist create the app on snapapp.com. Grab the widget code from the "Publishing Methods" section of the app builder, and paste in here. Give it a convenient name so you can find it later (this name will not be displayed to users).</p>
  <form enctype="multipart/form-data" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST">
    <input type="hidden" name="snapapp" value="yes" />
    <input type="hidden" name="id" value="<?=htmlspecialchars($_REQUEST['id'])?>" />
    <input type="hidden" name="action" value="<?=$action?>" />
    
    <table class="form-table">    
      <tr valign="top">
        <th scope="row">Name:</th>
        <td>
          <input type="text" name="snapapp_name" id="snapapp_name"  style="width:45%;" value="<?=htmlspecialchars($snapapp_name)?>" />
          <script>document.getElementById("snapapp_name").focus();</script>        
        </td>
      </tr>
        
      <tr valign="top">
        <th scope="row">Widget Code:</th>
        <td>
          <textarea name="snapapp_id" id="snapapp_id" style="width:45%; height:100px;" value="<?=$snapname?>"><?=$snapname?></textarea><br>
          <p>The snapapp id is shown when your script can't be displayed by a browser.</p>
        </td>
      </tr>
    </table>
        
    <p class="submit">
      <input type="submit" class="button-primary" value="<?=$btn?>" />
    </p>
  </form>
  <?php } ?>
</div>
