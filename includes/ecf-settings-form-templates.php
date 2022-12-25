
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	
<?php
    global $wpdb; 
    $table_name =  $wpdb->prefix.'easy_contact_form';
    $results = $wpdb->get_results( "SELECT * FROM $table_name");
      foreach ( $results as $print ) {
      }
        $email_get = $print->email_to;
        $check_count = $print->check_count;
        $check_recaptcha = $print->check_recaptcha;
        $site_key = $print->site_key;
      
 ?>

<section class="ecf_form_wrapper" >
  <div class="ecf_form_container">
    <div  class="ecf_form_heading_control">
     <h1 class="ecf_form_title" ><?php echo get_the_title();  ?></h1>
     <img class="linear_img_form" src="<?php echo plugin_dir_url( __FILE__ ).'../assets/images/liner.png' ;?>" alt="line"></img>
    </div>
	  <form action="" method="post" class="ajax" >
      <div class="ecf-form-row" ><label class="wpcf_label" >Name:</label><span class="form-required"> *</span></br><input type="text" name="name" value=""  class="name"id="name" required="required"><span class="check_name" ></span></div>
	    <div class="ecf-form-row" ><label class="wpcf_label"  >Email:    </label><span class="form-required"> *</span></br><input type="email" name="email" value=""   class="email" id="email" required="required"><span class="check_email" ></span></div>
	    <div class="ecf-form-row" ><label class="wpcf_label"  >Subject:</label><span class="form-required"> *</span></br><input type="text" name="subject" value=""   class="subject" id="subject" ><span class="check_subject" ></span></div>
	    <div class="ecf-form-row" ><label  class="wpcf_label" >Message:</label><span class="form-required"> *</span></br><textarea name="message" cols="40"  rows="10" id="text_length"  class="message" aria-invalid="false"></textarea><span class="check_textarea" ></span></div>
	   
   
             <input type="hidden"   name="get_check_val" class="getcheckval"  value="<?php echo $check_recaptcha; ?>" />
          
    <?php
           
        if($check_count==1){
             echo '<h3 id="count_show" >Message Length: <span class="results" >0</span></h3>';
             echo '<h4 class="ecf_count_power" >Powered by: <a href="https://charactercounter.com/" >CharacterCounter.com</a></h4>';
           }else {
	          echo "";
           }

          if($check_recaptcha==1){
            ?>
            <div class="g-recaptcha" data-sitekey="<?php  foreach ( $results as $print ) { 
              	echo $print-> site_key; 
             }
            ?>"  >
          </div>
          <span class="check_confrim_box" ></span>
           <?php 
            }else {
           echo "";
            }
     
    ?>
	  <p><input type="submit" name="submit"  value="Submit" id="ecf-form-submit"  onclick="return callValidation();" /></p>
       <div class="loader" >
         <img src="<?php echo plugin_dir_url( __FILE__ ).'../assets/images/loader.gif' ;?>" alt="wpcfImage" style="width:50px; height:50px; " >
       </div>
    <h1 class="data_access" ></h1>
	 </form> 
 </div>
</section>

<script>



  function callValidation(){
    var checkClass= jQuery("div").hasClass("g-recaptcha");
    
   if(checkClass ){
    var checkRecaptcha =grecaptcha.getResponse().length == 0 ;
   }else{
    var checkRecaptcha = "";
   }

    if( checkRecaptcha || document.getElementById('name').value == '' || document.getElementById('email').value == '' || document.getElementById('subject').value == '' || document.getElementById('text_length').value == ''  ){

     if( document.getElementById('name').value == '') {
       jQuery('.check_name').text('The name is required');
     }else{
       jQuery('.check_name').text('');
     }
     if( document.getElementById('email').value == '') {
       jQuery('.check_email').text('The email is required');
     }else{
       jQuery('.check_email').text('');
     }
     if( document.getElementById('subject').value == '') {
       jQuery('.check_subject').text('The subject is required');
     }else{
       jQuery('.check_subject').text('');
     }
     if( document.getElementById('text_length').value == '') {
       jQuery('.check_textarea').text('The message is required');
     }else{
       jQuery('.check_textarea').text('');
     }
    if( checkRecaptcha ) {
      jQuery('.check_confrim_box').text('The reCAPTCHA confirmation is required');
    }else{
      jQuery('.check_confrim_box').text('');
    }
    return false;
  }

    jQuery('.check_name').text('');
    jQuery('.check_email').text('');
    jQuery('.check_subject').text('');
    jQuery('.check_textarea').text('');
    jQuery('.check_confrim_box').text('');

 return true;
}


</script>