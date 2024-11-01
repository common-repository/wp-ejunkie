<?php
/*
Plugin Name: WP E-junkie.com Shopping Cart
Plugin URI: http://smartwebutah.com/
Description: A simple plugin for easily adding products hosted on e-junkie.com to your WordPress powered website.
Version: 1.6.1
Author: Seth Shoultes
Author URI: http://smartwebutah.com/
Copyright 2018  Seth Shoultes

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//Define the version of the plugin
function wpejunkie_version() {
	return '1.6.1';
}
define("WPEJUNKIE_VERSION", wpejunkie_version() );
define('WPEJUNKIE_VERSION_LINK', '<a href="http://wpejunkie.com" target="_blank">WP E-junkie.com Shopping Cart - ' . WPEJUNKIE_VERSION . '</a>');
define('WPEJUNKIE_SUPPORT_LINK', '<a href="http://wpejunkie.com/support/" target="_blank">Help / Support</a>');
define('WPEJUNKIE_ITEMS_LINK', '<a href="admin.php?page=wpejunkie_items_listings">Manage Items</a>');
define('WPEJUNKIE_DONATE_LINK', '<a href="http://www.e-junkie.com/donate/">Make a Donation</a>');
define('WPEJUNKIE_POWERED_BY', '<a href="http://wpejunkie.com" target="_blank">WP E-junkie.com Shopping Cart</a>');
define('WPEJUNKIE_AFFILIATE_URL', 'http://www.e-junkie.com/?r=113214');
define('WPEJUNKIE_COUPON_CODE', 'WPJUNKIE');
define('WPEJUNKIE_AFFILIATE_BUTTON', '<a href="' . WPEJUNKIE_AFFILIATE_URL .'" target="ejcom" title="Shopping Cart by E-junkie"><img src="https://www.e-junkie.com/linkimg/5649f286f0bf206dab311e0850f11a19113214/1.gif" border="0" alt="E-junkie Shopping Cart and Digital Delivery"></a>');

//Define the plugin directory and path
define("WPEJUNKIE_PLUGINPATH", "/" . plugin_basename( dirname(__FILE__) ) . "/");
define("WPEJUNKIE_PLUGINFULLPATH", WP_PLUGIN_DIR . WPEJUNKIE_PLUGINPATH  );
define("WPEJUNKIE_PLUGINFULLURL", WP_PLUGIN_URL . WPEJUNKIE_PLUGINPATH );

//Define all of the plugins database tables
define("WPEJUNKIE_HTML_CODE_TABLE", get_option('wpejunkie_html_code_tbl') );

/*Install the plugin tables and options*/
require_once("install_db.php"); 
register_activation_hook(__FILE__,'wpejunkie_data_tables_install');
register_activation_hook(__FILE__,'wpejunkie_active_install');

//Runs on plugin deactivation
register_deactivation_hook( __FILE__, 'wpejunkie_active_remove' );

//Get the functions
require_once("wpejunkie_functions.php"); 

//Get the shortcodes
require_once("wpejunkie_shortcodes.php"); 


//Get the admin pages
require_once("wpejunkie_items.php");
require_once("wpejunkie_items_list.php");


//Output the admin menu and settings page
if ( is_admin() ){
	//Call the html code
	add_action('admin_menu', 'wpejunkie_settings_menu');
	//Call the style sheet and javascripts
	add_action('admin_print_styles', 'wpejunkie_config_page_styles');
	add_action('admin_print_scripts', 'wpejunkie_admin_scripts');
	
}

//Load the e-junkie javascripts
if ( !is_admin() ){
	//Add the ejunkie scripts to the footer
	add_action('wp_footer', 'wpejunkie_print_footer_script');
}

//Build the menu tab
function wpejunkie_settings_menu() {
	add_menu_page(__('E-junkie.com Shopping Cart - Cart Items','wpejunkie'), __('E-junkie Cart','wpejunkie'), 'administrator','wpejunkie', 'wpejunkie_items');
	
	//Add/Edit items
	add_submenu_page('wpejunkie', __('E-junkie.com Shopping Cart - Add/Edit Items','wpejunkie'), __('Add/Edit Items','wpejunkie'), 'administrator',  'wpejunkie', 'wpejunkie_items');
	
	//Add/Edit items
	add_submenu_page('wpejunkie', __('E-junkie.com Shopping Cart - Add/Edit Items','wpejunkie'), __('List Items','wpejunkie'), 'administrator',  'wpejunkie_items_listings', 'wpejunkie_items_listings');
	
	//General Settings
	add_submenu_page('wpejunkie', __('E-junkie.com Shopping Cart - General Settings','wpejunkie'), __('General Settings','wpejunkie'), 'administrator',  'wpejunkie_settings_page', 'wpejunkie_settings_page');
	
	
}

