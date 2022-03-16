
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php if(isset($_REQUEST['section']) && $_REQUEST['section']=="citieswise"){
	$style="display:block;";
	
}else{
	$style="display:block;";
} 

	global $wpdb;
    if(isset($_POST['submit_form'])){
	  if(isset($_POST['city_rates']) && !empty($_POST['city_rates'])){
		
		 $delete = $wpdb->query("TRUNCATE TABLE `wpo7_cities_shipping_rules`");
		  $counts=count($_POST['city_rates']['shipping_state']);
		 for($i=1; $i<=$counts; $i++){
		$wpdb->insert('wpo7_cities_shipping_rules', array(
		'shipping_state' => $_POST['city_rates']['shipping_state'][$i],
		'shipping_cities' => serialize($_POST['city_rates']['shipping_cities'][$i]),
		'shipping_cost_city' => $_POST['city_rates']['shipping_cost_city'][$i], // ... and so on
	     )); 
		 }
	 }
   }
    if(isset($_POST['submit_form_setting_free_shipping'])){
		update_option( 'free_shipping_enable', $_POST['free_shipping_enable'] );
		update_option( 'free_shipping_min_price', $_POST['free_shipping_min_price'] );
		update_option( 'free_shipping_store_limit', $_POST['free_shipping_store_limit'] );
	}
   
   if(isset($_POST['submit_vendor_text_message_setting'])){
	  
		update_option( 'vendor_phone_text_message_befor_evening', $_POST['vendor_phone_text_message_befor_evening'] );
		update_option( 'message_Setting_', $_POST['message_Setting_'] );
		update_option( 'message_send_to_vendor_by_day', serialize($_POST['message_vendor'] ));
		 
		
	}
   $states = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."state where country_id=%1s order by name asc", '38') );
   $get_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."cities_shipping_rules ") );
 

 ?>
 <form method='post' name="shipping_rates_submit"  >
<div id="custom_shipping_city" style="<?php  echo $style; ?>" >
<h2>Rules</h2>
<p>Custom Shipping Method Rules Setting </p>
<table class="form-table">		
<tbody>
<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woocommerce_citieswise_enabled">Select Country </label>
			</th>
		
			<td class="forminp">
				<fieldset>
				
					<?php	

/* echo "<pre>";
print_r($get_data); */
if(!empty($get_data)){
$total=count($get_data);
	?>
	
		<div class="input_fields_wrap">
		<button class="add_field_button">Add Rules</button>
		<?php  foreach($get_data as $key=> $city_data){
			$new=unserialize($city_data->shipping_cities);
$cities = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."city where state_id=%1s order by name asc", $city_data->shipping_state));
$html="";
foreach($cities  as $selected_city){
	 if(in_array($selected_city->id , $new)){ $newvar= 'selected="selected"'; }else{ $newvar= ''; }
	$html .="<option value='". $selected_city->id ."' $newvar data-id='".$selected_city->id ."'>".$selected_city->name."</option>";
	
}
			?>
		<div><select onchange="getcities(<?php echo $key; ?>)" id="selected_<?php echo $key; ?>"  class="states_list" data-select_id="<?php echo $city_data->id; ?>"  name="city_rates[shipping_state][<?php echo $city_data->id; ?>]" style="width: 25%;" >
		<option value="0">Select State</option>
		<?php foreach($states as $st){  ?>
		<option value="<?php echo $st->id;  ?>" id="<?php echo $st->id;  ?>"  <?php if($st->id==$city_data->shipping_state){ echo 'selected="selected"'; }  ?>><?php echo $st->name;  ?></option>
		<?php }  ?>
		</select>
		<select id="select_<?php echo $key; ?>" class="cities_list chosen_select" name="city_rates[shipping_cities][<?php echo $city_data->id; ?>][]" style="width: 25%;"  multiple>
		<option value="0">Select City</option>
		<?php echo $html;  ?>
		</select>
		<script>
		jQuery(document).ready(function() {
    jQuery('#select_<?php echo $key;  ?>').select2();
});</script>
		$<input  class="input-text" type="text" name="city_rates[shipping_cost_city][<?php echo $city_data->id; ?>]" id="shipping_cost_city" value="<?php  echo $city_data->shipping_cost_city;  ?>" style="width: 25%;"  ><a href="javascript:void(0)" class="remove_rule" onclick="remove_rule(<?php echo $city_data->id; ?>);">Remove Rule</a></div>
<?php }  ?>
		</div>
