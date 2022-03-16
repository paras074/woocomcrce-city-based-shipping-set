<?php
 
 $key = 'cus_vendor_notification_sms'; 
     $single = true; 
 
   
   $vendor = get_users( array( 'role__in' => array( 'wcfm_vendor' ) ) );
		// echo "<pre>";
if(isset($_POST['action_notification_vendors'])){

$key="cus_vendor_notification_sms";
$value=$_POST['cus_noti_vendor'];
 
foreach($vendor as $key_user=>$val){
	if(isset($value[$val->ID])){
		update_user_meta( $val->ID, $key, $value[$val->ID] );
	}else{
		update_user_meta( $val->ID, $key, 'off' );
	}
	
	//update_user_meta( $key_user, $key, $val );
}
echo "Submit success fully";
 // update_user_meta( $user_id, $key, $value );
}

		//print_r($vendor);
		//echo "</pre>"; 
// Array of WP_User objects.

    
?>
<section class="">
	
	<div class="cus_vendor_noti"><h3>Admin Notification Manager For Vendors</h3><p>  New Order of customer, you can enable and disable for sms of order detail</p>

	<form method="post">
 <?php  foreach ( $vendor as $user ) {
$user_last = get_user_meta( $user->ID, $key, $single ); 
	 ?>
  <p><input type="checkbox"  name="cus_noti_vendor[<?php echo  $user->ID ; ?>]"  <?php  if($user_last=="on"){ echo 'checked'; } ?>/> <span class="noti_label"><?php echo esc_html( $user->display_name ); ?> </span></p>
 <?php }  ?>
  <div class="submit_noti"><input type="submit" style="margin: 20px 0 0 0;" name="action_notification_vendors" /></div>
  </form></div> 
</section>

