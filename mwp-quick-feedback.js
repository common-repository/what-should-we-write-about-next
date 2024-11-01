jQuery(document).ready(function(){
	jQuery("#mwp-quick-feedback-form").live('submit',function(e){
		e.preventDefault();
		
		jQuery("#mwp-quick-feedback-result").css('display','block').text("Thank you.");
		jQuery(this).hide();
		jQuery.ajax({
        type: "post",
        url: mwp_qf_params.url,
        data: jQuery(this).serialize(),
        success: function (msg) {},
        error: function () {
            genericError();
        }
    });
    
		});
});