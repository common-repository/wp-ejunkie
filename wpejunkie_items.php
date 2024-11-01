<?php
//Build the edit items page.
function wpejunkie_items(){
	//Get the items list
	require_once("items_list.php"); 
	
	/**********************************
	*******	Add Item
	***********************************/
	function wpejunkie_item_insert(){
		global $wpdb;
			$product_name=esc_html($_REQUEST['product_name']);
			$product_desc=esc_html($_REQUEST['product_desc']);
			$item_id=esc_html($_REQUEST['item_id']);
			$html=esc_html($_REQUEST['html']);
			
			$unique_id = uniqid('ej',true);
			
			$sql=array('unique_id'=> $unique_id, 'product_desc'=>$product_desc, 'product_name'=>$product_name, 'item_id'=>$item_id, 'html'=>$html);
			
			$sql_data = array('%s','%s','%s');
							
			$wpdb->insert( WPEJUNKIE_HTML_CODE_TABLE, $sql, $sql_data);
			
			return $wpdb->insert_id;
	}
	
	
	/**********************************
	*******	Update Item
	***********************************/
	
	//Updae the item
	function wpejunkie_item_update($unique_id){
		global $wpdb;
		
			$product_name=esc_html($_REQUEST['product_name']);
			$product_desc=esc_html($_REQUEST['product_desc']);
			$item_id=esc_html($_REQUEST['item_id']);
			$html=esc_html($_REQUEST['html']);
						
			$wpdb->update(WPEJUNKIE_HTML_CODE_TABLE,
					array('product_name'=>$product_name, 'product_desc'=>$product_desc, 'html'=>$html),
			 		array( 'unique_id' => $unique_id )
			);

			
			return $unique_id;
	}
	
	/**********************************
	*******	Show Items Form
	***********************************/

	function wpejunkie_item_update_form($id = ''){
		wp_tiny_mce( false , // true makes the editor "teeny"
			array(
				"editor_selector" => "theEditor"//This is the class name of your text field
			)
		);
		global $wpdb;
		$update_item = false;
		if ($_REQUEST['unique_id'] !='' || $id !=''){
	
			$update_item = 'true';
						
			$sql = "SELECT * FROM " . WPEJUNKIE_HTML_CODE_TABLE . " WHERE " . ($_REQUEST['unique_id'] == "" ? "id = '" . $id . "'" : "unique_id = '" . $_REQUEST['unique_id'] . "'");
			$wpdb->query($sql);
	
			if ($wpdb->num_rows > 0 && $update_item = 'true')
				$items = $wpdb->get_results($sql);
						
		}
						
	?>
	<script type="text/javascript">
		//Confirm Delete
		function confirmDelete(){
		 if (confirm('Are you sure want to delete?')){
			  return true;
			}
			return false;
		  }
		  
		//Select All
		  function selectAll(x) {
		for(var i=0,l=x.form.length; i<l; i++)
		if(x.form[i].type == 'checkbox' && x.form[i].name != 'sAll')
		x.form[i].checked=x.form[i].checked?false:true
		}
    </script>
	<div id="wpejunkie_organization_form" class="wrap">
	  <div id="icon-options-wpejunkie" class="icon32"><br />
	  </div>
	  <h2>
		<?php _e('E-junkie Shopping Cart Items', 'wpejunkie'); ?> 
	  </h2>
	  <div id="wpejunkie-col-left">
		<form method="post" action="admin.php?page=wpejunkie">
		  <ul id="wpejunkie-sortables">
			<li>
			  <div class="box-mid-head">
				<h2 class="fugue f-wrench">
				  <?php echo  $update_item == 'true' ? __('Edit Item', 'event_espresso') : __('Add Item', 'event_espresso');?>
				</h2>
			  </div>
			  <div class="box-mid-body" id="toggle2">
				<div class="padding">
				 
				  <ul>
					<li> 
					
					<?php echo get_option('ejunkie_id') == '' ? '<p class="red_text"><strong>' . __('If you do not have an E-junkie.com account, <a href="' . WPEJUNKIE_AFFILIATE_URL . '" target="_blank">please sign up here</a>.', 'wpejunkie') . '</strong></p>' : '';?>
					<p><?php _e('Add items from your', 'event_espresso'); ?> <a href="<?php echo WPEJUNKIE_AFFILIATE_URL ?>" target="_blank">E-junkie</a> <?php _e('account. Shortcodes can be used in your posts and pages to display the add to cart and checkout buttons.', 'event_espresso'); ?></p>
                  
                  <table width="100%" border="0" cellspacing="10">
  <tr>
    <td valign="top" width="30%">
    
    <p><strong>
      <?php _e('Item ID', 'wpejunkie'); ?>
      </strong><br />					<?php echo $items[0]->item_id =='' ? '<input name="item_id" type="text" id="item_id" value="" />' : '<input disabled type="text" value="' . $items[0]->item_id . '" />'; ?> </p>
      
      <p><strong>
        <?php _e('Product Name', 'wpejunkie'); ?>
        </strong><br />
      <input name="product_name" type="text" id="product_name" value="<?php echo $items[0]->product_name =='' ? '' : $items[0]->product_name; ?>" /></p> <?php echo wpejunkie_usage_info($items[0]->item_id); ?>       </td>
    <td width="70%" align="left" valign="top">
    	<div style="margin-left:30px; width:90%"><p><strong>
      <?php _e('Optional E-junkie HTML', 'wpejunkie'); ?>
      </strong><br />
      <textarea name="html" cols="50" rows="10"><?php echo $items[0]->html =='' ? '' : stripslashes_deep(html_entity_decode($items[0]->html)); ?></textarea></p>
      <p><?php _e('<strong>OPTIONAL:</strong> Paste Add to Cart button codes from E-junkie Admin here, if you want to use that instead of the basic Add to Cart button normally generated for this product. This is ONLY required for products using Variants/Variations or a Suggested Price.', 'event_espresso'); ?></p>
      <p><strong><?php _e('Available Short Tags:', 'event_espresso'); ?></strong> <br />
[button_image], [ejunkie_id], [product_name], [product_description], [item_id]<br />
(<font size="-2"><?php _e('Short tags can be used to replace the default E-junkie buttons, show the product names and descriptions, replace the E-junkie ID and item ID. ', 'wpejunkie'); ?> <a href="http://wpejunkie.com/support/" target="_blank"><?php _e('More Info', 'wpejunkie'); ?> </a>.</font>)</p></div></td>
  </tr>
  </table>
<div id="post-body">
  <div id="post-body-content">

<p><strong>
                    <?php _e('Product Description (optional)', 'wpejunkie'); ?>
                    </strong></p>
<div id="descriptiondivrich" class="postarea">
      <?php
	/*
	This is the editor used by WordPress. It is very very hard to find documentation for this thing, so I pasted everything I could find below.
	param: string $content Textarea content.
	param: string $id Optional, default is 'content'. HTML ID attribute value.
	param: string $prev_id Optional, default is 'title'. HTML ID name for switching back and forth between visual editors.
	param: bool $media_buttons Optional, default is true. Whether to display media buttons.
	param: int $tab_index Optional, default is 2. Tabindex for textarea element.
	*/
	//the_editor($content, $id = 'content', $prev_id = 'title', $media_buttons = true, $tab_index = 2)
	the_editor($items[0]->product_desc =='' ? '' : stripslashes_deep(html_entity_decode($items[0]->product_desc)), $id = 'product_desc', $prev_id = 'title', $media_buttons = true, $tab_index = 3); ?>
      <table id="post-status-info" cellspacing="0">
        <tbody>
          <tr>
            <td id="wp-word-count"></td>
            <td class="autosave-info"><span id="autosave">&nbsp;</span></td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
  </div>
                    
					</li>
				  </ul>
				  <?php echo $update_item == 'true' ? '<input name="unique_id" type="hidden" value="' . $items[0]->unique_id . '">' : ''; ?>
				  <input type="hidden" name="action" <?php echo $update_item == 'true' ? 'value="update"' : 'value="insert"'; ?> />
				  <p>
					<input type="submit" value="<?php _e('Save Changes') ?>" />
				  </p>
				  <p>&nbsp;</p>
				  
				</div>
			  </div>
			</li>
		  </ul>
		</form>
       
	<?php
	
	}
	switch ($_REQUEST['action']){
			case 'update':
				//wpejunkie_item_update();
				wpejunkie_item_update_form(wpejunkie_item_update($_REQUEST['unique_id']));
			break;
			case 'insert':
				//wpejunkie_item_insert();
				wpejunkie_item_update_form(wpejunkie_item_insert());
			break;
			case 'delete':
				wpejunkie_delete_item();
			break;
			default:
				wpejunkie_item_update_form();
			break;
		}
		wpejunkie_item_list();
		echo wpejunkie_admin_links(); ?>
	  </div>
	</div>
    <script type="text/javascript" charset="utf-8">
	function toggleEditor(id) {
	if (!tinyMCE.get(id))
		tinyMCE.execCommand('mceAddControl', false, id);
	else
		tinyMCE.execCommand('mceRemoveControl', false, id);
	}
	jQuery(document).ready(function($) {
		
		var id = 'conf_mail';
		$('a.toggleVisual').click(
			function() {
				tinyMCE.execCommand('mceAddControl', false, id);
			}
		);

		$('a.toggleHTML').click(
			function() {
				tinyMCE.execCommand('mceRemoveControl', false, id);
			}
		);
});
</script>
    <?php
	wpejunkie_display_right_column();
}