/*Build the ejunkie settings page */

function wpejunkie_settings_page() {
?>

<div id="configure_organization_form" class="wrap">
  <div id="icon-options-wpejunkie" class="icon32"><br />
  </div>
  <h2>
    <?php _e('E-junkie Shopping General Settings', 'wpejunkie'); ?>
  </h2>
  <div id="wpejunkie-col-left">
    <form method="post" action="options.php">
      <ul id="wpejunkie-sortables">
        <li>
          <div class="box-mid-head">
            <h2 class="fugue f-wrench"><?php _e('General Settings', 'event_espresso'); ?></h2>
          </div>
          <div class="box-mid-body" id="toggle2">
            <div class="padding">
              <?php wp_nonce_field('update-options'); ?>
              <div style=" float:right; width:35%"><?php echo wpejunkie_usage_info(); ?></div>
              <ul>
                <li> <?php echo get_option('ejunkie_id') == '' ? '<p><span class="red_text"><strong>' . __('If you do not have an E-junkie.com account, <a href="' .WPEJUNKIE_AFFILIATE_URL .' target="_blank">please sign up here</a>.', 'wpejunkie') . '</strong></span></p> <p>' . _e('Use the promo code "<strong>'. WPEJUNKIE_COUPON_CODE . '</strong>" to get 60 days of E-junkie FREE!</p>') : '';?>
               
                  <table width="65%">
                    <tr valign="top">
                      <th nowrap="nowrap" width="50%" align="left" scope="row"><?php _e('E-junkie.com ID', 'wpejunkie'); ?><?php echo get_option('ejunkie_id') == '' ? '<span class="red_text"> ----------------></span>' : ''?></th>
                      <td width="50%" align="left"><input name="ejunkie_id" type="text" id="ejunkie_id" value="<?php echo get_option('ejunkie_id'); ?>" /></td>
                    </tr>
                    <tr><td colspan="2"> <p><?php _e('These options can be set to pass default settings to the <a href="' .WPEJUNKIE_AFFILIATE_URL .'" target="_blank">E-junkie</a> buttons using tags.', 'event_espresso'); ?></p> </td></tr>
                    <tr valign="top">
                      <th align="left" scope="row">
				      <p><?php _e('Custom Add to Cart Button (URL)', 'wpejunkie'); ?></p></th>
                      <td align="left"><p><input name="ejunkie_add2cart_image" type="text" id="ejunkie_add2cart_image" value="<?php echo get_option('ejunkie_add2cart_image'); ?>" /></p></td>
                    </tr>
                    <tr valign="top">
                      <th nowrap="nowrap" align="left" scope="row"><p><?php _e('Custom Checkout Button (URL)', 'wpejunkie'); ?></p></th>
                      <td align="left"><p><input name="ejunkie_viewcart_image" type="text" id="ejunkie_viewcart_image" value="<?php echo get_option('ejunkie_viewcart_image'); ?>" /></p></td>
                    </tr>
                   
                  </table>
                </li>
              </ul>
              <p>
                <input type="submit" value="<?php _e('Save Changes') ?>" />
              </p>
              <p> <?php echo get_option('ejunkie_id') == '' ? '' : __('Use the promo code "<strong>'. WPEJUNKIE_COUPON_CODE . '</strong>" to get 60 days of E-junkie FREE!</p>')?></p>
              <input type="hidden" name="action" value="update" />
              <input type="hidden" name="page_options" value="ejunkie_id,ejunkie_add2cart_image,ejunkie_viewcart_image" />
            </div>
          </div>
        </li>
      </ul>
    </form>
    <?php echo wpejunkie_admin_links() ?>
  </div>
</div>
<?php wpejunkie_display_right_column();
}//End of the settings page