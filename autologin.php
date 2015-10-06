<?php  
/**
 * Plugin Name: My Auto Login Link
 * Plugin URI: http://neowebsolution.com
 * Description: This plugin adds some Fun to your website 
 * Version: 1.0.0
 * Author: Keshav Kalra
 */

function loginUserAutoKeshav(){
$user_id = makedecrypted($_GET['hashtag']) ;
$user = get_user_by( 'id', $user_id ); 
if( $user ) {
    wp_set_current_user( $user_id, $user->user_login );
    wp_set_auth_cookie( $user_id );
    do_action( 'wp_login', $user->user_login );
}
}
///this is used for making the code not in not visible form

function makeencrypted($id){
		
			$tagcode = base64_encode(serialize(base64_encode($id)));
			return $tagcode;
}


///this is used for making the function the code in formated as we need

function makedecrypted($id){
$tagcode = base64_decode(unserialize(base64_decode($id)));
return $tagcode;
}


add_action( 'admin_menu', 'register_my_custom_menu_page' );

function register_my_custom_menu_page(){
	add_menu_page( 'Custom Login Link', 'Manage User Links', 'manage_options', 'customloginurl', 'my_custom_menu_page'); 
}

function my_custom_menu_page(){
	echo "Admin Page Test";	
	global $wpdb;
	$wp_user_search = $wpdb->get_results("
SELECT u.ID, u.display_name, um.meta_value
FROM $wpdb->users u
JOIN $wpdb->usermeta um ON um.user_id = u.ID
WHERE um.meta_key = 'first_name' or um.meta_key = 'last_name'
ORDER by um.meta_value
ASC");
print_r($wp_user_search);
}
///now time to save the 

?>