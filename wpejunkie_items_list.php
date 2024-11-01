<?php
//Build the edit items page.
function wpejunkie_items_listings(){
	//Get the items list
	require_once("items_list.php"); 
					
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
		
 <?php
		wpejunkie_item_list();
		echo wpejunkie_admin_links();
		?>
	  </div>
	</div>
    
    <?php
	wpejunkie_display_right_column();
}