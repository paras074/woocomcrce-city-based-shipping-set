<?php
/**
 * Plugin Name: WC Cities Shipping
 * Plugin URI:  https://perfectwebservices.com/
 * Description: City based shipping for WooCommerce.
 * Version:     1.0.6
 * Author:      Perfect Web Services
 * WC tested up to:      5.5
 */

/**
 * Check if WooCommerce is active
 */
 if ( ! defined( "CUSTOM_SHIPPING_CITY_DIR_PATH" ) ) {
    define( "CUSTOM_SHIPPING_CITY_DIR_PATH", plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CUSTOM_SHIPPING_CITY_DIR_URL' ) ) {
    define( 'CUSTOM_SHIPPING_CITY_DIR_URL', plugin_dir_url( __FILE__ ) );
}
 function cb_create_admin_menu(){
    add_menu_page( "Custom Shipping City", "Custom Shipping City", 'read', 'custom_shipping_city', '', plugins_url( 'images/icons.png',  __FILE__ ) );
   add_submenu_page( 'custom_shipping_city', "Setting", "Setting", 'read', 'custom_shipping_city', 'custom_shipping_city');
   add_submenu_page( 'custom_shipping_city', 'Export Orders CSV', 'Export Orders CSV','read', 'export_order_csv','export_order_csv');
   //add_submenu_page( 'custom_shipping_city', 'Export Commission Payouts', 'Export Commission Payouts','read', 'export_commission_payouts_csv','export_commission_payouts_csv');
   add_submenu_page( 'custom_shipping_city', 'CSV Scheduled Jobs', 'CSV Scheduled Jobs','read', 'csv_scheduled_jobs','csv_scheduled_jobs');
   add_submenu_page( 'custom_shipping_city', 'Vendor Notification Setting', 'Vendor Notification Setting','read', 'vendor_notification_setting','vendor_notification_setting');
   

 }
 add_action("admin_menu","cb_create_admin_menu");
 add_action("admin_init","cb_backend");
 function cb_backend(){
   wp_enqueue_style( 'shipping-style', CUSTOM_SHIPPING_CITY_DIR_URL . 'css/shipping-style.css' );
	}
function custom_shipping_city(){
    
    global $wpdb;
    if ( file_exists( CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/additional-charges-cities.php' ) ) {
        include CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/additional-charges-cities.php';
    }
 }
 function export_order_csv(){
    
    global $wpdb;
    if ( file_exists( CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/export_order_csv.php' ) ) {
        include CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/export_order_csv.php';
    }
 }
 function csv_scheduled_jobs(){
    
    global $wpdb;
    if ( file_exists( CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/csv_scheduled_jobs.php' ) ) {
        include CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/csv_scheduled_jobs.php';
    }
 }
 function export_commission_payouts_csv(){
    
    global $wpdb;
    if ( file_exists( CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/export_commission_payouts_csv.php' ) ) {
        //include CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/export_commission_payouts_csv.php';
    }
 }
 function vendor_notification_setting(){
    
    global $wpdb;
    if ( file_exists( CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/vendor_notification_setting.php' ) ) {
        include CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/vendor_notification_setting.php';
    }
 }
 function plugin_activate_custom_entry() {
    require_once  ABSPATH."wp-admin/includes/upgrade.php"; 
    global $wpdb;
	$getoption = get_option("shipping_city_insert_data");
	
}
register_activation_hook( __FILE__, 'plugin_activate_custom_entry' );

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	function citieswise_shipping_method_init() {
		if ( ! class_exists( 'Citieswise_Shipping_Method' ) ) {
			class Citieswise_Shipping_Method extends WC_Shipping_Method {
				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                 = 'citieswise'; // Id for your shipping method. Should be uunique.
					$this->method_title       = __( 'Cities Shipping' );  // Title shown in admin
					$this->method_description = __( 'Custom Shipping Method for cities based' ); // Description shown in admin
					$this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
					$this->title = isset($this->settings['title']) ? $this->settings['title'] : __('Cities Shipping', 'citieswise');
					//$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
					//$this->title              = "Cities Shipping"; // This can be added as an setting but for this example its forced.

					$this->init();
				}

				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init() {
					// Load the settings API
					$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
					$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

					// Save settings in admin if you have any defined
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}
				function init_form_fields()
				 {
					 $this->form_fields = array(
						 'enabled' => array(
						 'title' => __('Enable', 'citieswise'),
						 'type' => 'checkbox',
						 'default' => 'yes'
						 ),
						 'title' => array(
						 'title' => __('Title', 'citieswise'),
						 'type' => 'text',
						 'default' => __('Cities Shipping', 'citieswise')
						 ),
					 );
					//require_once 'template/additional-charges-cities.php';
				 }
								/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
				public function calculate_shipping( $package=null ) {
					global $wpdb ,$woocommerce;
					
					//echo $this->settings['enabled'];
					if($this->settings['enabled']=='yes'){
					$coupon_code=$package['applied_coupons'][0];
					/*-----delevery user location detail------*/
				 	$delver_user_city='Calgary';
				 	//$delver_user_city1='Calgary';
					/*-----delevery user location detail end------*/
					
					/*-----current user location detail------*/
					$ct_u_country = $package["destination"]["country"];
					$ct_u_state = $package["destination"]["state"];
				 	$ct_u_city = $package["destination"]["city"];
					/*-----current user location detail end ------*/
					
				/*======Delevery boy City Cost==========*/		
				$selected_city = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."city where name='$delver_user_city' " ) );
				
				$state_id=$selected_city->state_id;
				$d_city_id=$selected_city->id;
				/*======Delevery boy City  Cost==========*/	


				/*======Customer City  Cost==========*/		
				$c_selected_city = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."city where name='$ct_u_city' " ) );
				 $c_city_id=$c_selected_city->id;
				/*======Customer City  Cost==========*/					
				$get_rule = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."cities_shipping_rules where shipping_state='$state_id'") );
$total_vendor_city=array();
$rules_cost=array();
foreach($get_rule as $rules){
	$new=unserialize($rules->shipping_cities);
	foreach($new as $n){
		$rules_cost[$n]=$rules->shipping_cost_city;	
	}
	
}
	
global $wp_session;
$wp_session['citieswise_text']="";	
/* if(isset($rules_cost[$c_city_id])){
	
} */
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item1) {
		$post_obj    = get_post( $cart_item1['product_id'] ); // The WP_Post object
		$user_id = $post_obj->post_author; // <=== The post author ID
		$vendor_data = get_user_meta( $user_id, 'wcfmmp_profile_settings', true );
		
		//$street_1 = isset( $vendor_data['address']['street_1'] ) ? $vendor_data['address']['street_1'] : '';
		//$street_2 = isset( $vendor_data['address']['street_2'] ) ? $vendor_data['address']['street_2'] : '';
		$store_name     = isset( $vendor_data['store_name'] ) ? $vendor_data['store_name'] : '';
		$vendor_city     = isset( $vendor_data['address']['city'] ) ? $vendor_data['address']['city'] : '';
		// $zip      = isset( $vendor_data['address']['zip'] ) ? $vendor_data['address']['zip'] : '';
		$country  = isset( $vendor_data['address']['country'] ) ? $vendor_data['address']['country'] : '';
		$state    = isset( $vendor_data['address']['state'] ) ? $vendor_data['address']['state'] : ''; 
		
$total_vendor_city[$vendor_data['store_slug']]=$vendor_city;

/*======Vendor  City  Cost==========*/		
$V_selected_city = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."city where name='$vendor_city' " ) );

$V_city_id=$V_selected_city->id;

/*======Vendor  City  Cost==========*/


if($ct_u_city==$vendor_city && $ct_u_city==$delver_user_city && $vendor_city==$delver_user_city ){
	

}
if($ct_u_city!=$vendor_city && $ct_u_city!=$delver_user_city && $vendor_city!=$delver_user_city ){

	$cost['vendor']=isset( $rules_cost[$V_city_id])  ? $rules_cost[$V_city_id] : '';
	$cost['customer']=isset( $rules_cost[$c_city_id])  ? $rules_cost[$c_city_id] : '';
 $total_cost[$vendor_data['store_slug']]=array_sum($cost);	
//echo "customer , delevery boy and vendor  diffrent city . Vendor shipping is : ".$rules_cost[$V_city_id]." and customer shipping : ".$rules_cost[$c_city_id]." <br>";
$vendor_cost=$rules_cost[$V_city_id];
if(empty($rules_cost[$V_city_id])){
	$vendor_cost='0';
}
$customer_cost=$rules_cost[$c_city_id];
if(empty($rules_cost[$c_city_id])){
	$customer_cost='0';
}
$citieswise_text_new[$store_name]="<p class='des_custom'>Vendor (".$store_name.") : $".array_sum($cost)."</p>";	
}


if( $ct_u_city==$delver_user_city && $ct_u_city!=$vendor_city ){
//echo "customer and delevery boy same city and vendor diffrent . customer shipping : ".$rules_cost[$c_city_id]." <br>";
//$cost['vendor']=$rules_cost[$V_city_id];
$total_cost[$vendor_data['store_slug']]=$rules_cost[$V_city_id];	
$vendor_cost=$rules_cost[$V_city_id];
if(empty($rules_cost[$V_city_id])){
	$vendor_cost='0';
}

$citieswise_text_new[$store_name]="<p class='des_custom'>Vendor (".$store_name."): $".$vendor_cost."</p>";
}
if($ct_u_city==$vendor_city && $ct_u_city!=$delver_user_city ){
//echo "customer and vendor  same city and delevery diffrent "."<br>";
 
 $total_cost[$vendor_data['store_slug']]=$rules_cost[$c_city_id];
$vendor_cost=$rules_cost[$c_city_id];
if(empty($rules_cost[$c_city_id])){
	$vendor_cost='0';
}

$citieswise_text_new[$store_name]="<p class='des_custom'>Vendor (".$store_name."): $".$vendor_cost."</p>";
}
if($vendor_city==$delver_user_city && $vendor_city!=$ct_u_city ){
//echo "Vendor and delevery boy  same city and customer diffrent "."<br>";

$total_cost[$vendor_data['store_slug']]=$rules_cost[$c_city_id];

$customer_cost=$rules_cost[$c_city_id];
if(empty($rules_cost[$c_city_id])){
	$customer_cost='0';
}
$citieswise_text_new[$store_name]="<p class='des_custom'>Vendor (".$store_name."): $".$customer_cost."</p>";
}
}
/* echo "<pre>";
print_r($total_vendor_city);
echo "</pre>"; */
$Calgary_not=array();
$Calgary_in=array();
foreach($total_vendor_city as $chk){
	if($chk!="Calgary"){
		$Calgary_not[]=$chk;
	}
	if($chk=="Calgary"){
		$Calgary_in[]=$chk;
	}
	
}

if(array_sum($total_cost)!=0){

	$new_total=array_sum($total_cost);
	
	if (  isset( $package['rates']['pisol_extended_flat_shipping:4033'] ) ) { 
			$rate = $package['rates']['pisol_extended_flat_shipping:4033']; 
			 $total_v=count($total_vendor_city);
			 $rate_vendor=$rate->cost;
			
			$free_shipping_enable=get_option( 'free_shipping_enable');
			 $free_shipping_min_price=get_option( 'free_shipping_min_price');
			 $free_shipping_store_limit= get_option( 'free_shipping_store_limit' );
			 if($free_shipping_enable=="on"){
			
			$single_vendor_rate=$rate_vendor/$total_v;
			$store_limit_count = $free_shipping_store_limit;
			
			$cart_total_price=$package['cart_subtotal'];
			if($free_shipping_min_price<=$cart_total_price){
				
				if(empty($Calgary_in)){
					
					
        
					
				}else{
					if(count($Calgary_in)>=$store_limit_count){
					 $remove_ship_count=$store_limit_count;
					 $rate_vendor=$rate_vendor-$single_vendor_rate*$remove_ship_count;
					}
					if(count($Calgary_in)<$store_limit_count){
					$remove_ship_count=count($Calgary_in);
				    $rate_vendor=$rate_vendor-$single_vendor_rate*$remove_ship_count;
				    }
				}
				
				
			} 

	
           }  
                  // Merge cost and taxes - label and ID will be the same 
	$new_total += $rate_vendor; 
	$wp_session['citieswise_text'] .="<p class='des_custom'>Shipping Flat Fee: $".$rate_vendor."</p>";

	$wp_session['citieswise_text'] .="<p class='des_custom'>Outside Delivery Fees: <b>Total Outside Fee:</b> $".array_sum($total_cost)."</p>";


	foreach($citieswise_text_new as $city_text){
	$wp_session['citieswise_text'] .=$city_text;

	}
	
	/*  if(!empty($coupon_code)){
		 $c = new WC_Coupon($coupon_code);
	$dis=$c->get_amount();
	$wp_session['citieswise_text'] .="<p class='des_custom'>Coupon Discount:  - $".$dis."</p>";
	 } */
	} 
//Shipping Fee Pickup From ".count($citieswise_text_new).Locations
	
				$rate = array(
				        'id'    => $this->id,
						'label' => "Shipping  Pickup Fee ",
						'description'=>'check detail',
						'cost' => $new_total,
						'calc_tax' => 'per_item'
					);		

					// Register the rate
					$this->add_rate( $rate );
}

 if($ct_u_city=="Calgary"){
	
	$new_total="0";
	if (  isset( $package['rates']['pisol_extended_flat_shipping:4033'] ) ) { 
			$rate = $package['rates']['pisol_extended_flat_shipping:4033']; 
			 $rate_vendor=$rate->cost;
			 $total_v=count($total_vendor_city);
			 $free_shipping_enable=get_option( 'free_shipping_enable');
			 $free_shipping_min_price=get_option( 'free_shipping_min_price');
			 $free_shipping_store_limit= get_option( 'free_shipping_store_limit' );
			 if($free_shipping_enable=="on"){
			
			$single_vendor_rate=$rate_vendor/$total_v;
			$store_limit_count = $free_shipping_store_limit;
			$cart_total_price=$package['cart_subtotal'];
			if($free_shipping_min_price<=$cart_total_price){
				
				if(empty($Calgary_in)){
					
					
        
					
				}else{
					if(count($Calgary_in)>=$store_limit_count){
					 $remove_ship_count=$store_limit_count;
					 $rate_vendor=$rate_vendor-$single_vendor_rate*$remove_ship_count;
					}
					if(count($Calgary_in)<$store_limit_count){
					$remove_ship_count=count($Calgary_in);
				    $rate_vendor=$rate_vendor-$single_vendor_rate*$remove_ship_count;
				    }
				}
				
				
			} 

	
           }  
                  // Merge cost and taxes - label and ID will be the same 
	$new_total += $rate_vendor; 
	$wp_session['citieswise_text'] .="<p class='des_custom'>Shipping Flat Fee: $".$rate_vendor."</p>";
	 /* if(!empty($coupon_code)){
		 $c = new WC_Coupon($coupon_code);
	$dis=$c->get_amount();
	$wp_session['citieswise_text'] .="<p class='des_custom'>Coupon Discount:  - $".$dis."</p>";
	 } */ 
} 
//"Shipping  Pickup From ".$total_v." Locations"
	
				$rate = array(
				        'id'    => $this->id,
						'label' => "Shipping  Pickup Fee",
						'description'=>'check detail',
						'cost' => $new_total,
						'calc_tax' => 'per_item'
					);		

					// Register the rate
					$this->add_rate( $rate );
} 

				}
				}
				
			}
		}
	}

	add_action( 'woocommerce_shipping_init', 'citieswise_shipping_method_init' );

	function add_citiwise_shipping_method( $methods ) {
		$methods['citieswise'] = 'Citieswise_Shipping_Method';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'add_citiwise_shipping_method' );
	
	  function citieswise_validate_order( $posted )   {
  global $wpdb ,$woocommerce, $wp_session;
        $packages = WC()->shipping->get_packages();
		/* echo "<pre>";
 print_r($packages);die; */
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
        
        if( is_array( $chosen_methods ) && in_array( 'citieswise', $chosen_methods ) ) {
             
            foreach ( $packages as $i => $package ) {
 
                if ( $chosen_methods[ $i ] != "citieswise" ) {
                             
                    continue;
                             
                }else{
					
				$delver_user_city='Calgary';

$ct_u_city = $package["destination"]["city"];
$selected_city = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."city where name='$delver_user_city' " ) );
				
				$state_id=$selected_city->state_id;
				$d_city_id=$selected_city->id;
				/*======Delevery boy City  Cost==========*/	


				/*======Customer City  Cost==========*/		
				$c_selected_city = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."city where name='$ct_u_city' " ) );
				 $c_city_id=$c_selected_city->id;
				/*======Customer City  Cost==========*/					
				$get_rule = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."cities_shipping_rules where shipping_state='$state_id'") );

				$rules_cost=array();
				foreach($get_rule as $rules){
					$new=unserialize($rules->shipping_cities);
					foreach($new as $n){
						$rules_cost[$n]=$rules->shipping_cost_city;	
					}
					
				}
	
