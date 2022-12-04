<?php
/**
 * Plugin Name: CPT Draft Maker
 * Description: This is a plugin to make chosen CPT as draft.
 * Plugin URI:  https://sivacreative.com
 * Version:     1.0.0
 * Author:      Siva Creative
 * Author URI:  https://sivacreative.com
 * Text Domain: cpt-draft-maker-movies
 * Settings URI: #
 */
 
 if ( ! defined( 'ABSPATH' ) ) exit;
 
 
define( 'CPT_DRAFT_MAKER_PLUGIN', '1.0.0' );
define( 'CPT_DRAFT_MAKER_PREVIOUS_STABLE_VERSION', '1.0.0' );

define( 'CPT_DRAFT_MAKER__FILE__', __FILE__ );
define( 'CPT_DRAFT_MAKER_PLUGIN_BASE', plugin_basename( CPT_DRAFT_MAKER__FILE__ ) );
define( 'CPT_DRAFT_MAKER_PATH', plugin_dir_path( CPT_DRAFT_MAKER__FILE__ ) );

define( 'CPT_DRAFT_MAKER_MODULES_PATH', CPT_DRAFT_MAKER_PATH . 'modules/' );
define( 'CPT_DRAFT_MAKER_URL', plugins_url( '/', CPT_DRAFT_MAKER__FILE__ ) );
define( 'CPT_DRAFT_MAKER_ASSETS_URL', CPT_DRAFT_MAKER_URL . 'assets/' );
define( 'CPT_DRAFT_MAKER_MODULES_URL', CPT_DRAFT_MAKER_URL . 'modules/' );
define('CPT_NAME','movie');
define('CPT_key','cpt-draft-maker-movies');


 
class CPT_DRAFT_MAKER {

	private $replacements;

	private $settings;
	public function __construct() {
		
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		// We only need to register the admin panel on the back-end
		if ( is_admin() ) {
			add_action( 'admin_menu', array( 'CPT_DRAFT_MAKER', 'add_admin_menu' ) );
			add_action( 'admin_init', array( 'CPT_DRAFT_MAKER', 'register_settings' ) );
			add_action( 'admin_init', array( 'CPT_DRAFT_MAKER', 'make_draft' ) );
		}
	}

	public static function get_theme_options() {
		return get_option( 'theme_options' );
	}

	
	public function init() {
		
		add_action( 'wp_enqueue_scripts', array( $this, 'CPT_DRAFT_MAKER_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_CPT_DRAFT_MAKER_js' ) );
	
	}
	
	public static function get_theme_option( $id ) {
		$options = self::get_theme_options();
		if ( isset( $options[$id] ) ) {
			return $options[$id];
		}
	}


public function CPT_DRAFT_MAKER_scripts() {

	$asset_file_js = array('dependencies' => array('wp-hooks'), 'version' => date("h:i:s"));
	wp_register_script(
		'client-js-injection',
		CPT_DRAFT_MAKER_ASSETS_URL . 'js/client-cpt-draft-maker.js' ,
		$asset_file_js['dependencies'],
		$asset_file_js['version'],
		false
	);
	wp_enqueue_script( 'client-js-injection' );

	$asset_file_css = array('version' => date("h:i:s"));
    wp_register_style(
		'client-css-injection',
		CPT_DRAFT_MAKER_ASSETS_URL . 'css/client-cpt-draft-maker.css',
		null,
		$asset_file_css['version'],
		false
	);
	wp_enqueue_style( 'client-css-injection' );
 }

 public function admin_CPT_DRAFT_MAKER_js(){

	$admin_details_array = [1,1,3,5,8,13,21];
	wp_register_script( 'admin_backend_js_CPT_DRAFT_MAKER', CPT_DRAFT_MAKER_ASSETS_URL . 'js/admin-cpt-draft-maker.js', array ( 'jquery' ), date("h:i:s"), true);
	wp_localize_script('admin_backend_js_CPT_DRAFT_MAKER', 'admin_details_array', $admin_details_array);
	wp_enqueue_script('admin_backend_js_CPT_DRAFT_MAKER');
	wp_register_style( 'admin_CPT_DRAFT_MAKER_css',CPT_DRAFT_MAKER_ASSETS_URL . 'css/admin-cpt-draft-maker.css', false, date("h:i:s") );
    wp_enqueue_style( 'admin_CPT_DRAFT_MAKER_css' );

 }

