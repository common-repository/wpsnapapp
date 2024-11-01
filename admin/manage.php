<div class="wrap">
  <h2>SnapApp - Manage Files</h2>
  
  <?php
    global $wpdb;
    global $_POST;
    $table_name = $wpdb->prefix . "snapapp";
    
    // Delete Function
    if ($_REQUEST['act']=="delete") : ?>
    
      <div id="message" class="updated">
        <p>
        <?php if($_REQUEST['id']!=""):
          $delete_results=$wpdb->query("DELETE FROM ".$table_name." WHERE id =".$_REQUEST['id']);
          _e('Successfully Deleted.');
        endif ?>
        </p>
      </div>
      
    <?php endif;

    // Multiple Delete Function
    if ($_POST['bulkaction']!="" ) : 
      $postCount = count($_POST['snap']);
      
      for ( $i=0; $i < $postCount; $i++ ) {
        $deleteIds .= $_POST['snap'][$i].',';
      }
      $deleteIds = rtrim($deleteIds,',');
      
      if (strlen($deleteIds)>0) :
        $delete_results=$wpdb->query('DELETE FROM '.$table_name.' WHERE id in ('.$deleteIds.')');
        _e('<div id="message" class="updated"><p>Successfully Deleted.</p></div>');
      endif;
    endif;
  ?> 

  <script type="text/javascript">
    function condel(frm) {
     
      if(document.form1.bulkaction.value=="") {
        alert("Please select Bulk Action.");
        return false;
      }
      a = confirm("This will delete all selected apps.  Are you sure you want to do this?");
      
      if( a == true ) {
        var checked = false;
        var chks = document.getElementsByName('snap[]');
        var checkCount = 0;
        
        for(i=0;i<chks.length;i++) {
          if (chks[i].checked) {
            checkCount++;
            checked = true;
            break;
          }
        }
        
        if(checked==true) {
          document.form1.submit();
        } else {
          alert("Please select atleast 1 user");
          return false;
        }
      } else { return false }
    } 
      
    function checkAllFields(ref) {
      var chkAll        = document.getElementById('checkAll'),
          checks        = document.getElementsByName('snap[]'),
          boxLength     = checks.length,
          allChecked    = false,
          totalChecked  = 0;
      //var removeButton = document.getElementById('removeChecked');
      
      if ( ref == 1 ) {
        if ( chkAll.checked == true ) {
          for ( i=0; i < boxLength; i++ )
          checks[i].checked = true;
        } else {
          for ( i=0; i < boxLength; i++ ) checks[i].checked = false;
        }
      } else {
        for ( i=0; i < boxLength; i++ ) {
          if ( checks[i].checked == true ) {
            allChecked = true;
            continue;
          } else {
            allChecked = false;
            break;
          }
        }
        
        if ( allChecked == true ) chkAll.checked = true;
        else chkAll.checked = false;
      }
      for ( j=0; j < boxLength; j++ ) {
        if ( checks[j].checked == true ) totalChecked++;
      }
      //removeButton.value = "Remove ["+totalChecked+"] Selected";
    }
  </script>


  <form  name="form1" id="form1" action="" method="post">
  <?php wp_nonce_field('update-permalink') ?>
  
    <h3><?php _e('SnapApp List'); ?></h3>
    <div style="float:left;margin-bottom:10px;margin-top:-2px;">
      <a href="<?php echo admin_url('admin.php?page=snapapp_add_menu'); ?>" class="button-secondary action"><?php _e('Add SnapApp') ?></a>
    </div>
    
    <table class="wp-list-table widefat fixed users" cellspacing="0">
      <thead>
        <tr>
          <th scope='col' id='cb' class='manage-column column-cb check-column'  style="width:10%;"> 
            <input type="checkbox" onclick="checkAllFields(1);" id="checkAll" />
          </th>
          <th scope='col' id='id' class='manage-column column-cb check-column'  style="width:10%;">ID</th>
          <th scope='col' id='id' class='manage-column column-id username'  style="text-align:left">Name</th>
          <th scope='col' id='name' class='manage-column column-username '  style="width:25%;"><span>Widget Code</span></th>
          <th scope='col' id='action' class='manage-column column-action' style="width:15%;">Action</th>
        </tr>
      </thead>
      <tbody id="the-list" class='list:user'>
      
        <?php 
          $results=$wpdb->get_results("SELECT * FROM $table_name ORDER BY Id ASC");
          if ($wpdb->num_rows) {
            $i = 1;
            foreach ($results as $result) { ?>
                  
        <tr>    
          <td  style="width:10%;">
            <input type='checkbox' name='snap[]' id='chkSel' class='administrator' value='<?php echo $result->id;?>' onclick="checkAllFields(2);" />
          </td>
          <td  style="width:10%;"><?php echo $result->id;?></td>
          <td  style="width:25%;"><?php echo $result->snapapp_name;?></td>
          <td  style="width:25%;"><?php echo substr(htmlentities($result->snapapp_id),0,100);?></td>
          <td  style="width:15%;"><a href="<?php echo admin_url('admin.php?page=snapapp_add_menu&id='.$result->id); ?>">Edit</a> | <a href="<?php echo admin_url('admin.php?page=snapapp_manage_snapapps&act=delete&id='.$result->id); ?>">Delete</a></td>
        </tr>
        
            <?php   
              $i++;
            } 
          } else {  ?>
        
        <tr><td colspan="4" align="center">Record not found.</td></tr>
        
          <?php
          }
        ?>
      
      <thead>
        <tr>
          <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""></th>
          <th scope='col' id='id' class='manage-column column-id num' style="text-align:left">ID</th>
          <th scope='col' id='id' class='manage-column column-id num' style="text-align:left">Name</th>
          <th scope='col' id='name' class='manage-column column-username'  style=""><span>Widget Code</span></th>
          <th scope='col' id='action' class='manage-column column-action'  style="">Action</th>
        </tr>
      </thead>
    </table>
    
    <div class="tablenav bottom">
      <div class="alignleft actions">
        <select name='bulkaction' id="bulkaction">
          <option value='' selected='selected'>Bulk Actions</option>
          <option value='delete'><?php _e("Delete"); ?></option>
        </select>
      </div>
      <input type="submit" name="del_cat" id="del_cat" onClick="return condel(this);" class="button-secondary action" value="Apply"  />
    </div>
  </form> 
</div>