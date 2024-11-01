<?php
function wpejunkie_item_list(){
	global $wpdb;
?>
<div class="clear_both"></div>
<ul id="wpejunkie-sortables">
			<li>
<div class="box-mid-head">
  <h2 class="fugue f-wrench">
    <?php _e('Current Items','wpejunkie'); ?>
    <a name="cart_items" id="cart_items"></a>  </h2>
</div>

<div class="box-mid-body" id="toggle2">
  <div class="padding">
    <div style="float:right; margin:10px 20px;"> <a class="button-primary" href="admin.php?page=wpejunkie&amp;action=add_new_item">
      <?php _e('Add New Item','wpejunkie'); ?>
    </a> </div>
    <div id="tablewrapper">
      <div id="tableheader">
        <div class="search">
          <select id="columns" onchange="sorter.search('query')">
          </select>
          <input type="text" id="query" onkeyup="sorter.search('query')" />
        </div>
        <span class="details">
          <div>
            <?php _e('Records', 'event_espresso'); ?>
            <span id="startrecord"></span>-<span id="endrecord"></span>
            <?php _e('of', 'event_espresso'); ?>
            <span id="totalrecords"></span></div>
          <div><a href="javascript:sorter.reset()">
            <?php _e('reset', 'event_espresso'); ?>
          </a></div>
        </span> </div>
      <table id="table" class="tinytable" width="65%">
        <thead>
          <tr>
            <th valign="top"><h3>
              <?php _e('Item ID','wpejunkie'); ?>
            </h3></th>
            <th valign="top"><h3>
              <?php _e('Product Name','wpejunkie'); ?>
            </h3></th>
            <th valign="top"><h3>
              <?php _e('Add to Cart Button','wpejunkie'); ?>
            </h3></th>
          </tr>
        </thead>
        <tbody>
          <?php 		
		$sql = "SELECT * FROM ". WPEJUNKIE_HTML_CODE_TABLE . "  ORDER BY id  ASC";
		$results = $wpdb->get_results($sql);
		if ($wpdb->num_rows > 0) {
			
				foreach ($results as $result){
					$item_id= stripslashes_deep($result->item_id);
					$product_name=stripslashes_deep($result->product_name);
					$unique_id = stripslashes_deep($result->unique_id);
?>
          <tr>
            <td valign="top"><a href="admin.php?page=wpejunkie&action=edit&unique_id=<?php echo $unique_id?>"><?php echo $item_id?></a><br />
              <div class="row-actions"><span class='edit'><a href="admin.php?page=wpejunkie&action=edit&unique_id=<?php echo $unique_id?>">Edit</a> | </span><span class='delete'><a onclick="return confirmDelete();" href='admin.php?page=wpejunkie&amp;action=delete&amp;unique_id=<?php echo $unique_id?>'>Delete</a></span></div></td>
            <td valign="top"><?php echo $product_name?><br /></td>
            <td><span style="font-size:10px; padding:5px; background:#FFC; border:solid 1px #0C0">[EJUNKIE_ADD2CART item="<?php echo $item_id ?>"]</span></td>
          </tr>
          <?php } 
		}else { ?>
          <tr>
            <td><?php _e('No Record Found!','wpejunkie'); ?></td>
          </tr>
          <?php	}?>
        </tbody>
      </table>
      <div id="tablefooter">
        <div id="tablenav">
          <div> <img src="<?php echo WPEJUNKIE_PLUGINFULLURL?>images/first.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" /> <img src="<?php echo WPEJUNKIE_PLUGINFULLURL?>images/previous.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" /> <img src="<?php echo WPEJUNKIE_PLUGINFULLURL?>images/next.gif" width="16" height="16" alt="First Page" onclick="sorter.move(1)" /> <img src="<?php echo WPEJUNKIE_PLUGINFULLURL?>images/last.gif" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" /> </div>
          <div>
            <select id="pagedropdown">
            </select>
          </div>
          <div> <a href="javascript:sorter.showall()">view all</a> </div>
        </div>
        <div id="tablelocation">
          <div>
            <select onchange="sorter.size(this.value)">
              <option value="5">5</option>
              <option value="10" selected="selected">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <span>Entries Per Page</span> </div>
          <div class="page">Page <span id="currentpage"></span> of <span id="totalpages"></span></div>
        </div>
      </div>
    </div>
  </div>
</div>
</li>
</ul>

<script type="text/javascript">
	var sorter = new TINY.table.sorter('sorter','table',{
		headclass:'head',
		ascclass:'asc',
		descclass:'desc',
		evenclass:'evenrow',
		oddclass:'oddrow',
		evenselclass:'evenselected',
		oddselclass:'oddselected',
		paginate:true,
		size:30,
		colddid:'columns',
		currentid:'currentpage',
		totalid:'totalpages',
		startingrecid:'startrecord',
		endingrecid:'endrecord',
		totalrecid:'totalrecords',
		hoverid:'selectedrow',
		pageddid:'pagedropdown',
		navid:'tablenav',
		sortcolumn:2,
		sortdir:1,
		//sum:[8],
		//avg:[6,7,8,9],
		//columns:[{index:7, format:'%', decimals:1},{index:8, format:'$', decimals:0}],
		init:true
	});
	
  </script>
  <?php
  }