<?php // PLUGIN EMAIL SUPPORT FORM - Mitchell Bennis | Element Engage, LLC | 
	
	// #eeSupportForm, eeContact - Rev 05.26.18
	
// Add to main plugin file: require(plugin_dir_path( __FILE__ ) . 'support/ee-support-functions.php');
// Add to display function globals: $eeContact_Plugin, $eeContact_name, $eeContact_email, $eeContact_message, $eeContact_link, $eeContact_From

defined( 'ABSPATH' ) or die( 'No direct access is allowed' );
if ( ! wp_verify_nonce( $eeNonce, 'ee_include_page' ) ) exit; // Exit if nonce fails
	
// Config		
$eeContact_To = 'mitch@elementengage.com';

// Initialize
$eeContact_From = '';
$eeContact_name = '';
$eeContact_email = '';
$eeContact_message = '';
$eeContact_link = '';
$eeContact_wpError = '';
$eeContact_Headers = array();

$eeContact_Install = WP_PLUGIN_URL; // Why won't this work too? Some rule? ---> . '/' . filter_var($_GET['page'], FILTER_SANITIZE_STRING) . '/';
		
include($eePluginPath . '/support/ee-support-functions.php');

// Convert the current plugin slug to a nice name
$eeContact_PluginName = eeUnSlug(str_replace('ee-', '', filter_var($_GET['page'], FILTER_SANITIZE_STRING)));
	
// Form Processor ======================================

if(@$_POST['eeSupportForm']) {
	
	// echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
	
	if(@$_POST['eeContact_email'] AND check_admin_referer( 'ee-support-form', 'ee-support-form-nonce')) {
		
		$eeContact_Body = eeProcessSupportPost($_POST); // Process the form
		
		$eeContact_Headers[] = 'From: wordpress@' . $_SERVER['HTTP_HOST'];
		$eeContact_Headers[] = 'Reply-To: ' . $eeContact_From;
		
		if($eeContact_Body) {
			
			// show mail error if there is one.
			add_action( 'wp_mail_failed', 'eeMailError', 10, 1);
			
			// Uncomment to test email error
			// $eeContact_To = FALSE;
			
			$eeContact_Body = htmlspecialchars_decode($eeContact_Body);
			// $eeContact_Body = urldecode($eeContact_Body);
			$eeContact_Body = strip_tags($eeContact_Body);
			$eeContact_Body = stripslashes($eeContact_Body);	// Make it all nice
			
			if(wp_mail($eeContact_To, $eeContact_PluginName . ' Support', $eeContact_Body, $eeContact_Headers)) {
				
				echo "<script>
				
				alert('The message was sent. Expect a reply soon.');
				
				window.location.replace('" . get_admin_url() . basename($_SERVER['PHP_SELF']) . '?page=' . $_GET['page'] . "');
				
				</script>";
			}
		}
	}
}


// Form Display ========================================
	
// Get this URL with all arguments
$eeContact_Location = $_SERVER['REQUEST_URI'];
	
// Nonce a field for security
$eeContact_Nonce = wp_nonce_field( 'ee-support-form', 'ee-support-form-nonce', TRUE, FALSE); // Return it

// The form's default link
if(!$eeContact_link) { $eeContact_link = get_bloginfo('url'); }

// Build the Support Form
$eeOutput = '<article class="eeSupp">

		<p><strong>' . __('Do you need help or have a suggestion? Send me a message and I will reply promptly.', 'ee-protect-login-page') . '</strong></p>

		<form action="' . $eeContact_Location . '" method="post" id="eeSupportForm"> ' . "\n\n" . '
			  
			  <input type="hidden" name="eeSupportForm" value="TRUE" />' . "\n" . 
			  $eeContact_Nonce . "\n\n" . '
			  <input type="hidden" name="eeContact_Site" value="' . get_bloginfo('url') . '" />' . "\n" . '
			  <input type="hidden" name="eeContact_Site-Name" value="' . get_bloginfo('name') . '" />' . "\n" . '
				
			<fieldset>
				
				<label for="eeContact_name">' . __('Your Name', 'ee-protect-login-page') . ':</label>
				<input type="text" name="eeContact_name" id="eeContact_name" value="' . $eeContact_name . '" required /><span class="eeContact_Required">*</span>
				<br class="eeClearFix" />
				
				<label for="eeContact_email">' . __('Your Email', 'ee-protect-login-page') . ':</label>
				<input type="email" name="eeContact_email" id="eeContact_email" value="' . $eeContact_email . '" required /><span class="eeContact_Required">*</span>
				<br class="eeClearFix" />
				
				<input type="hidden" name="eeContact_Plugin" value="' . $eeContact_PluginName . '" />' . "\n" . '
				<input type="hidden" name="eeContact_Install" value="' . $eeContact_Install . '" />' . "\n" . '
				
				<label for="eeContact_page">' . __('Page with Problem', 'ee-protect-login-page') . ':</label><input type="url" name="eeContact_Problem-Page" value="' . $eeContact_link . '" id="eeContact_page">' . "\n" . '

				<input type="hidden" name="eeContact_Admin-Email" value="' . get_bloginfo('admin_email') . '" />' . "\n" . '
				<input type="hidden" name="eeContact_Time-Zone" value="' . get_option('timezone_string') . '" />' . "\n" . '
				<input type="hidden" name="eeContact_Language" value="' . get_bloginfo('language') . '" />' . "\n" . '
				<input type="hidden" name="eeContact_WP-Version" value="' . get_bloginfo('version') . '" />' . "\n" . '
				<input type="hidden" name="eeContact_Content-Type" value="' . get_bloginfo('html_type') . '" />' . "\n" . '
				<!-- <input type="hidden" name="eeContact_IP" value="' . @$_SERVER['SERVER_ADDR'] . '" />' . "\n" . '
				<input type="hidden" name="eeContact_Referer" value="' . @$_SERVER['HTTP_REFERER'] . '" />' . "\n" . ' -->
				<input type="hidden" name="eeContact_Agent" value="' . @$_SERVER['HTTP_USER_AGENT'] . '" />' . "\n" . '
				
				<label for="eeContact_message">' . __('Your Message', 'ee-protect-login-page') . ':</label>
				<textarea required name="eeContact_message" id="eeContact_message" cols="60" rows="6">' . $eeContact_message . '</textarea><span class="eeContact_Required">*</span>
				<br class="eeClearFix" />
				
				<br class="eeClearFix" />
				
				<span id="eeSupportFormSubmitMessage" style="font-size:150%;">
				Sending Your Message<br />
				<img style="padding:3px;background:white;border:1px #666 solid;border-radius:12px;margin-top:10px;" src="' . plugin_dir_url(__FILE__) . 'images/sending.gif" width="32" height="32" alt="Sending Icon" /></span>
				<br class="eeClearFix" />
				<input type="submit" id="eeSupportFormSubmit" value="SEND">
				
				<p style="text-align:right;"><i>' . __('Plugin environment details will automatically be sent along with your message to:', 'ee-protect-login-page') . '<br /><i><a href="mailto:' . $eeContact_To . '" title="Mitchell Bennis" >' . $eeContact_To . '</a></i></p>
			
			</fieldset>
			
			  
			
			</form></article>
			
	<script>

	jQuery( "#eeSupportFormSubmitMessage" ).hide();
	
	jQuery( "#eeSupportForm" ).submit(function() {
		jQuery( "#eeSupportFormSubmit" ).hide();
		jQuery( "#eeSupportFormSubmitMessage" ).fadeIn();
	});

	</script>';
	
	
	
	
?>