if(!isset($rules_cost[$c_city_id])){
if($ct_u_city=='Calgary'){
	
}else{
	 $message = sprintf( __( 'This City or Town is currently out of our service area', 'citieswise' ) );
                             
                        $messageType = "error";
 
                        if( ! wc_has_notice( $message, $messageType ) ) {
                         $wp_session['citieswise_text']="This City or Town is currently out of our service area";
                            wc_add_notice( $message, $messageType );
                           wp_redirect( site_url().'/cart' ); 
                           exit;
                        }
}
 
                       
            
} 	
				} 



                
                
                
                
            }       
        } 
    }
 
    //add_action( 'woocommerce_review_order_before_cart_contents', 'citieswise_validate_order' , 10 );
    //add_action( 'woocommerce_after_checkout_validation', 'citieswise_validate_order' , 10 );
}

 add_action( 'woocommerce_after_shipping_rate', 'action_after_shipping_rate', 20, 2 );
function action_after_shipping_rate ( $method, $index ) {
    global $wp_session;

    if( 'citieswise' === $method->id ) {
        echo __($wp_session['citieswise_text']);
    }
} 
/*--------delete a rule function  end-------------*/

 
 /*--------get Cities data start-------------*/
add_action('wp_ajax_custom_shipping_get_cities','function_custom_shipping_get_cities');
add_action("wp_ajax_nopriv_custom_shipping_get_cities", "function_custom_shipping_get_cities");
function function_custom_shipping_get_cities() 
{

global $wpdb;
if(isset($_POST["sid"]))
{
	
$sid=sanitize_text_field($_POST["sid"]);
}
$cities = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."city where state_id=%1s order by name asc", $sid));
echo json_encode($cities);
wp_die();
}
/*--------get Cities data end-------------*/

