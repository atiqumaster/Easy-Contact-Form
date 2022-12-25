jQuery(document).ready(function(jQuery){

	jQuery('form.ajax').on('submit', function(e){
		
		e.preventDefault();
		var that = jQuery(this),
		url = that.attr('action'),
		type = that.attr('method');
		var name = jQuery('.name').val();
		var email = jQuery('.email').val();
		var subject = jQuery('.subject').val();
		var message = jQuery('.message').val();
		var checkval = jQuery('.getcheckval').val();
	    var datas = { action : 'ecf_form_callback', name:name, email:email,subject:subject,message:message	}
        if(checkval == 1){
			datas['recaptcha'] = grecaptcha.getResponse();
	    }
  jQuery.ajax( {
   url : ecf_ajax_url.ajax_url,
   type : 'post',
   data : datas ,
   
   beforeSend: function(){
   jQuery('.loader').show();
	},
   success:function(data){
     console.log(data);
	   jQuery('.loader').hide();
       jQuery('.data_access').show();
	   jQuery('.data_access').html(data);
		}
	 }); 

	
	 jQuery('.ajax')[0].reset();
	});
   });




jQuery( document ).ready(function() {
	jQuery('#text_length').keyup(function () { 
	var value= jQuery(this).val();
    var length = value.length;     

	jQuery('.results').html(length);
	});
});