<?php  }else{ 
$total=1;
 ?>
	<div class="input_fields_wrap">
		<button class="add_field_button">Add Rules</button>
		<div><select onchange="getcities(1)" id="selected_1"  class="states_list" data-select_id="1"  name="city_rates[shipping_state][1]" style="width: 25%;" >
		<option value="0">Select State</option>
		<?php foreach($states as $st){  ?>
		<option value="<?php echo $st->id;  ?>" id="<?php echo $st->id;  ?>"  ><?php echo $st->name;  ?></option>
		<?php }  ?>
		</select>
		<select id="select_1" class="cities_list" name="city_rates[shipping_cities][1][]" style="width: 25%;"  multiple>
		<option value="0">Select City</option>
		</select>
		$<input  class="input-text" type="text" name="city_rates[shipping_cost_city][1]" id="shipping_cost_city" style="width: 25%;"  ></div>
		</div>
<?php } ?>
									
									</fieldset>
			</td>
		</tr>
				
		</tbody></table> 
		<button type="submit" name="submit_form" class="btn btn-primary">Submit</button>
</div>
</form>
<?php
//$no_exists_value = get_option( 'no_exists_value' );
 $free_shipping_enable=get_option( 'free_shipping_enable');
$free_shipping_min_price=get_option( 'free_shipping_min_price');
 $free_shipping_store_limit= get_option( 'free_shipping_store_limit' );
?>
 <form method='post' name="custom_free_shipping_setting"  >
<div id="custom_shipping_city" style="<?php  echo $style; ?>" >
<h2>Free Shipping Rules Setting</h2>
<p></p>
<table class="form-table">		
<tbody>
<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woocommerce_citieswise_enabled">Free Shipping Enable: </label>
			</th>
		
			<td class="forminp">
				<input  class="input-text" type="checkbox" <?php if($free_shipping_enable=="on"){ echo 'checked="checked"';} ?> name="free_shipping_enable"  >
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woocommerce_citieswise_enabled">Minimum price: </label>
			</th>
		
			<td class="forminp">
			
				<input  class="input-text" type="text" value="<?php  echo $free_shipping_min_price; ?>" name="free_shipping_min_price">
			</td>
		</tr>
<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woocommerce_citieswise_enabled">Store Limit: </label>
			</th>
		
			<td class="forminp">
				<input  class="input-text" type="text" value="<?php  echo $free_shipping_store_limit; ?>" name="free_shipping_store_limit"  >
			</td>
		</tr>			
		</tbody></table> 
		<button type="submit" name="submit_form_setting_free_shipping" class="btn btn-primary">Submit</button>
</div>
</form>

<?php
//$no_exists_value = get_option( 'no_exists_value' );
 $vendor_phone_text_message_befor_evening=get_option( 'vendor_phone_text_message_befor_evening');
  $message_Setting_=get_option( 'message_Setting_');
 $message_send_to_vendor_by_day=unserialize(get_option( 'message_send_to_vendor_by_day'));

