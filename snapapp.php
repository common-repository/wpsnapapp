<?php
/*
  Plugin Name: SnapApp
  Plugin URI: http://www.snapapp.com
  Description: Plugin for displaying a snapapp from 'www.snapapp.com'
  Author: SnapApp
  Version: 1.5
  Author URI: http://www.snapapp.com
*/

// menu list
function snapapp_menu()
{
  $icon_url = plugins_url($path = '/wpsnapapp').'/images/snapapp_icon.png';
  add_menu_page('SnapApp', 'SnapApp', 9, __FILE__, 'snapapp_main_menu', $icon_url);
  add_submenu_page(__FILE__, 'About/Help', 'About/Help', 9, __FILE__, 'snapapp_main_menu');
  add_submenu_page(__FILE__, 'SnapApp - New', 'Add New SnapApp', 9, 'snapapp_add_menu', 'snapapp_add_menu');
  add_submenu_page(__FILE__, 'Manage SnapApps', 'Manage SnapApps', 9, 'snapapp_manage_snapapps', 'snapapp_manage_snapapps');
}

//Install process code
if (!class_exists("gc_snapapp")) {
  class snapapp_ins {
    //the constructor that initializes the class
    function snapapp_ins() {
    }

    //setup a db table to store file info in
    function gcsa_install () {
       global $wpdb;
       $table_name = $wpdb->prefix . "snapapp";
       if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
        $sql = "CREATE TABLE " . $table_name . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        snapapp_name VARCHAR(255) NOT NULL,
        snapapp_id TEXT,
        UNIQUE KEY id (id)
      );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
      }

    }

  }
}

// add,edit page redirect functions
function snapapp_add_menu() {
  include("admin/main.php");
}
// snapapp index page redirect functions
function snapapp_main_menu() {
  include("admin/options.php");
}
// manage snapapp page redirect functions
function snapapp_manage_snapapps() {
  include("admin/manage.php");
}

//initialize the class to a variable
if (class_exists("snapapp_ins")) {
  $cl_snapapp = new snapapp_ins();
}

//Actions and Filters and Menu
if (isset($cl_snapapp)) {
  //Actions - remember to reference the class variable for functions
  register_activation_hook(__FILE__,array(&$cl_snapapp,'gcsa_install'));
  add_action('admin_menu', 'snapapp_menu');
  add_action('init', 'snapapp_init');
}

// add new media button
add_action('media_buttons_context', 'plugin_media_button');
function plugin_media_button($context)
{

    $image_btn 	= plugins_url($path = '/wpsnapapp').'/images/snapapp_icon.png';
	$popupurl 	= plugins_url($path = '/wpsnapapp').'/admin/snapapp_popup.php';
    $out = '<a href="'.$popupurl.'?type=wpsnapapp&amp;TB_iframe=1&max-width=320" class="thickbox" title="Add a SnapApp"><img src="'.$image_btn.'" alt="Add a SnapApp" /></a>';
    return $context . $out;
}

//initialize the snapapp in editor
function snapapp_init()
{
  add_action('wp_head', 'headScript' );  // add the javascript code on head
}

// Include Javascript code on head
function headScript()
{
    $script  ="<script type=\"text/javascript\">\n";
    $script  .="      (function(){\n";
    $script  .="        var s = document.createElement('script');\n";
    $script  .="        s.type = 'text/javascript';\n";
    $script  .="    s.async = true;\n";
    $script  .="    var src = document.location.protocol + '//';\n";
    $script  .="    src += (document.location.protocol == 'http:') ?\n";
    $script  .="      'cdn.snapapp.com' : 'scdn.snapapp.com';\n";
    $script  .="    src += '/widget/widget.js';\n";
    $script  .="    s.src = src;\n";
    $script  .="    s.id = 'eeload';\n";
    $script  .="    document.getElementsByTagName('head')[0].appendChild(s);\n";
    $script  .="  })();\n";
    $script  .="</script>\n";

    echo $script;
}


function add_button($buttons)
{
  array_push($buttons, "wp_snapapp");
  return $buttons;
}