/*--------delete a rule function start-------------*/
add_action('wp_ajax_custom_shipping_rule_delete','function_custom_shipping_rule_delete');
add_action("wp_ajax_nopriv_custom_shipping_rule_delete", "function_custom_shipping_rule_delete");
function function_custom_shipping_rule_delete() 
{

global $wpdb;
if(isset($_POST["table_id"]))
{
	
$sid=sanitize_text_field($_POST["table_id"]);
}
$table = $wpdb->base_prefix.'cities_shipping_rules';
$res=$wpdb->delete( $table, array( 'id' => $sid ) );
echo json_encode($res);
wp_die();
}
add_shortcode('custom_order_detail', 'custom_order_detail_function');
function custom_order_detail_function() {
     if ( file_exists( CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/del_order_detail.php' ) ) {
        include CUSTOM_SHIPPING_CITY_DIR_PATH . 'template/del_order_detail.php';
    }
}


// ------------------
// 1. Register new endpoint (URL) for My Account page
// Note: Re-save Permalinks or it will give 404 error
  
function add_vendor_notification_endpoint() {
    add_rewrite_endpoint( 'vendor-notification', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'vendor-delivery-notes', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'add_vendor_notification_endpoint' );
  
// ------------------
// 2. Add new query var
  
function vendor_notification_query_vars( $vars ) {
    $vars[] = 'vendor-notification';
    $vars[] = 'vendor-delivery-notes';
    return $vars;
}
  
add_filter( 'query_vars', 'vendor_notification_query_vars', 0 );
  
// ------------------
// 3. Insert the new endpoint into the My Account menu
  
function add_vendor_notification_link_my_account( $items ) {
		 $user_id = get_current_user_id(); 
 $user_meta=get_userdata($user_id);

 $user_roles=$user_meta->roles;
 
	if (in_array("wcfm_vendor", $user_roles) || in_array("administrator", $user_roles)){
	 $items['vendor-notification'] = 'Vendor Notification';
    $items['vendor-delivery-notes'] = 'Delivery Notes';
    }
    
    return $items;
}
  
add_filter( 'woocommerce_account_menu_items', 'add_vendor_notification_link_my_account' );
  
// ------------------
// 4. Add content to the new tab
  
function vendor_notification_content() {

 $user_id = get_current_user_id(); 

if(isset($_POST['action_notification'])){

$key="cus_vendor_notification_sms";
 $value=$_POST['cus_noti_vendor'];

  update_user_meta( $user_id, $key, $value );
}
$key = 'cus_vendor_notification_sms'; 
     $single = true; 
   $user_last = get_user_meta( $user_id, $key, $single ); 
     
   echo '<div class="cus_vendor_noti"><h3>Notification Manager</h3><p>  New Order of customer, you can enable and disable for sms of order detail</p>';
  ?>
  <form method="post">
 
  <input type="checkbox"  name="cus_noti_vendor" <?php  if($user_last=="on"){ echo 'checked'; } ?>/> <span class="noti_label">Message Notification Enable </span>
  <input class="notification-submit-btn" type="submit"  name="action_notification" />
  </form>
  
  <?php  
}
add_action( 'woocommerce_account_vendor-notification_endpoint', 'vendor_notification_content' );

function vendor_delivery_notes_content() {

 $user_id = get_current_user_id(); 

if(isset($_POST['action_delivery_notes'])){

$key="cus_vendor_delivery_notes";
 $value=$_POST['cus_delivery_notes'];

  update_user_meta( $user_id, $key, $value );
}
$key = 'cus_vendor_delivery_notes'; 
     $single = true; 
   $user_last = get_user_meta( $user_id, $key, $single ); 
   echo '<div class="cus_delivery_note"><h3>Delivery Notes</h3><p>  New Order, add delivery notes</p>';
  ?>
  <form method="post">

  <span class="noti_label">Delivery Notes</span>
   <textarea style="width:50%" placeholder="Please write a notes for Delivery...." name="cus_delivery_notes" ><?php if(!empty($user_last)){ echo $user_last ; }  ?></textarea>
  <input class="notification-submit-btn" type="submit"  name="action_delivery_notes" />
  </form>
  
  <?php  
}
  
add_action( 'woocommerce_account_vendor-delivery-notes_endpoint', 'vendor_delivery_notes_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format
// 1. Register new endpoint (URL) for My Account page
// ------------------END 