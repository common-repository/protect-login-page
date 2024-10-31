<?php
	
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! wp_verify_nonce( $eeNonce, 'ee_include_page' ) ) exit; // Exit if nonce fails

$eeOutput = '<article class="eeSupp">

<h2>' . __('Activation', 'ee-protect-login-page') . '</h2>

<p>' . __('To activate protection, check the Activate box, then enter the PIN you want to use in the box below.', 'ee-protect-login-page') . ' ' . __('The PIN can be up to 8 digits in length.', 'ee-protect-login-page') . ' '  . __('Your website login page will now only be accessible using the PIN in the URL.', 'ee-protect-login-page') . '</p>';

$eePLP_STATE = get_option('eePLP_STATE');
$eePLP_PIN = get_option('eePLP_PIN');
$eePLP_URL = 'http://' . $_SERVER['HTTP_HOST'] . '/wp-login.php?PIN=';

if($eePLP_STATE == 'ON' AND $eePLP_PIN) {
	
	$eeOutput .= '<h3>' . __('Your PIN is set!', 'ee-protect-login-page') . '</h3>';
	
	$eeOutput .= '<p><strong>' . $eePLP_PIN . '</strong></p>';

}

$eeOutput .= '<p>';

if(!$eePLP_PIN) {
	$eePLP_PIN = '[' . __('your PIN goes here', 'ee-protect-login-page') . ']';
}

if($eePLP_STATE != 'ON') {
	$eeOutput .= __('Once activated', 'ee-protect-login-page') . ', ';
}

$eeOutput .= __('Log in using this URL', 'ee-protect-login-page') . ':<br />
	<a target="blank" href="' . $eePLP_URL . $eePLP_PIN . '"><strong>' . $eePLP_URL . $eePLP_PIN . '</strong></a></p>

<h2>' . __('IMPORTANT', 'ee-protect-login-page') . '</h2>

<p>' . __('If you lose your PIN you will not be able to log into your website.', 'ee-protect-login-page') . ' ' . __('Deactivate the plugin in  the database', 'ee-protect-login-page') . ' (wp_options.eePLP_STATE) ' . __('or rename the plugin\'s folder in your files.', 'ee-protect-login-page') . ' ' . __('You may also <a href="/wp-admin/options-general.php?page=ee-protect-login-page&tab=help">contact support</a> for a rescue PIN.', 'ee-protect-login-page') . '</p>

<p>' . __('Do not use this plugin if you require login access other than the main Wordpress login page, such as the Wordpress mobile app.', 'ee-protect-login-page') . '</p>
	
</article>';

?>