//add and save functions
function save_gcsa($snapapp_id,$action,$sid,$sname)
  {
      //Declare the needed global stuff
      global $wpdb;
      global $_POST;
      $table_name = $wpdb->prefix . "snapapp";

      if($action=="new")
      {
          $check_q = $wpdb->get_row("SELECT * FROM $table_name WHERE snapapp_name='$sname' LIMIT 1");
          $this_id = $check_q->id;
          if($check_q != NULL)
          {
            echo "Sorry, this SnapApp Name already exists.";
          }
          else
          {
            $wpdb->query("INSERT INTO $table_name (snapapp_name,snapapp_id)VALUES ('$sname','$snapapp_id')");
            echo "Successfully Inserted.";
          }
      }
      else
      {
          $check_q = $wpdb->get_row("SELECT * FROM $table_name WHERE snapapp_name='$sname' LIMIT 1");
          $this_id = $check_q->id;
          if($check_q != NULL && $this_id!=$sid)
          {
            echo "Sorry, this SnapApp Name already exist.";
          }
          else
          {
			echo "UPDATE $table_name SET snapapp_name='$sname', snapapp_id='$snapapp_id' WHERE id='$sid'";
            $wpdb->query("UPDATE $table_name SET snapapp_name='$sname', snapapp_id='$snapapp_id' WHERE id='$sid'");
            echo "Successfully Updated.";
          }

      }
  }


//ShortCode Function
function getSnapAppScript( $atts )
{
  global $wpdb;
  $table_name = $wpdb->prefix . "snapapp";

    extract( shortcode_atts( array(
        'id' => false
    ), $atts ) );
    if ('id' == false){
        return false;
    }

    $spid = $atts['id'];
  $results = $wpdb->get_results("SELECT * FROM $table_name  WHERE  id = $spid ORDER BY Id ASC");
  if ($results) :
  foreach ($results as $row)
  {
    // Title of the SnapApp
    //$result = "<h2 class='entry-title'>".$row->snapapp_name."</h3>";
    // Code of the SnapApp
    $result .= $row->snapapp_id;
  }
  else :
  $result = "Not Found";
  endif;
    return $result;
}

add_shortcode('SnapApp', 'getSnapAppScript' );


// widget code
define(SNAP_APP, "widget_snap_app");

//Pubilc side code print function
function list_snapappid($id)
{
  global $wpdb;
  $table_name = $wpdb->prefix . "snapapp";
  $results = $wpdb->get_results("SELECT * FROM $table_name  WHERE  id = $id ORDER BY Id ASC");
  if ($results) :
  foreach ($results as $row)
  {
    // Title of the SnapApp
    //$result = "<h3 class='widget-title'>".$row->snapapp_name."</h3>";
    // Code of the SnapApp
    $result .= $row->snapapp_id;
  }
  else :
  $result = "Not Found";
  endif;
   echo $result;
}

// Call function for public side
function widget_snapapp($args) {
  extract($args, EXTR_SKIP);
  $options = get_option(SNAP_APP);
  // Query the next scheduled post
  $snapId = $options["id"];
  echo $before_widget;
  list_snapappid($snapId);
  echo $after_widget;
}

function widget_gcsnapapp_init() {
  wp_register_sidebar_widget(SNAP_APP, __('SnapApp'), 'widget_snapapp');
  wp_register_widget_control(SNAP_APP, __('SnapApp'), 'widget_snapapp_control');
}

function widget_snapapp_control() {
  $options = get_option(SNAP_APP);
  if (!is_array($options)) {
    $options = array();
  }

  $widget_data = $_POST[SNAP_APP];
  if ($widget_data['submit']) {
    $options['id'] = $widget_data['id'];
    update_option(SNAP_APP, $options);
  }

// Render form
  $snapId = $options['id'];

?>
  <p> <label for="<?php echo SNAP_APP;?>_id-">Widget:</label>
     <?php
    global $post, $wpdb;
    $table_name = $wpdb->prefix . "snapapp";
    $results=$wpdb->get_results("SELECT * FROM $table_name ORDER BY Id ASC");
    if($wpdb->num_rows)
    {
    $i = 1;
    $result =  '<select class="widefat" type="text"  name="'.SNAP_APP.'[id]" id="'.SNAP_APP.'-id" style="width:99%">';
    foreach ($results as $row)
    {
      if($row->id == $snapId)
      {
        $selectv = 'selected="selected"';
      }
      else
      {
        $selectv = "";
      }

      $result .='<option value="'. $row->id .'" '. $selectv .' >'. $row->snapapp_name .'</option>';
      $i++;
    }
    $result .='</select>';
    } else {
    $result = 'Record not found.';
    }
    echo $result;
  ?>

  </p>
    <input type="hidden" name="<?php echo SNAP_APP; ?>[submit]" value="1">
<?php
}

// Register widget to WordPress
add_action("plugins_loaded", "widget_gcsnapapp_init");

?>
