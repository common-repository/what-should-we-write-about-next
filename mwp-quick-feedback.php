<?php
/* 
Plugin Name: What Should We Write About Next
Plugin URI: http://www.prelovac.com/vladimir/wordpress-plugins/what-should-we-write-about-next
Description: allows your users to quickly leave feedback at the end of your posts
Author: Vladimir prelovac
Version: 1.0
Author URI: http://www.prelovac.com/vladimir/
*/


add_action('admin_menu','mwp_quick_feedback_add_options');
function mwp_quick_feedback_add_options(){        
	add_menu_page('What Should We Write About Next', 'What Write', 'administrator', 'mwp-quick-feedback-options', 'mwp_quick_feedback_options_page');
}

function mwp_quick_feedback_options_page(){
	
	if(isset($_POST['mwp_quick_feedback_submit'])){
		echo '<div id="message" class="updated below-h2"><p>Options updated.</p></div>';
		mwp_quick_feedback_save_options($_POST);
	}
	
	$options = get_option('mwp_quick_feedback_options');
	if(!is_array($options))
		$options = array();
	extract($options);
	$mwp_quick_feedback_content = $mwp_quick_feedback_content ? 'checked' : ''; 
	echo '<div class="wrap">';
	echo '<h2>What Should We Write About Next Options</h2>';
	echo '<form method="post">';
	echo '<table class="form-table"><tbody>';
	echo '<tr class="form-field"><th><label>Label text:</label></th><td><input type="text" name="mwp_quick_feedback_label" value="'.$mwp_quick_feedback_label.'" style="width:300px;"/></td></tr>';
	echo '<tr class="form-field"><th><label>Email subject:</label></th><td><input type="text" name="mwp_quick_feedback_subject" value="'.$mwp_quick_feedback_subject.'" style="width:300px;"/></td></tr>';
	echo '<tr class="form-field"><th><label>Email to:</label></th><td><input type="text" name="mwp_quick_feedback_to" value="'.$mwp_quick_feedback_to.'" style="width:300px;"/></td></tr>';
	echo '<tr class="form-field"><th><label>Attach to post content?</label></th><td style="float: left;width: 27px;text-align: left !important;"><input type="checkbox" name="mwp_quick_feedback_content" value="1" '.$mwp_quick_feedback_content.'/></td></tr>';
	echo '<tr class="form-field"><th><label></label></th><td><input type="submit" name="mwp_quick_feedback_submit" value="Save" class="button" style="width:40px;"/></td></tr>';
	echo '</tbody></table>';
	echo '</form>';
	echo '</div>';
}


function mwp_quick_feedback_save_options($args){
	update_option('mwp_quick_feedback_options',$args);
}

add_filter('the_content','mwp_quick_feedback_form_content');
	function mwp_quick_feedback_form_content($post){
		if(is_single()){
			$options = get_option('mwp_quick_feedback_options');
			if(isset($options['mwp_quick_feedback_content'])){
				$form_html = mwp_quick_feedback_form();
				return $post.$form_html;
			}
		} 
		return $post;
}

function mwp_quick_feedback_form(){
	global $current_user;
			$email = isset($current_user->user_email) ? $current_user->user_email : get_option('admin_email');
			$options = get_option('mwp_quick_feedback_options');
				if(!is_array($options))
			$options = array();
			extract($options);
			
			$form_html = '<div class="mwp-quick-feedback-form">
				<div class="shadow3"></div>
				<span id="mwp-quick-feedback-result"></span>
				<form id="mwp-quick-feedback-form" method="POST">
				<label class="feedback_title">'.$mwp_quick_feedback_label.'</label><label><input type="text" name="mwp-quick-feedback-msg" id="mwp-quick-feedback-msg"/><input type="submit" class="button" value="Send"/></label>
				<input type="hidden" name="mwp-quick-feedback-email" value="'.$email.'">
				<input type="hidden" name="mwp-quick-feedback-nonce" value="'.wp_create_nonce().'"/>
			</form>
			</div>';
		
		return $form_html;
}

add_action('wp_enqueue_scripts','mwp_quick_feedback_scripts');
function mwp_quick_feedback_scripts(){
	if(is_single()){
		$plugin_url = WP_PLUGIN_URL . '/' . basename(dirname(__FILE__));
		
		wp_register_script('mwp-quick-feedback-js', "$plugin_url/mwp-quick-feedback.js", array('jquery'));  
		wp_enqueue_script('mwp-quick-feedback-js');
		//Set params for JS
		$params = array('url' => $plugin_url.'/mwp-quick-feedback-ajax.php');
		wp_localize_script( 'mwp-quick-feedback-js', 'mwp_qf_params', $params);
        
    wp_register_style('mwp-quick-feedback-css', "$plugin_url/mwp-quick-feedback.css");
    wp_enqueue_style( 'mwp-quick-feedback-css');
	}
}

register_activation_hook( __FILE__ ,'mwp_quick_feedback_install');
function mwp_quick_feedback_install(){
	//set default settings
	$args = array(
		'mwp_quick_feedback_label' => 'What should we write about next?',
		'mwp_quick_feedback_subject' => 'What should we write about next',
		'mwp_quick_feedback_to' => get_option('admin_email'),
		'mwp_quick_feedback_content' => 1
	);
	update_option('mwp_quick_feedback_options',$args);
}

register_deactivation_hook(__FILE__, 'mwp_quick_feedback_uninstall');
function mwp_quick_feedback_uninstall(){
	delete_option('mwp_quick_feedback_options');
}

?>