if(empty($vendor_phone_text_message_befor_evening)){
 $message_vendor="Incoming! A new Eat4Later Order has been placed! Click the link %url_confirm% to confirm the order. You can also click the following link to view the order details from your vendor account login on eat4later.com %order_url%";
}else{
	$message_vendor=$vendor_phone_text_message_befor_evening;
}
?>
<form method='post' name="vendor_text_message_setting"  >
<div id="custom_shipping_city" style="<?php  echo $style; ?>" >
<h2>Vendor Text Message Setting</h2>
<p></p>
<table class="form-table">		
<tbody>

		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woocommerce_citieswise_enabled">Slot 1: </label>
				<label>Enable</label>
		<input type="radio" name="message_Setting_" <?php if($message_Setting_=="day_select"){ echo 'checked="checked"' ; }   ?> value="day_select" />
			</th>
	
			<td class="forminp">
			 <div class="form-group "> 
			 <p>Start Day:</p>
            <select name="message_vendor[1][start][day]" >
			<option  value="">--Select Day--</option>
			<option <?php if($message_send_to_vendor_by_day[1]['start']['day']=="Sunday"){ echo 'selected="selected"'; } ?> value="Sunday">Sunday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['start']['day']=="Monday"){ echo 'selected="selected"'; } ?> value="Monday">Monday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['start']['day']=="Tuesday"){ echo 'selected="selected"'; } ?> value="Tuesday">Tuesday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['start']['day']=="Wednesday"){ echo 'selected="selected"'; } ?> value="Wednesday">Wednesday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['start']['day']=="Thursday"){ echo 'selected="selected"'; } ?> value="Thursday">Thursday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['start']['day']=="Friday"){ echo 'selected="selected"'; } ?> value="Friday">Friday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['start']['day']=="Saturday"){ echo 'selected="selected"'; } ?> value="Saturday">Saturday</option>
			</select>
         <input type="time" value="<?php echo $message_send_to_vendor_by_day[1]['start']['time'];   ?>" autocomplete="off"  name="message_vendor[1][start][time]" class="form-control" placeholder="End Date"/>
          
		<p>End Day:</p>
           <select name="message_vendor[1][end][day]" >
			<option  value="">--Select Day--</option>
			<option <?php if($message_send_to_vendor_by_day[1]['end']['day']=="Sunday"){ echo 'selected="selected"'; } ?> value="Sunday">Sunday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['end']['day']=="Monday"){ echo 'selected="selected"'; } ?> value="Monday">Monday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['end']['day']=="Tuesday"){ echo 'selected="selected"'; } ?> value="Tuesday">Tuesday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['end']['day']=="Wednesday"){ echo 'selected="selected"'; } ?> value="Wednesday">Wednesday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['end']['day']=="Thursday"){ echo 'selected="selected"'; } ?> value="Thursday">Thursday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['end']['day']=="Friday"){ echo 'selected="selected"'; } ?> value="Friday">Friday</option>
			<option <?php if($message_send_to_vendor_by_day[1]['end']['day']=="Saturday"){ echo 'selected="selected"'; } ?> value="Saturday">Saturday</option>
			</select>
         <input type="time" value="<?php echo $message_send_to_vendor_by_day[1]['end']['time'];   ?>" autocomplete="off"  name="message_vendor[1][end][time]" class="form-control" placeholder="End Date"/>
        </div>
		<p>Message text:</p>
			<textarea style="width: 100%;" name="message_vendor[1][vendor_phone_text]"><?php echo $message_send_to_vendor_by_day[1]['vendor_phone_text'];  ?></textarea>
			<p style="font-style: italic;"><b>Note:</b></br>1. Ignore removing vendor confirm a url variable [ %url_confirm% ]  .</br>2. Ignore removing vendor view order detail url variable [ %order_url% ].</p>
				
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woocommerce_citieswise_enabled">Slot 2: </label>
			</th>
		
			<td class="forminp">
			 <div class="form-group "> 
			 <p>Start Day:</p>
            <select name="message_vendor[2][start][day]" >
			<option  value="">--Select Day--</option>
			<option <?php if($message_send_to_vendor_by_day[2]['start']['day']=="Sunday"){ echo 'selected="selected"'; } ?> value="Sunday">Sunday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['start']['day']=="Monday"){ echo 'selected="selected"'; } ?> value="Monday">Monday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['start']['day']=="Tuesday"){ echo 'selected="selected"'; } ?> value="Tuesday">Tuesday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['start']['day']=="Wednesday"){ echo 'selected="selected"'; } ?> value="Wednesday">Wednesday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['start']['day']=="Thursday"){ echo 'selected="selected"'; } ?> value="Thursday">Thursday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['start']['day']=="Friday"){ echo 'selected="selected"'; } ?> value="Friday">Friday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['start']['day']=="Saturday"){ echo 'selected="selected"'; } ?> value="Saturday">Saturday</option>
			</select>
         <input type="time" value="<?php echo $message_send_to_vendor_by_day[2]['start']['time'];   ?>" autocomplete="off"  name="message_vendor[2][start][time]" class="form-control" placeholder="End Date"/>
          
		<p>End Day:</p>
            <select name="message_vendor[2][end][day]" >
			<option  value="">--Select Day--</option>
			<option <?php if($message_send_to_vendor_by_day[2]['end']['day']=="Sunday"){ echo 'selected="selected"'; } ?> value="Sunday">Sunday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['end']['day']=="Monday"){ echo 'selected="selected"'; } ?> value="Monday">Monday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['end']['day']=="Tuesday"){ echo 'selected="selected"'; } ?> value="Tuesday">Tuesday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['end']['day']=="Wednesday"){ echo 'selected="selected"'; } ?> value="Wednesday">Wednesday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['end']['day']=="Thursday"){ echo 'selected="selected"'; } ?> value="Thursday">Thursday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['end']['day']=="Friday"){ echo 'selected="selected"'; } ?> value="Friday">Friday</option>
			<option <?php if($message_send_to_vendor_by_day[2]['end']['day']=="Saturday"){ echo 'selected="selected"'; } ?> value="Saturday">Saturday</option>
			</select>
         <input type="time" value="<?php echo $message_send_to_vendor_by_day[2]['end']['time'];   ?>" autocomplete="off"  name="message_vendor[2][end][time]" class="form-control" placeholder="End Date"/>
        </div>
		<p>Message text:</p>
			<textarea style="width: 100%;" name="message_vendor[2][vendor_phone_text]"><?php echo $message_vendor;  ?></textarea>
			<p style="font-style: italic;"><b>Note:</b></br>1. Ignore removing vendor confirm a url variable [ %url_confirm% ]  .</br>2. Ignore removing vendor view order detail url variable [ %order_url% ].</p>
				
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woocommerce_citieswise_enabled">Default message: </label>
				<label>Enable</label>
		<input type="radio" name="message_Setting_" <?php if($message_Setting_=="default_select"){ echo 'checked="checked"' ; }   ?> value="default_select" />
			</th>
		
			<td class="forminp">
			 
         <p>Message text:</p>
			<textarea style="width: 100%;" name="vendor_phone_text_message_befor_evening"><?php echo $message_send_to_vendor_by_day[2]['vendor_phone_text'];  ?></textarea>
			<p style="font-style: italic;"><b>Note:</b></br>1. Ignore removing vendor confirm a url variable [ %url_confirm% ]  .</br>2. Ignore removing vendor view order detail url variable [ %order_url% ].</p>
				
			</td>
		</tr>
		</tbody></table> 
		<button type="submit" name="submit_vendor_text_message_setting" class="btn btn-primary">Submit</button>
