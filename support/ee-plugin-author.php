<?php // PLUGIN AUTHOR PAGE - Mitchell Bennis | Element Engage, LLC | mitch@elementengage.com
	
	// Rev 03.12.18
	
	// text-domain = ee-protect-login-page
	
defined( 'ABSPATH' ) or die( 'No direct access is allowed' );
if ( ! wp_verify_nonce( $eeNonce, 'ee_include_page' ) ) exit; // Exit if nonce fails
	
// Plugin Contributors Array - Format: Name|URL|DESCRIPTION Example: Thnaks to <a href="URL">NAME</a> DESCRIPTION
// Values here are inserted below
$eeContributors = FALSE;  // else it's an array('');

$eePageSlug = $_GET['page'];
	
// The Content
$eeOutput = '<article class="eeSupp">

	<a href="https://elementengage.com/donate/" title="' . esc_attr__('Show Your Support', 'ee-protect-login-page') . '" target="_blank">
	
		<img style="float:right;" src="' . plugin_dir_url( __FILE__ ) . '/images/Mitchell-Bennis-Head-Shot.jpg" />
	
	</a>

	<h2>' . __('Plugin Author', 'ee-protect-login-page') . '</h2>

	
	<p>' . __('Plugin by', 'ee-protect-login-page') . ' <a href="http://mitchellbennis.com/" target="_blank">Mitchell Bennis</a> ' . __('at', 'ee-protect-login-page') . ' 
		
		<a href="https://elementengage.com/" target="_blank">Element Engage</a> ' . __('in', 'ee-protect-login-page') . ' Cokato, Minnesota, USA</p>'; // That's me!
		
		$eeOutput .= '<p>' . __('Contact Me', 'ee-protect-login-page') . ': <a href="?page=' . $eePageSlug . '&tab=support ">' . __('Feedback or Questions', 'ee-protect-login-page') . '</a></p><p>'  
			
 		. __('Please rate this plugin', 'ee-protect-login-page') . ' <a href="https://wordpress.org/plugins/protect-login-page/reviews/" target="_blank">here</a>.</p>'; // It's a good thing
		
		
	if(is_array($eeContributors)) {
		
		$eeOutput .= '<hr />
		
		<h6>' . __('Contributors', 'ee-protect-login-page') . '</h6>
		
		<p>';
		
		// Contributors Output
		foreach( $eeContributors as $eeValue){
			
			$eeArray = explode('|', $eeValue);
			$eeOutput .= __('Thanks to', 'ee-protect-login-page') . ' <a href="' . @$eeArray[1] . '" target="_blank">' . @$eeArray[0] . ' </a>' . @$eeArray[2] . '<br />';
		}
		
		$eeOutput .= '</p>';
	}
		
	$eeOutput .= '</article>';
	
	
?>