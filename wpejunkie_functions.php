<?php

//Output the admin style sheet
function wpejunkie_config_page_styles() {
	wp_enqueue_style('dashboard');
	wp_enqueue_style('thickbox');
	wp_enqueue_style('global');
	wp_enqueue_style('wp-admin');
	wp_enqueue_style('wpejunkie', WPEJUNKIE_PLUGINFULLURL.'style.css');//wpejunkie core style
}
//Print the admin scripts
function wpejunkie_admin_scripts() {
	switch ( $_REQUEST['page'] ){
		case ( $_REQUEST['page'] == 'wpejunkie' || $_REQUEST['page'] == 'wpejunkie_items_listings' ):
			wp_enqueue_script('postbox');
			wp_enqueue_script('dashboard');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('tiny_mce');
			wp_enqueue_script('editor');
			wp_enqueue_script('editor-functions');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('post');
			wp_enqueue_script( 'tiny_table',  WPEJUNKIE_PLUGINFULLURL.'tiny-table.js', array('jquery') );//Events core table script
			remove_all_filters('mce_external_plugins');
		break;
	}
}
		
//Build the footer script posts/pages
function wpejunkie_print_footer_script() {
	$content = '<script language="javascript" type="text/javascript"><!-- function EJEJC_lc(th) { return false; } function EJEJC_config(){EJEJC_INITCSS=false;} // --></script><script src="http://www.e-junkie.com/ecom/box.js" type="text/javascript"></script>';
	echo $content;
}


/*Build the other page elements*/

// gets current URL to return to after donating
function get_wpejunkie_current_location() {
	$wpejunkie_current_location = "http";
	$wpejunkie_current_location .= ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? "s" : "")."://";
	$wpejunkie_current_location .= $_SERVER['SERVER_NAME'];
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
		if($_SERVER['SERVER_PORT']!='443') {
			$wpejunkie_current_location .= ":".$_SERVER['SERVER_PORT'];
		}
	}
	else {
		if($_SERVER['SERVER_PORT']!='80') {
			$wpejunkie_current_location .= ":".$_SERVER['SERVER_PORT'];
		}
	}
	$wpejunkie_current_location .= $_SERVER['REQUEST_URI'];
	echo $wpejunkie_current_location;
}

//Build the right column
function wpejunkie_display_right_column (){
?>
<div id="wpejunkie-col-right">
<div class="box-right">
		<div class="box-right-head">
			<h3 class="fugue f-megaphone"><?php _e('New @ Shoultes.net', 'wpejunkie'); ?></h3>
		</div>
		<div class="box-right-body">
			<div class="padding">
				<ul class="infolinks">
					<?php // Get RSS Feed(s)
include_once(ABSPATH . WPINC . '/feed.php');
$rss = fetch_feed('http://shoultes.net/feed/rss/?cat=-1538');
$maxitems = $rss->get_item_quantity(3); 
$rss_items = $rss->get_items(0, $maxitems); 
?>
    <?php if ($maxitems == 0) echo '<li>No items.</li>';
    else
    // Loop through each feed item and display each item as a hyperlink.
    foreach ( $rss_items as $item ) : ?>
       <li> <a target="_blank" href='<?php echo $item->get_permalink(); ?>' title='<?php echo $item->get_title(); ?>'>
        	<?php echo $item->get_title(); ?>
		</a></li>
    <?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="box-right">
		<div class="box-right-head">
			<h3 class="fugue f-info-frame"><?php _e('Sponsors', 'wpejunkie'); ?></h3>
		</div>
		<div class="box-right-body">
			<div class="padding"> <?php
					$wpejunkie_sponsors = wp_remote_retrieve_body( wp_remote_get('http://shoultes.net/ejunkie-plugin-sponsors.php') );
					echo $wpejunkie_sponsors;
				?>
            </div>
		</div>
	</div>
</div>
<?php 
}

/*Display setup notice */

function wpejunkie_activation_notice(){
		if(function_exists('admin_url')){
						echo '<div class="error fade"><p><strong>' . __('WP E-junkie must be configured. Go to','event_espresso') . ' <a href="' . admin_url( 'admin.php?page=wpejunkie_settings_page' ) . '">'. __('the General Settings page','event_espresso') . '</a>'.__(' to configure the plugin options','event_espresso').'.</strong></p></div>';
		}else{
				echo '<div class="error fade" ><p><strong>' . __('WP E-junkie must be configured. Go to','event_espresso') . '<a href="' . get_option('siteurl') . 'admin.php?page=wpejunkie_settings_page' . '">' . __('the General Settings page','event_espresso') . '</a>' . __('to configure the plugin options','event_espresso').'.</strong></p></div>';
		}
}
if($_POST['ejunkie_id'] == NULL && get_option('ejunkie_id') == '' && get_option('ejunkie_active')=='Y'){
	add_action( 'admin_notices', 'wpejunkie_activation_notice');
}

function wpejunkie_usage_info($item_id = ''){?>
<p><strong><?php _e('Post/Page Tags', 'event_espresso'); ?></strong><br />
(<font size="-2"><?php _e('These tags are used on posts and pages to display the cart butons.', 'event_espresso'); ?></font>)</p>
<p><strong><?php _e('Displays an Add to Cart Button', 'event_espresso'); ?></strong><br /><br />
<span style="font-size:10px; padding:5px; background:#FFC; border:solid 1px #0C0">[EJUNKIE_ADD2CART item="<?php echo $item_id =='' ? 'item_id' : $item_id ?>"]</span></p>
<p><strong><?php _e('Displays a View Cart Button', 'event_espresso'); ?></strong><br /><br />
<span style="font-size:10px; padding:5px; background:#FFC; border:solid 1px #0C0">[EJUNKIE_VIEWCART]</span></p>
<?php
}

//function to delete items
function wpejunkie_delete_item(){
	global $wpdb;
	$sql="DELETE FROM " . WPEJUNKIE_HTML_CODE_TABLE . " WHERE unique_id='" . $_REQUEST['unique_id'] . "'";
	$wpdb->query($sql);
}

//Links
function wpejunkie_admin_links(){
?>
	<p align="right"><?php echo WPEJUNKIE_ITEMS_LINK ?> | <?php echo WPEJUNKIE_SUPPORT_LINK ?> | <?php echo WPEJUNKIE_VERSION_LINK ?>  | <?php echo WPEJUNKIE_DONATE_LINK ?></p>
<?php
}