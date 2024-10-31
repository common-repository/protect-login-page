<?php

/**
 * @package Element Engage - eePLP
 */
/*
Plugin Name: Protect Login Page
Plugin URI: http://elementengage.com/protect-login-page
Description: Deny access to your login page using a simple PIN. Help keep the hackers away &rarr; <a href="/wp-admin/options-general.php?page=ee-protect-login-page">Set Your PIN</a>
Author: Mitchell Bennis - Element Engage, LLC
Version: 1.0.3
Author URI: http://elementengage.com
License: GPLv2 or later
Text Domain: ee-protect-login-page
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eePLP_Activate() {
	
	// Nothing to do. Options will be set upon plugin config.
}
register_activation_hook( __FILE__, 'eePLP_Activate' );



// Load stuff we need in the Admin head
function eePLP_AdminHead($eeHook) {
        
    // wp_die($eeHook); // Use this to discover the hook for each page
    
    // https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
    
    // Only load scripts if on these Admin pages.
    $eeHooks = array('settings_page_ee-protect-login-page');
    
    if(in_array($eeHook, $eeHooks)) {
        wp_enqueue_style( 'ee-protect-login-page-style', plugins_url('ee-style.css', __FILE__) );
        wp_enqueue_script( 'ee-protect-login-page-js', plugins_url('ee-javascript.js', __FILE__) );
    }

}
add_action( 'admin_enqueue_scripts', 'eePLP_AdminHead' );



// Functions ---------------------------------

// Plugin Menu
function eePLP_PluginMenu() {
	
	// https://codex.wordpress.org/Adding_Administration_Menus
	
	add_options_page( 'Protect Login Page', 'Login Page PIN', 'activate_plugins', 'ee-protect-login-page', 'eePLP_OptionsPageDisplay');

}

add_action( 'admin_menu', 'eePLP_PluginMenu' );



function eePLP_OptionsPageDisplay() {
	
	if( !current_user_can('activate_plugins') ) { return FALSE; } // This plugin is only for Admins
	
	$eeDevMode = FALSE; // If TRUE, writes a log file in the plugin/logs folder. Size is limited to 256kB.
						// Errors are always written.
	
	// Configuration
	$eeWebsiteLink = 'https://elementengage.com';
	$eePluginName = 'Protect Login Page';
	$eeVersion = '1.0.2';
	
	// Variables
	$eeLog = array('Main Page Loading...');
	$eePluginSlug = 'ee-protect-login-page';
	$eeResult = FALSE;
	$eeMessages = array();
	$eeErrors = array();
	$eePluginPath = plugin_dir_path( __FILE__ ) . '/';
	
	// Process the Form
	if(@$_POST['eePLP'] == 'TRUE') {
		
		if(check_admin_referer( 'ee-protect-login-page', 'ee-protect-login-page-nonce')) {  // Check the Form's Nonce
	
			// On or OFF
			if(@$_POST['eePLP_STATE'] == 'ON') {  // Don't use the input, just make sure it is exactly what we want.
				update_option('eePLP_STATE', 'ON');
				$eeMessages[] = 'eePLP is ON';
			} else {
				update_option('eePLP_STATE', 'OFF'); // If it's anything but ON, turn OFF the protection
				$eeMessages[] = 'eePLP is OFF';
			}
		
			// The PIN
			$eePIN = preg_replace("/[^\d]/i", "", $_POST['eePLP_PIN']); // Sanitize
			// A javascript insures that only digits are allowed in the input, 
			// but we also strip everything except digits 0 - 9 here as well.
			
			// PIN length must match after filtering, and it must be under 8 chars
			if(strlen($eePIN) == strlen($_POST['eePLP_PIN']) AND strlen($eePIN) <= 8) { // Blank is allowed
				
				update_option('eePLP_PIN', $eePIN); // Set the PIN Value in the Database
				$eeMessages[] = 'eePLP PIN SET';
				if($eeDevMode) { $eeMessages[] = 'PIN: ' . $eePIN; }
				
				// Allow a blank PIN to turn off the protection.
				if(!$eePIN) {
					update_option('eePLP_STATE', 'OFF');
					$eeMessages[] = 'eePLP is now OFF';
				}
					
			} else {
				update_option('eePLP_STATE', 'OFF');
				$eeErrors['ERROR'] = 'PIN using non-numeric characters or PIN longer than 8 characters.';
			}
		}
	}
	
	// Security
	$eeNonce = wp_create_nonce('ee_include_page'); // Check on the included pages below
	

	// HTML Buffer
	$eePLP_Output = '<div class="eePLP_Tabs wrap">
		
		<h1>' . __('Protect Login Page', 'ee-protect-login-page') . '</h1>'; 
	
	$eePLP_Page = 'ee-protect-login-page'; // This admin page slug
	
	// Reads the new tab's query string value
	if( isset( $_GET[ 'tab' ] ) ) { $active_tab = $_GET[ 'tab' ]; } else { $active_tab = 'settings'; }
	
	$eePLP_Output .= '<h2 class="nav-tab-wrapper">';
	
	// Settings
	$eePLP_Output .= '<a href="?page=' . $eePLP_Page . '&tab=settings" class="nav-tab '; 
	if($active_tab == 'settings') {$eePLP_Output .= '  eeActiveTab '; }
	$active_tab == 'settings' ? 'nav-tab-active' : '';    
    $eePLP_Output .= $active_tab . '">' . __('Settings', 'ee-protect-login-page') . '</a>';
    
    // Plugin Instructions
    $eePLP_Output .= '<a href="?page=' . $eePLP_Page . '&tab=instructions" class="nav-tab ';  
	if($active_tab == 'instructions') {$eePLP_Output .= '  eeActiveTab '; }   
    $active_tab == 'support' ? 'nav-tab-active' : ''; 
    $eePLP_Output .= $active_tab . '">' . __('Instructions', 'ee-protect-login-page') . '</a>';
    
    // The Help / Email Form Page
    $eePLP_Output .= '<a href="?page=' . $eePLP_Page . '&tab=help" class="nav-tab ';   
	if($active_tab == 'help') {$eePLP_Output .= '  eeActiveTab '; }  
    $active_tab == 'help' ? 'nav-tab-active' : ''; 
    $eePLP_Output .= $active_tab . '">' . __('Help', 'ee-protect-login-page') . '</a>';
    
    // Author
    $eePLP_Output .= '<a href="?page=' . $eePLP_Page . '&tab=author" class="nav-tab ';   
	if($active_tab == 'author') {$eePLP_Output .= '  eeActiveTab '; }  
    $active_tab == 'author' ? 'nav-tab-active' : ''; 
    $eePLP_Output .= $active_tab . '">' . __('Author', 'ee-protect-login-page') . '</a>';
    
    // Donate to Feel Great
    $eePLP_Output .= '<a href="?page=' . $eePLP_Page . '&tab=donate" class="nav-tab ';    
	if($active_tab == 'donate') {$eePLP_Output .= '  eeActiveTab '; } 
    $active_tab == 'donate' ? 'nav-tab-active' : ''; 
    $eePLP_Output .= $active_tab . '">' . __('Donate', 'ee-protect-login-page') . '</a>';
    
    $eePLP_Output .= '</h2>'; // END Tabs
    
    
    // Which Tab to Display? --------------------
    
	if($active_tab == 'settings') {	
	
		$eePLP_STATE = get_option('eePLP_STATE');
		$eePLP_PIN = get_option('eePLP_PIN');
		
		$eePLP_Output .= '<div class="wrap eeAdmin" id="eePLP">
		
		<h1>Protect Login Page</h1>
	
		<form action="' . $_SERVER['PHP_SELF'] . '?page=ee-protect-login-page" method="post">
		
			<input type="hidden" name="eePLP" value="TRUE" />';
			
		// If set, display the link needed to reach the login page
		if($eePLP_STATE == 'ON' AND $eePLP_PIN) {
				
			$eePLP_Output .= '<fieldset class="eePLP_Set">
				
				<h2>PIN is Set</h2>';
				
			$eePLP_URL = 'http://' . $_SERVER['HTTP_HOST'] . '/wp-login.php?PIN=' . $eePLP_PIN;
				
			$eePLP_Output .= '<p>You must use this link to reach your website\'s login page: <br />
					
					<a href="' . $eePLP_URL . '"><strong>' . $eePLP_URL . '</strong></a></p>
				
			</fieldset>';
			
		}
			
		$eePLP_Output .= '<fieldset>
		
		<h2>Set Your PIN</h2>' . 
		
		"\n\n" . wp_nonce_field( 'ee-protect-login-page', 'ee-protect-login-page-nonce' ) . "\n\n";	
				
		$eePLP_Output .= '<label for="eePLP_STATE">Activate PIN:</label>
		
			<input type="checkbox" name="eePLP_STATE" value="ON" id="eePLP_STATE"';
				
		if($eePLP_STATE == 'ON') { $eePLP_Output .= ' checked="checked"'; }
				
		$eePLP_Output .= '/>
			
			<div class="eeNote">Protect your website\'s login page with a simple PIN. The login page will only appear if the correct PIN is provided.</div>
			
			<label for="eePLP_PIN">PIN:</label><input type="text" name="eePLP_PIN" value="';
				
		if($eePLP_PIN) { $eePLP_Output .= $eePLP_PIN; }
				
		$eePLP_Output .= '" id="eePLP_PIN" maxlength="8" />
			
			<div class="eeNote">Enter the PIN you want to use, up to 8 numbers.</div>
			
			<br class="eeClearFix" />
		
			<input type="submit" name="submit" id="submit" value="SAVE" />
			
		</fieldset></form></div>';
		
		// Nonce 'ee_include_page' for the following includes was created above
		
		} elseif($active_tab == 'instructions') { // Instructions Tab Display...
			
			$eePLP_Output .= '<h2>' . __('Instructions', 'ee-protect-login-page') . '</h2>';
				
			// Get the instructions page
			include($eePluginPath . '/support/ee-plugin-instructions.php');
		
		} elseif($active_tab == 'help') { // Support Tab Display...
			
			// Get the support page
			include($eePluginPath . '/support/ee-plugin-support.php');
		
		} elseif($active_tab == 'author') { // About
						
			// Get the support page
			include($eePluginPath . '/support/ee-plugin-author.php');
			
		} else { // Please Donate - DEFAULT TAB
				
			// Get the support page
			include($eePluginPath . '/support/ee-donations.php');
			
		}
		
		if(@$eeOutput) {
			$eePLP_Output .= $eeOutput; // Include the included page's HTML buffer
		}
			
		$eePLP_Output .= '<div id="eeAdminFooter">
		
				<fieldset>
				<p>' . __('Protect Login Page', 'ee-protect-login-page') . '</p>
				
				<p><a href="' . $eeWebsiteLink . '">' . $eePluginName . ' &rarr; ' . __('Version', 'ee-protect-login-page') . ' ' . $eeVersion;
	
				$eePLP_Output .= '</a></p></fieldset>
		
			</div>
		</div>'; // END .wrap
    
	
    // Dump the HTML buffer
    echo $eePLP_Output;	
	
	// Closing function operations
	
	if($eeErrors OR $eeDevMode) { 
		
		if($eeMessages) { $eeLog[] = $eeMessages; }
		if($eeErrors) { $eeLog[] = $eeErrors; }
		
		// $eePLP_Output .= '<pre>' . print_r($eeLog) . '</pre>';
		
		eePLP_WriteLogFile($eeLog);
		
	}
}


// The PIN Checker
function eePLP_Protect() {
	
	$eePLP_STATE = get_option('eePLP_STATE'); // Get DB Option value
	$eePLP_PIN = get_option('eePLP_PIN');
	
	if($eePLP_STATE != 'ON' OR !$eePLP_PIN) { return TRUE; }
	
	if(@$_POST['log']) { // Login, PIN has been accepted
		
		// Check the form was submitted from here.
		if(!strpos($_SERVER['HTTP_REFERER'], $eePLP_PIN)) {
			
			exit(); // Dead End
		}
		
	} elseif(@$_GET['action'] == 'logout' AND check_admin_referer('log-out')) { // Logging out...
		
		// Proceed . . .
		return TRUE; // Proceed to login page so we can log out.
		
	} elseif(@$_GET['loggedout']) {  // Logged out	
		
		$eeOutput = __('Logged Out', 'ee-protect-login-page') . '...
		
		<meta http-equiv="refresh" content="1;url=/">'; // Redirect the browser to the website home page.
		
		exit($eeOutput);
		
	} else { // Logging in. Check the PIN
		
		// This PIN must be passed in order to access this page
		$thePIN = @$_GET['PIN']; 
		
		if($thePIN != $eePLP_PIN) { // FAIL
			
			// TO DO - Abuse Prevention
			$eePLP_AbuseCheck = FALSE; // <<<------- TO DO - Track current login attempts and block abusers.
			
			// Check for the rescue PIN
			if(@$_GET['rescuePIN']) {
				
				$rescuePIN = filter_var($_GET['rescuePIN'], FILTER_VALIDATE_INT);
				
				if(md5($rescuePIN) == '4e54d70fea5046fa0bd23cd0f4df3e94') { // If it matches
					update_option('eePLP_STATE', 'OFF'); // Turn off the protection
					return TRUE; // Proceed to login page
				}	
			
			}
			
			$eeOutput = '<body style="text-align:center; margin:100px auto; font-size:150%; background-color:black; color:white;">
			
			<h1>' . __('Access Denied', 'ee-protect-login-page') . '</h1>
			
			<p>' . __('Missing Proper Credentials', 'ee-protect-login-page') . '</p>
			
			<p><em>' . __('This website is protected by', 'ee-protect-login-page') . ' <br />
			
			<a style="color:yellow;" href="https://elementengage.com/protect-login-page/">' . __('Protect Login Page', 'ee-protect-login-page') . '</a>
			
			</body>';
			
			exit($eeOutput);
		
		} else {
			return TRUE; // PASS
		}	
	}
}

add_action( 'login_enqueue_scripts', 'eePLP_Protect');


// Write log file
function eePLP_WriteLogFile($eeLog) {
	
	if($eeLog) {
		
		$eeLogFile = plugin_dir_path( __FILE__ ) . 'logs/EE-Log.txt';
		
		// File Size Management
		$eeLimit = 262144; // 262144 = 256kb  1048576 = 1 MB
		$eeSize = @filesize($eeLogFile);
		
		if(@filesize($eeLogFile) AND $eeSize > $eeLimit) {
			unlink($eeLogFile); // Delete the file. Start Anew.
		}
		
		// Write the Log Entry
		if($handle = @fopen($eeLogFile, "a+")) {
			
			if(@is_writable($eeLogFile)) {
			    
				fwrite($handle, 'Date: ' . date("Y-m-d H:i:s") . "\n");
			    
			    foreach($eeLog as $key => $logEntry){
			    
			    	if(is_array($logEntry)) { 
				    	
				    	foreach($logEntry as $key2 => $logEntry2){
					    	fwrite($handle, '(' . $key2 . ') ' . $logEntry2 . "\n");
					    }
					    
				    } else {
					    fwrite($handle, '(' . $key . ') ' . $logEntry . "\n");
				    }
			    }
			    	
			    fwrite($handle, "\n\n\n---------------------------------------\n\n\n"); // Separator
			
			    fclose($handle);
			    
			    return TRUE;
			 
			} else {
			    return FALSE;
			}
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}