 public static function register_settings() {
	register_setting( 'theme_options', 'theme_options', array( 'CPT_DRAFT_MAKER', 'sanitize' ) );

}

public static function add_admin_menu() {
	add_menu_page(
		esc_html__( 'CPT Draft Maker', CPT_key ),
		esc_html__( 'CPT Draft Maker', CPT_key),
		'manage_options',
		'cpt-draft-maker-options',//options page url
		array( 'CPT_DRAFT_MAKER', 'cpt_delete_page' )
	);
}


 public static function sanitize( $options ) {

	// If we have options lets sanitize them
	if ( $options ) {

		// Checkbox
		if ( ! empty( $options['checkbox_example'] ) ) {
			$options['checkbox_example'] = 'on';
		} else {
			unset( $options['checkbox_example'] ); // Remove from options if not checked
		}

		// Input
		/*if ( ! empty( $options['input_example'] ) ) {
			$options['input_example'] = sanitize_text_field( $options['input_example'] );
		} else {
			unset( $options['input_example'] ); // Remove from options if empty
		}

		// Select
		if ( ! empty( $options['select_example'] ) ) {
			$options['select_example'] = sanitize_text_field( $options['select_example'] );
		}*/

	}

	// Return sanitized options
	return $options;

}

public static function make_draft(){
	$post_args_published = array('post_type' => CPT_NAME,'post_status' => 'publish');
	$post_args_drafts = array('post_type' => CPT_NAME,'post_status' => 'draft');
    $pub_posts = get_posts($post_args_published); 
	$draft_posts = get_posts($post_args_drafts); 
	foreach($pub_posts as $dpost){
		$value = self::get_theme_option( $dpost->post_title );
		if($value=='on'){
			$post = array( 'ID' => $dpost->ID, 'post_status' => 'draft' );
			wp_update_post($post);
		}
	}
	foreach($draft_posts as $dpost){
		$value = self::get_theme_option( $dpost->post_title );
		if($value!='on'){
			$post = array( 'ID' => $dpost->ID, 'post_status' => 'publish' );
			wp_update_post($post);
		}
	}

}

 public static function cpt_delete_page() { 
	

	
	?>

	<div class="wrap">

		<h1><?php esc_html_e( 'CPT Drafts Settings', CPT_key); ?></h1>

		<form method="post" action="options.php">

			<?php settings_fields( 'theme_options' ); ?>
			<h4><b>Check Field And Save To Draft and Publish</b></h4>
			<table class="form-table wpex-custom-admin-login-table">
            <?php

$post_args = array('post_type' => CPT_NAME,
'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')    );
$press_posts = get_posts($post_args);   

if(!empty($press_posts)){
foreach($press_posts as $single_post){
    ?>
    <br><?php
// Checkbox example ?>
<tr valign="top">
	<th scope="row"><?php esc_html_e( $single_post->post_title, CPT_key); ?></th>
	<td>
		<?php $value = self::get_theme_option( $single_post->post_title ); ?>
		<input type="checkbox" name="<?php echo "theme_options[" . $single_post->post_title ."]"; ?>" <?php checked( $value, 'on' ); ?>> <?php esc_html_e($single_post->post_title , CPT_key); ?>
	</td>
</tr><?php
}
}
?>



			   
				

			 

			</table>


			
			<?php submit_button(); ?>

		</form>

	</div><!-- .wrap -->
<?php }






	
	
	
	
} 

// Instantiate Referral Replace Content.
new CPT_DRAFT_MAKER();

function myprefix_get_theme_option( $id = '' ) {
	return CPT_DRAFT_MAKER::get_theme_option( $id );
} 