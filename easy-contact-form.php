<?php
/**
 * Hammani Tech Plugin.
 *
 * Example plugin to demonstrate the ability to handle bundled plugins with the
 *
 *
 * @package     WordPress\Plugins\Hammani Tech Plugin
 * @author      Hammad Ashfaq <hammad@hammanitech.com>
 * @link        https://github.com/thomasgriffin/TGM-Plugin-Activation
 * @version     1.0.1
 * @copyright   2011-2016 Thomas Griffin
 * @license     http://creativecommons.org/licenses/GPL/3.0/ GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: Easy Contact Form
 * Plugin URI:  https://hammanitech.com/
 * Description: Just another contact form plugin. Simple but flexible.
 * Author:      Hammad Ashfaq
 * Version:     1.0.1
 * Text Domain: easy-contact-form
 * Domain Path: /languages
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * 
 */

// Avoid direct calls to this file.
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! function_exists( 'tgm_php_mysql_versions' ) ) {

	add_action( 'rightnow_end', 'tgm_php_mysql_versions', 9 );

	/**
	 * Displays the current server's PHP and MySQL versions right below the WordPress version
	 * in the Right Now dashboard widget.
	 *
	 * @since 1.0.0
	 */
	function tgm_php_mysql_versions() {
		echo wp_kses(
			sprintf(
				/* TRANSLATORS: %1 = php version nr, %2 = mysql version nr. */
				__( '<p>You are running on <strong>PHP %1$s</strong> and <strong>MySQL %2$s</strong>.</p>', 'tgm-example-plugin' ),
				phpversion(),
				$GLOBALS['wpdb']->db_version()
			),
			array(
				'p' => array(),
				'strong' => array(),
			)
		);
	}
}

// Avoid direct calls to Plugin.
if(!defined('WPINC')){
	die;
} 


// Activation Hook database table create


register_activation_hook( __FILE__, 'create_ecf_database_table' );
function create_ecf_database_table()
{	
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name =  $wpdb->prefix.'easy_contact_form';
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			check_count  varchar(256) NOT NULL,
			email_to  varchar(256) NOT NULL,
			site_key  varchar(256) NOT NULL,
			secret_key  varchar(256) NOT NULL,
			check_recaptcha  varchar(256) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$id = 1;
		$check_count = 0;
		$email_to = get_bloginfo('admin_email');
		$site_key  = '';
		$secret_key = '';
        $check_recaptcha = 0;
	  	
		if( $print-> id == ""   || $print-> check_count == ""   || $print-> email_to == "" || $print-> site_key == ""   || $print-> secret_key == ""){
			$sql = $wpdb->insert(	$table_name , array("id" => $id , "check_count" => $check_count , "email_to" => $email_to ,"site_key" => $site_key , "secret_key" => $secret_key ,"check_recaptcha" => $check_recaptcha ));
		}else {
		   echo "<h1>Data is here</h1>";
		}
  

 }


 // deActivation HooK drop database table
register_deactivation_hook(__FILE__, 'drop_ecf_database_table');

function drop_ecf_database_table()
{
	global $wpdb;
	$table_name =  $wpdb->prefix.'easy_contact_form';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
    delete_option("devnote_plugin_db_version");
}


//pluign constant defined

if( !defined('ECF_PLUGIN_DIR')) {
	define('ECF_PLUGIN_DIR ' , plugin_dir_url(__FILE__)); 
}


if( !defined('ECF_PLUGIN_VERSION')){
	define('ECF_PLUGIN_VERSION' , '1.0.0');
} 


// add relative files in plugin

if( !function_exists('ecf_plugin_scripts')) {

    function ecf_plugin_scripts(){
	  
	 wp_enqueue_script( 'jquery');
     wp_enqueue_style('ecf-css' , plugin_dir_url(__FILE__). 'assets/css/style.css' );  
	 
	 wp_enqueue_script('ecf-ajax' , plugin_dir_url(__FILE__). 'assets/js/main.js', 'jQuery' , '1.0.0' , true ); 

      wp_localize_script('ecf-ajax' , 'ecf_ajax_url' , array('ajax_url' => admin_url('admin-ajax.php')));
    }
add_action('wp_enqueue_scripts' ,'ecf_plugin_scripts'); 
 } 


//admin site file add in Plugin
function load_admin_styles() {
	wp_enqueue_style('ecf-admin-css' , plugin_dir_url(__FILE__). 'assets/css/admin-style.css' );   
}

add_action( 'admin_enqueue_scripts', 'load_admin_styles' );

 
// Add admin site menu

 function ecf_register_menu_page() { 
 
  add_menu_page('ECF Form Templates' ,'ECF Settings' , 'manage_options' , 'easy-contact-form/includes/ecf-admin-setting-controls.php' , '' , 'dashicons-email' , 30);
  
}
add_action('admin_menu' , 'ecf_register_menu_page'); // registar main menu & sub menu


// add shortcode in wordpress
 function form_templates($attr ) {
	$array = shortcode_atts(array(
		'width' => '500' ,
		'height' => '400'
            ) , $attr );

     require plugin_dir_path(__FILE__).'includes/ecf-settings-form-templates.php';

 }

add_shortcode("ecf_form", "form_templates"); 


// call ajax for form submission

add_action('wp_ajax_ecf_form_callback','ecf_form_callback');
add_action('wp_ajax_nopriv_ecf_form_callback', 'ecf_form_callback');


function ecf_form_callback(){
	
	global $wpdb; 
	$table_name =  $wpdb->prefix.'easy_contact_form';
	 
	$results = $wpdb->get_results( "SELECT * FROM $table_name");
	 foreach ( $results as $print ) {
	 }
	 
	$email_get = $print->email_to;
	$secretkey = $print->secret_key;
	$getRecaptcha = $print->check_recaptcha;
 
 $recaptcha = $_POST['recaptcha'];
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret='. $secretkey . '&response=' . $recaptcha;
    $data = file_get_contents($url);
    $resp = json_decode($data);
 
    if($getRecaptcha == 1){
  
      if ($resp->success) {
	     
		$first_name = $_POST['name'];
	    $email = $_POST['email'];
	    $subject = $_POST['subject'];
	    $message = $_POST['message'];
	    $body = 'Email:' .$email ."\n". ' First Name:' . $first_name."\n".'Subject:'. $subject."\n".'Message:'. $message;
	    $to = $email_get;
     	$headers = 'From: '. $email . "\r\n" .
	               'Reply-To: ' . $email . "\r\n";
        $mailSend =wp_mail( $to, $first_name, $body , $headers );
        sleep(2);

       if($mailSend){
         echo "Email has been sent successfully.";     
         } else{
		 echo "Failed To send Email";
	    }
 
      } else {
         echo "Opps you are Rebot😡😡";
     }
   } else{
  
  	$first_name = $_POST['name'];
	$email = $_POST['email'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$body = 'Email:' .$email ."\n". ' First Name:' . $first_name."\n".'Subject:'. $subject."\n".'Message:'. $message;
	$to = $email_get;
    $headers = 'From: '. $email . "\r\n" .
	'Reply-To: ' . $email . "\r\n";
    $mailSend =wp_mail( $to, $first_name, $body , $headers );
    sleep(2);

    if($mailSend){
	   echo "Email has been sent successfully.";     
    }else {
	   echo "Email  send failed.";     
    }
 
  }

 wp_die();

}


?>