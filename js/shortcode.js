/*
  Plugin Name: SnapApp
  Plugin URI: http://www.snapapp.com
  Description: Plugin for displaying a snapapp from 'www.snapapp.com'
  Author: Dhibakar
  Version: 1.5
  Author URI: http://www.snapapp.com
*/

(function() {  
    tinymce.create('tinymce.plugins.wp_snapapp', {
        init : function(ed, url) {
          ed.addCommand('wp_snapapp_cmd', function() {
        ed.windowManager.open({
          file : url + '/button-snapapp.php',
          width : 420 + parseInt(ed.getLang('button.delta_width', 0)),
          height : 220 + parseInt(ed.getLang('button.delta_height', 0)),
          inline : 1
          }, {
          plugin_url : url
        });
      });
        //alert(url)
            ed.addButton('wp_snapapp', {
                title : 'Add a SnapApp',
                image : url + '/snapapp_icon.png',
                cmd: 'wp_snapapp_cmd',
            });
        },
    getInfo : function() {
      return {
        longname : 'Insert a SnapAppID',
        author : 'Dhibakar',
        authorurl : '#',
        infourl : '#',
        version : tinymce.majorVersion + '.' + tinymce.minorVersion
      };
    },
    });
    tinymce.PluginManager.add('wp_snapapp', tinymce.plugins.wp_snapapp);
})();
