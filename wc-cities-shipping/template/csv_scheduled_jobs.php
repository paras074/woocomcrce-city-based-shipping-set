<?php
global $wpdb;
$prefix = $wpdb->prefix;
  if(isset($_POST['submit_form_shedule'])){
	  //echo "<pre>";
	 //print_r($_POST);
	$data_setting=array();
	$new=array();
		  $data_setting['enable']=$_POST['enable'];
		  $data_setting['skip_empty_file']=$_POST['skip_empty_file'];
		  foreach($_POST['weekday'] as $key=>$data){
			  
			  if(isset($data['day'])){
				$new[$key]['time']=$data['Time'];
			  }
		  }
		  $data_setting['weekday']=serialize($new);
		  $data_setting['emails']=serialize($_POST['emails']);
		  
	foreach($data_setting as $field=>$value){
		 $table_name=$prefix.'csv_export_shedule';
		//echo "UPDATE $table_name SET value='$value' WHERE field=$field";
		$wpdb->query($wpdb->prepare("UPDATE $table_name SET value='$value' WHERE field='$field'"));
	
	} 
  }
$get_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$prefix."csv_export_shedule ") );  

$newarray=array();
foreach($get_data as $value){
	$newarray[$value->field]=$value->value;
}

?>
<section class="custom_sedule_job">
<h3> Schedule Setting </h3>
<div id="my-left" style="float: left; width: 49%; max-width: 500px;">
<form method='post' name="shipping_rates_submit"  >
<div class="my-block_title">
        <div>
		<label><input type="checkbox" name="enable" <?php  if($newarray['enable']=='on'){ echo 'checked="checked"' ; }  ?>>
			Enable       </label><br>
        <label>
            
            <input type="checkbox" name="skip_empty_file" <?php  if($newarray['skip_empty_file']=='on'){ echo 'checked="checked"' ; }  ?> >
			Don't send empty file        </label>
    </div>
</div>	
<div id="my-shedule-days_times" class="my-block_sedule_time">
    <div class="wc-oe-header">Schedule</div>
    <div id="d-schedule-1">
       <div class="block d-scheduled-block">
<?php $weekday=unserialize($newarray['weekday']); ?>
            <div class="weekday">
				<p> <label><span class="days-span-wrapper"><span class="day-txt">Sun</span><input type="checkbox" name="weekday[Sun][day]" <?php  if(isset($weekday['Sun'])){ echo 'checked="checked"' ; }  ?>></span> <span class="time-span-wrapper">Run at: <input type="time" name="weekday[Sun][Time]" value="<?php echo  $weekday['Sun']['time']  ?>"></span> </label></p>
				
				<p> <label><span class="days-span-wrapper"><span class="day-txt">Mon</span><input type="checkbox" name="weekday[Mon][day]" <?php if(isset($weekday['Mon'])){ echo 'checked="checked"' ; }  ?>></span> <span class="time-span-wrapper">Run at: <input type="time" name="weekday[Mon][Time]" value="<?php echo $weekday['Mon']['time']  ?>"></span> </label></p>
				
				<p> <label><span class="days-span-wrapper"><span class="day-txt">Tue</span><input type="checkbox" name="weekday[Tue][day]" <?php  if(isset($weekday['Tue'])){ echo 'checked="checked"' ; }  ?>></span> <span class="time-span-wrapper">Run at: <input type="time" name="weekday[Tue][Time]" value="<?php echo  $weekday['Tue']['time']  ?>"></span> </label></p>
				
				<p> <label><span class="days-span-wrapper"><span class="day-txt">Wed</span><input type="checkbox" name="weekday[Wed][day]" <?php  if(isset($weekday['Wed'])){ echo 'checked="checked"' ; }  ?>></span> <span class="time-span-wrapper">Run at: <input type="time" name="weekday[Wed][Time]" value="<?php echo  $weekday['Wed']['time']  ?>"></span> </label></p>
				
				<p> <label><span class="days-span-wrapper"><span class="day-txt">Thu</span><input type="checkbox" name="weekday[Thu][day]" <?php  if(isset($weekday['Thu'])){ echo 'checked="checked"' ; }  ?>></span> <span class="time-span-wrapper">Run at: <input type="time" name="weekday[Thu][Time]" value="<?php echo  $weekday['Thu']['time']  ?>"></span> </label></p>
				
				<p> <label><span class="days-span-wrapper"><span class="day-txt">Fri</span><input type="checkbox" name="weekday[Fri][day]" <?php  if(isset($weekday['Fri'])){ echo 'checked="checked"' ; }  ?>></span> <span class="time-span-wrapper">Run at: <input type="time" name="weekday[Fri][Time]"  value="<?php echo  $weekday['Fri']['time']  ?>"></span> </label></p>
				
				<p> <label><span class="days-span-wrapper"><span class="day-txt">Sat</span><input type="checkbox" name="weekday[Sat][day]" <?php  if(isset($weekday['Sat'])){ echo 'checked="checked"' ; }  ?>></span> <span class="time-span-wrapper">Run at: <input type="time" name="weekday[Sat][Time]" value="<?php echo  $weekday['Sat']['time']  ?>"></span> </label></p>
				            
				            </div>
            
        </div>
    </div>
    <div class="weo_clearfix"></div>
</div>
<?php $emails=unserialize($newarray['emails']); ?>
<div  id="my-shedule-emails">
<label>Emails</label>
        <table class="table table-bordered" id="dynamic_field"> <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
<?php if(!empty($emails)){ 
 $tlist=count($emails);
 foreach($emails as $em_k=> $email_list){	?>	
 
            <tr id="row<?php echo $em_k;  ?>">  
                                         <td><input type="text" name="emails[]" value="<?php echo $email_list;  ?>" placeholder="Enter your Name" class="form-control name_list" /><button type="button" name="remove" id="<?php echo $em_k;  ?>" class="btn btn-danger btn_remove">Remove</button></td>  

                                    </tr>                         
<?php }}else{
 $tlist=0;
	?>
	
<tr>  
                                         <td><input type="text" name="emails[]" placeholder="Enter your Name" class="form-control name_list" /></td>  
                                         <td></td>  
                                    </tr> 
<?php } ?>									
                               </table> 


</div>
<button id="sb-btn-wrap" type="submit" name="submit_form_shedule" class="btn btn-primary">Submit</button>
</form>
	</div>
</section>

<script>  
 var $=jQuery;
 $(document).ready(function(){  
      var i=<?php echo $tlist;  ?>;  
      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="emails[]" placeholder="Enter your Name" class="form-control name_list" /><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">Remove</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 </script>