</div>
</form>

<script>
jQuery(function($){

/* --------------------- GET CITIES ------------------------ */	
$(document).ready(function() {
	var max_fields      = 10; //maximum input boxes allowed
	var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
	var add_button      = $(".add_field_button"); //Add button ID
	
	var x = <?php echo $total; ?>; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
		e.preventDefault();
		
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			$(wrapper).append(' <div><select onchange="getcities('+x+')" id="selected_'+x+'"  class="states_list" data-select_id="'+x+'"  name="city_rates[shipping_state]['+x+']" style="width: 25%;" >				<option value="0">Select State</option><?php foreach($states as $st){  ?><option id="<?php echo $st->id;  ?>" value="<?php echo $st->id;  ?>" ><?php echo $st->name;  ?></option><?php }  ?></select><select id="select_'+x+'" class="cities_list chosen-select" name="city_rates[shipping_cities]['+x+'][]" style="width: 25%;"  multiple><option value="0">Select City</option></select>$<input  class="input-text" type="text" name="city_rates[shipping_cost_city]['+x+']" id="shipping_cost_city" style="width: 25%;"  ><a  href="#" class="remove_rule remove_field">Remove Rule</a></div>'); //add input box
			$(".chosen-select").select2({
  no_results_text: "Oops, nothing found!"
}); 
		}
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--;
	})
});
/* $(".states_list").change(function(){
if($(this).length>0)
{	
var sid=$(this).children("option:selected").attr('id');
var select_id=$(this).attr('data-select_id');
alert(select_id);
$("select.cities_list").html('<option value="0">Select City</option>');	
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
 jQuery.ajax({
            url : ajaxurl,
            type : 'post',
	        dataType : "json",
            data : {action: "custom_shipping_get_cities",sid:sid},
            success : function( response ) {
				for(i=0;i<response.length;i++)
					{
				var ct_id=response[i]['id'];
				var ct_name=response[i]['name'];
				var opt="<option value='"+ct_id+"' data-id='"+ct_id+"'>"+ct_name+"</option>";
						
				$("#select_"+select_id).append(opt);	
				
					}
            }
        });
}
});	 */

	
});
function remove_rule(table_id){
	if (confirm("Are you sure you want to delete rule?")) {
       jQuery.ajax({
            url : ajaxurl,
            type : 'post',
	        dataType : "json",
            data : {action: "custom_shipping_rule_delete",table_id:table_id},
            success : function( response ) {
				
				if(response==1){
					location.reload();
				}
            }
        });
    }
    return false;
	
	
}
function getcities(id){
	if(jQuery("#selected_"+id).length>0)
{	
var sid=jQuery("#selected_"+id).children("option:selected").attr('id');
var select_id=jQuery("#selected_"+id).attr('data-select_id');

jQuery("#select_"+select_id).html('<option value="0">Select City</option>');	
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
 jQuery.ajax({
            url : ajaxurl,
            type : 'post',
	        dataType : "json",
            data : {action: "custom_shipping_get_cities",sid:sid},
            success : function( response ) {
				for(i=0;i<response.length;i++)
					{
				var ct_id=response[i]['id'];
				var ct_name=response[i]['name'];
				var opt="<option value='"+ct_id+"' data-id='"+ct_id+"'>"+ct_name+"</option>";
						
				jQuery("#select_"+select_id).append(opt);	
				
					}
            }
        });
}
}
</script>