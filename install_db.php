<?php

//This function installs the tables
function wpejunkie_run_db_install ($table_name, $table_version, $sql) {

		   global $wpdb;
		 
		   $wp_table_name = $wpdb->prefix . $table_name;
		   
		   if($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {

				$sql_create_table = "CREATE TABLE " . $wp_table_name . " ( " . $sql . " ) ;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql_create_table);

			//create option for table version
				$option_name = $table_name.'_tbl_version';
				$newvalue = $table_version;
				  if ( get_option($option_name) ) {
					    update_option($option_name, $newvalue);
					  } else {
					    $deprecated=' ';
					    $autoload='no';
					    add_option($option_name, $newvalue, $deprecated, $autoload);
				  }
			//create option for table name
				$option_name = $table_name.'_tbl';
				$newvalue = $wp_table_name;
				  if ( get_option($option_name) ) {
					    update_option($option_name, $newvalue);
					  } else {
					    $deprecated=' ';
					    $autoload='no';
					    add_option($option_name, $newvalue, $deprecated, $autoload);
				  }
			
		}
		
	// Code here with new database upgrade info/table Must change version number to work.
		 
		 $installed_ver = get_option( $table_name.'_tbl_version' );
	     if( $installed_ver != $table_version ) {
			$sql_create_table = "CREATE TABLE " . $wp_table_name . " ( " . $sql . " ) ;";
	      	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	     	dbDelta($sql_create_table);
	     	update_option( $table_name.'_tbl_version', $table_version );
	      }
}

//Install/Update Tables when plugin is activated
function wpejunkie_data_tables_install () {
	//Run the sql queries
	$table_version = WPEJUNKIE_VERSION;

	$table_name = "wpejunkie_html_code";

	$sql =" id int(11) unsigned NOT NULL AUTO_INCREMENT,
			unique_id VARCHAR(23),
			product_name VARCHAR(255) DEFAULT NULL,
			product_desc TEXT,
			item_id VARCHAR(100) DEFAULT NULL,
			html TEXT,
			PRIMARY KEY  (id) ";
	wpejunkie_run_db_install ($table_name, $table_version, $sql);
}

//Adds options
function wpejunkie_active_install() {
	//Creates new database field
	add_option('ejunkie_active', 'Y', '', 'yes');
	add_option('ejunkie_id', '', '', 'yes');
	add_option('ejunkie_add2cart_image', '', '', 'yes');
	add_option('ejunkie_viewcart_image', '', '', 'yes');
	//add_option('ejunkie_affiliate_id', '5649f286f0', '', 'yes');
}
 

//Deletes the options
function wpejunkie_active_remove() {
	delete_option('ejunkie_active');
}
