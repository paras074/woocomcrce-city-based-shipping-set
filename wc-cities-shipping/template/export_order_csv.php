<?php global $wpdb;
    $table_name=$wpdb->base_prefix.'cities_shipping_plugin_setting';
    if(isset($_POST['header_column_edit'])){
	  $arr=$_POST;
	  unset($arr['header_column_edit']); 
	  $header_col=serialize($arr);
	  $wpdb->query($wpdb->prepare("UPDATE $table_name SET value='$header_col' WHERE type='header_column'"));
	}
	$query = new WC_Order_Query( array(
	'orderby' => 'ids',
   	'posts_per_page' => '-1'
    ));
	$orders = $query->get_orders();
	$header_col = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."cities_shipping_plugin_setting where type='header_column'") );
	 $serial=$header_col[0]->value;
	$header_columns=unserialize($serial);
	
	
 ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.1/css/dataTables.dateTime.min.css">
 	<div class="container">
  <div class="row">
 
		<div class="col-lg-12">
			<div id="main_datatable_table" class="panel panel-default">
                <div class="panel-heading">
                    <h4 style="display: inline-block;">Orders List</h4>
                   
                </div>
				<div>
				<a id="header_column_edit" href="javacript:void(0)">column change</a>
				<form method="post">
				<div style="display:none !important;" id="div_header_column_edit">
				<p><input type="text" name="col-0" value="<?php if(!empty($header_columns['col-0'])){ echo $header_columns['col-0']; }else{ echo "Order Number"; }  ?>"   /><p>
				<p><input type="text" name="col-1" value="<?php if(!empty($header_columns['col-1'])){ echo $header_columns['col-1']; }else{ echo "Order Date"; }  ?>"   /></p>
				<p><input type="text" name="col-2" value="<?php if(!empty($header_columns['col-2'])){ echo $header_columns['col-2']; }else{ echo "OrderVendor"; }  ?>"   /></p>
				<p><input type="text" name="col-3" value="<?php if(!empty($header_columns['col-3'])){ echo $header_columns['col-3']; }else{ echo "StoreName"; }  ?>"   /></p>
				<p><input type="text" name="col-4" value="<?php if(!empty($header_columns['col-4'])){ echo $header_columns['col-4']; }else{ echo "Address"; }  ?>"   /></p>
				<p><input type="text" name="col-5" value="<?php if(!empty($header_columns['col-5'])){ echo $header_columns['col-5']; }else{ echo "Email"; }  ?>"   /></p>
				<p><input type="text" name="col-6" value="<?php if(!empty($header_columns['col-6'])){ echo $header_columns['col-6']; }else{ echo "Phone"; }  ?>"   /></p>
				<p><input type="text" name="col-7" value="<?php if(!empty($header_columns['col-7'])){ echo $header_columns['col-7']; }else{ echo "Notes"; }  ?>"   /></p>
				<p><input type="text" name="col-8" value="<?php if(!empty($header_columns['col-8'])){ echo $header_columns['col-8']; }else{ echo "Business"; }  ?>"   /></p>
				<p><input type="submit" name="header_column_edit" /></p>
				</div>
				</form>
				</div>
                <div id="datatable_table" class="panel-body">
					<table class="custom_filter" border="0" cellspacing="5" cellpadding="5">
						<tbody>
						    <tr>
								<td>Minimum date:</td>
								<td><input type="text" id="min" name="min"></td>
							</tr>
							<tr>
								<td>Maximum date:</td>
								<td><input type="text" id="max" name="max"></td>
							</tr>
						</tbody>
					</table>
                    <table id="export_csv_table" class="table table-bordered">
                        <thead>
                            <tr>
							<?php if(!empty($header_columns)){
								
							 foreach($header_columns as $ke=>$head_col){

								 ?>
                                <th class="<?php echo $ke; ?>"><?php echo $head_col; ?></th>
                                
							<?php }  
							   }else{
								   							 ?>
							    <th>Order Number</th>
                                <th>Order Date</th>
                                <th>OrderVendor</th>
                                <th>StoreName</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
								<th>Notes</th>
								<th>Business</th>
							<?php }  ?>
                            </tr>
                        </thead>
                        <tbody>
						
						<?php  
