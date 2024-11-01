<?php
require_once '../../../wp-load.php';

if(wp_verify_nonce($_POST['mwp-quick-feedback-nonce'])){
	//Send mail
	if(trim($_POST['mwp-quick-feedback-msg'])){
		$options = get_option('mwp_quick_feedback_options');
		if(!is_array($options))
			$options = array();
		extract($options);
		$headers='';
		
		$from = $_POST['mwp-quick-feedback-email'];
	 	$subject = $mwp_quick_feedback_subject;
   	$to       = $mwp_quick_feedback_to;
	
	 	$headers  = "From: $from \r\n";	 	
   	$headers .= "Reply-To: " . $from . "\r\n";
   	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  	@wp_mail($to, $subject, $_POST['mwp-quick-feedback-msg'], $headers);
 	}
}

?>