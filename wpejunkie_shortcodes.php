<?php
/*Build the e-junkie.com buttons*/

//[EJUNKIE_ADD2CART item="your_item_id"]
function wpejunkie_add2cart($attr) {
	global $wpdb;
	$ejunkie_id = get_option('ejunkie_id');
	$ejunkie_add2cart_image = get_option('ejunkie_add2cart_image') == '' ? 'http://www.e-junkie.com/ej/ej_add_to_cart.gif' : get_option('ejunkie_add2cart_image');
	$default_button = '<a href="https://www.e-junkie.com/ecom/gb.php?c=cart&i=' . $attr['item'] . '&cl=' . get_option('ejunkie_id') . '&ejc=2" target="ej_ejc" class="ec_ejc_thkbx" onClick="javascript:return EJEJC_lc(this);"><img src="' . 	$ejunkie_add2cart_image . '" border="0" alt="' . __('Add to Cart','wpejunkie') . '"/></a>';
	
	$sql = "SELECT * FROM " . WPEJUNKIE_HTML_CODE_TABLE . " WHERE item_id = '" .$attr['item']. "'";
	$wpdb->query($sql);
	
	if ($wpdb->num_rows > 0) {
		$items = $wpdb->get_results($sql);
		if ($items[0]->html != ''){
			foreach ($items as $item){
				$tags = array("[button_image]", "[ejunkie_id]", "[product_name]", "[item_id]", "[product_description]");
				$vals = array($ejunkie_add2cart_image, $ejunkie_id, $item->product_name, $item->item_id, stripslashes_deep(html_entity_decode(wpautop($item->product_desc))) );
				$html = str_replace($tags,$vals,stripslashes_deep(html_entity_decode($item->html)));
				return $html;
			}
		}else{
			return $default_button;
		}
	}else{
		return $default_button;
	}
}
add_shortcode('EJUNKIE_ADD2CART', 'wpejunkie_add2cart');

//[EJUNKIE_VIEWCART]
function wpejunkie_viewcart() {
	$ejunkie_id = get_option('ejunkie_id');
	$ejunkie_viewcart_image = get_option('ejunkie_viewcart_image') == '' ? 'http://www.e-junkie.com/ej/ej_view_cart.gif' : get_option('ejunkie_viewcart_image');
	return '<a href="https://www.e-junkie.com/ecom/gb.php?c=cart&cl=' . get_option('ejunkie_id') . '&ejc=2" target="ej_ejc" class="ec_ejc_thkbx" onClick="javascript:return EJEJC_lc(this);"><img src="' . $ejunkie_viewcart_image . '" border="0" alt="' . __('View Cart','wpejunkie') . '"/></a>';
}
add_shortcode('EJUNKIE_VIEWCART', 'wpejunkie_viewcart');


//[EJUNKIE_ITEMSLIST]
function wpejunkie_itemslist($attr) {
	global $wpdb;
	$ejunkie_id = get_option('ejunkie_id');
	$ejunkie_add2cart_image = get_option('ejunkie_add2cart_image') == '' ? 'http://www.e-junkie.com/ej/ej_add_to_cart.gif' : get_option('ejunkie_add2cart_image');
	$default_button = '<a href="https://www.e-junkie.com/ecom/gb.php?c=cart&i=' . $attr['item'] . '&cl=' . get_option('ejunkie_id') . '&ejc=2" target="ej_ejc" class="ec_ejc_thkbx" onClick="javascript:return EJEJC_lc(this);"><img src="' . 	$ejunkie_add2cart_image . '" border="0" alt="' . __('Add to Cart','wpejunkie') . '"/></a>';
	$ejunkie_viewcart_image = get_option('ejunkie_viewcart_image') == '' ? 'http://www.e-junkie.com/ej/ej_view_cart.gif' : get_option('ejunkie_viewcart_image');
	
	$sql = "SELECT * FROM " . WPEJUNKIE_HTML_CODE_TABLE;
	$wpdb->query($sql);
	
	if ($wpdb->num_rows > 0) {
		$items = $wpdb->get_results($sql);
		foreach ($items as $item){
			$tags = array("[button_image]", "[ejunkie_id]", "[product_name]", "[item_id]", "[product_description]");
			$vals = array($ejunkie_add2cart_image, $ejunkie_id, $item->product_name, $item->item_id, stripslashes_deep(html_entity_decode(wpautop($item->product_desc))) );
			echo '<div class="wpejunkie_button_code" id="wpejunkie_button_code-' . $item->item_id . '">';
			echo str_replace($tags,$vals,stripslashes_deep(html_entity_decode($item->html)));
			echo ' <a href="https://www.e-junkie.com/ecom/gb.php?c=cart&cl=' . get_option('ejunkie_id') . '&ejc=2" target="ej_ejc" class="ec_ejc_thkbx" onClick="javascript:return EJEJC_lc(this);"><img src="' . $ejunkie_viewcart_image . '" border="0" alt="' . __('View Cart','wpejunkie') . '"/></a>';
			echo '</div>';
			}
	}else{
		return 'No items added.';
	}
}
add_shortcode('EJUNKIE_ITEMSLIST', 'wpejunkie_itemslist');