foreach($orders as $order){

	$order_id  = $order->get_id(); // Get the order ID
	$parent_id = $order->get_parent_id(); // Get the parent order ID (for subscriptions…)
if($parent_id=="0"){
	$user_id   = $order->get_user_id(); // Get the costumer ID
	$user      = $order->get_user(); // Get the WP_User object
	$order_status  = $order->get_status(); // Get the order status 
	//$currency      = $order->get_currency(); // Get the currency used  
	//$payment_method = $order->get_payment_method(); // Get the payment method ID
	//$payment_title = $order->get_payment_method_title(); // Get the payment method title
	$date_created  = $order->get_date_created(); // Get date created (WC_DateTime object)
	//$date_create= get_the_time(__('Y/m/d G:i', 'woocommerce'), $order);
	$date_created= gmdate( 'Y-m-d H:i',$order->get_date_created()->getOffsetTimestamp() );
	//$date_created= gmdate( 'Y-m-d',$order->get_date_created()->getOffsetTimestamp() );
	$date_modified = $order->get_date_modified(); // Get date modified (WC_DateTime object)
	$billing_country = $order->get_billing_country(); // Customer billing country
    $billing_mail= $order->get_billing_email();	
    $billing_phone= $order->get_billing_phone();;	
	 // Get and Loop Over Order Items
	$shipping_address=$order->get_formatted_shipping_address();
	$items=$order->get_items();
	$store_data=array(); 
	foreach($items as $item){
		 $product = $item->get_product();
       
        // Author id
        $author_id = $product->post->post_author;
        
        // Shopname
        //$vendor = dokan()->vendor->get( $author_id );
		$vendor = new WP_User($author_id);
		
		$shop_name = $vendor->get_shop_name();
		$vendor_data = get_user_meta( $author_id, 'wcfmmp_profile_settings', true );
		$store_name = isset( $vendor_data['store_name'] ) ? $vendor_data['store_name'] : '';
		$store_data[$store_name]['author_id'] = $author_id;
		$store_data[$store_name]['vendor_email'] = $vendor_data['store_email'];
		
		$store_data[$store_name]['store_name'] = $store_name;
		$store_data[$store_name]['vendor_phone'] = $vendor_data['phone'];
		$street_1= $vendor_data['address']['street_1'];
		$street_2= $vendor_data['address']['street_2'];
		$city= $vendor_data['address']['city'];
		$zip= $vendor_data['address']['zip'];
		$country= $vendor_data['address']['country'];
		$state= $vendor_data['address']['state'];

  $full_add=$street_1." ".$street_2." ".$city." ".$state.",".$zip.",".$country;
 $key = 'cus_vendor_delivery_notes'; 
 $store_data[$store_name]['full_add'] = $full_add;
     $single = true; 
   $delivery_notes = get_user_meta( $author_id, $key, $single ); 
	 $store_data[$store_name]['delivery_notes'] = $delivery_notes;	
	 } 
foreach($store_data as $store_list){
		if(!empty($store_list['store_name'])){
?>
   <tr>
                                    <td><?php echo $order_id;  ?></td>
                                    <td><?php echo $date_created; ?></td>
                                    <td><?php echo $store_list['author_id']; ?></td>
                                    <td><?php echo $store_list['store_name'];  ?></td>
                                    <td><?php echo $store_list['full_add'];  ?></td>
                                    <td><?php echo $store_list['vendor_email'];   ?></td>
                                    <td><?php echo $store_list['vendor_phone'];   ?></td>
                                    <td><?php  if(!empty($store_list['delivery_notes'])){ echo $store_list['delivery_notes']; }   ?></td>
                                    <td>Eat4Later</td>
                                </tr>
                                                  
	<?php }}  ?>
		 <tr style="font-weight: bold;">
                                    <td><?php echo $order_id;  ?></td>
                                    <td><?php echo $date_created; ?></td>
                                    <td><?php //echo $user_id; ?></td>
                                    <td><?php echo $user->display_name;  ?></td>
                                    <td><?php echo $shipping_address;  ?></td>
                                     <td><?php echo $billing_mail;   ?></td>
                                    <td><?php echo $billing_phone;   ?></td>
                                    <td><?php echo $order->get_customer_note();   ?></td>
									<td>Eat4Later</th>
                                </tr>  
		  
<?php 		  
}
 } ?>                                               
                                                    </tbody>
                    </table>
                </div>
            </div>
        </div>

</div>
</div>
</div>

<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
	
<script>

var minDate, maxDate;
// Custom filtering function which will search data in column four between two values
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = minDate.val();
        var max = maxDate.val();
        var date = new Date( data[1] );
 
        if (
            ( min === null && max === null ) ||
            ( min === null && date <= max ) ||
            ( min <= date   && max === null ) ||
            ( min <= date   && date <= max )
        ) {
            return true;
        }
        return false;
    }
);
$(document).ready(function() {
	
	$( "#header_column_edit" ).on( "click", function() {
		$( "#div_header_column_edit" ).slideToggle();
	});
	 minDate = new DateTime($('#min'), {
        format: 'MMMM Do YYYY HH:mm'
    });
    maxDate = new DateTime($('#max'), {
        format: 'MMMM Do YYYY HH:mm'
    });
   var table = $('#export_csv_table').DataTable( {
	   
        dom: 'lBfrtip',
		lengthMenu: [[10,20,30,40, 50, -1], [10,20,30,40, 50, "All"]],
		//lengthMenu: [ 10, 20, 30, 40, 50 ],    'csv', 'excel', 'pdf', 'print'     
       	order: [[ 0, "desc" ]],
        buttons: [
             'csv'
        ],
		
    } );
	// Refilter the table
    $('#min, #max').on('change', function () {
        table.draw();
    });
} );
</script>