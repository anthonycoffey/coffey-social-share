<?php
/**
 * Plugin Name: Coffey Social Share
 * Description: A simple plugin for displaying social media icons to share content
 * Author: Anthony Coffey
 * Author URI: http://coffeywebdev.com
 */

if ( ! defined( 'ABSPATH' ) )
die("You don't have sufficient permission to access this page");

require_once('includes/admin-form.php');
require_once('includes/buttons.php');

class coffey_social_share{

	public function __construct() {

		// register our settings page
		add_action( 'admin_menu', array( $this, 'register_submenu' ) );

		// register setting
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// add scripts
		add_action( 'wp_enqueue_scripts', array( $this,'load_styles_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this,'load_admin_styles_scripts' ) );
		add_action('wp_footer', array($this,'add_scripts_to_footer'));

		// add share icon markup
		add_filter( 'the_content', array( $this, 'append_sharediv' ) );
		add_filter('post_thumbnail_html',array( $this, 'append_sharediv_to_featured_image') );
		add_filter('wp_footer',array( $this, 'append_sharediv_to_footer') );
		add_shortcode('coffey-social-share', array($this,'html_markup' ));

		register_activation_hook( __FILE__, array( $this, 'load_defaults' ) );

	}



	public function load_admin_styles_scripts(){
		wp_enqueue_style( 'coffey-social-share-admin', plugins_url('css/admin.css',__FILE__) );
		wp_enqueue_script( 'cs-color-picker', plugins_url('js/jscolor.min.js',__FILE__));
	}

	public function load_styles_scripts(){
		$coffey_options = get_option('coffey_options');
		wp_enqueue_style( 'font-awesome-social-icons', '//netdna.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.css');

		wp_enqueue_style( 'coffey-social-share-main', plugins_url('css/style.css',__FILE__) );
	}

	public function add_scripts_to_footer() {
		// forces all share links to open in popup window
		echo "<script>jQuery(document).ready(function($) {
								    $('.cs-share-link').click(function(e) {
								        var id = $(this).data('windowid');
								        if(id == null || id.closed) {
								            id =  window.open($(this).attr('href'), '_blank', 'width=600,height=400');
								        }
								        id.focus();
								        $(this).data('windowid', id);
								        e.preventDefault();
								        return false;
								    });
								});
					</script>";
			// include whatsapp script
			echo '<script type="text/javascript"> if(typeof wabtn4fg==="undefined")   {wabtn4fg=1;h=document.head||document.getElementsByTagName("head")[0],s=document.createElement("script");s.type="text/javascript";s.src="//cdn.jsdelivr.net/whatsapp-sharing/1.3.3/whatsapp-button.js";h.appendChild(s)}</script>';
	}

	public function get_services() {
		return array('facebook', 'twitter', 'googleplus', 'linkedin', 'pinterest', 'whatsapp');
	}

	public function load_defaults(){
		update_option( 'coffey_options', $this->get_defaults() );
	}

	public function append_sharediv( $content ) {

		$coffey_options = get_option('coffey_options');
		// get current post's id
		global $post;
		$post_id = $post->ID;

		if( is_home() && !in_array( 'home', (array)$coffey_options['cs-show-on'] ) )
			return $content;
		if( is_single() && !in_array( 'posts', (array)$coffey_options['cs-show-on'] ) )
			return $content;
		if( is_page() && !in_array( 'pages', (array)$coffey_options['cs-show-on'] ) )
			return $content;
		// check custom post types too
		$custom_post_types = coffey_social_share::get_registered_custom_posttypes();
			foreach ($custom_post_types as $key => $value) {
				if ( is_singular( $value ) && !in_array( $value, (array)$coffey_options['cs-show-on'] ))
    			return $content;
			}

		$coffey_html_markup = $this->html_markup();

		if( is_array($coffey_options['cs-select-position']) && in_array('after-title', $coffey_options['cs-select-position']) )
			$content = $coffey_html_markup.$content;
		if( is_array($coffey_options['cs-select-position']) && in_array('after-content', (array)$coffey_options['cs-select-position']) )
			$content .= $coffey_html_markup;
		return $content;
	}

	public function append_sharediv_to_featured_image($html){

		$coffey_options = get_option('coffey_options');

		if( is_single() && !in_array( 'posts', (array)$coffey_options['cs-show-on'] ) )
			return $html;
		if( is_page() && !in_array( 'pages', (array)$coffey_options['cs-show-on'] ) )
			return $html;

		if( is_home() && !in_array( 'home', (array)$coffey_options['cs-show-on'] ) )
			return $html;
		// check custom post types too
		$custom_post_types = coffey_social_share::get_registered_custom_posttypes();
			foreach ($custom_post_types as $key => $value) {
				if ( is_singular( $value ) && !in_array( $value, (array)$coffey_options['cs-show-on'] ))
    			return $html;
			}

		if( !empty($coffey_options['cs-select-position']) && is_array($coffey_options['cs-select-position']) && in_array('inside-featured-img', $coffey_options['cs-select-position']) && is_single() ){
			return $html .= '<div class="ft-img-share ft-img-share-icons-'.$coffey_options['cs-select-size'].'">'.$this->html_markup().'</div>';
		}	else{
			return $html;
		}
	}

	public function append_sharediv_to_footer(){

	$coffey_options = get_option('coffey_options');

	if( !empty($coffey_options['cs-select-position']) && is_array($coffey_options['cs-select-position']) && in_array('floating-toolbar', $coffey_options['cs-select-position']) ){

		if( is_single() && in_array( 'posts', (array)$coffey_options['cs-show-on'] ) ){

			echo '<div class="floating-share-icons">'.$this->html_markup().'</div>';

		}
		if( is_page() && in_array( 'pages', (array)$coffey_options['cs-show-on'] ) ){

			echo '<div class="floating-share-icons">'.$this->html_markup().'</div>';

		}

		if( is_home() && in_array( 'home', (array)$coffey_options['cs-show-on'] ) ){

			echo '<div class="floating-share-icons">'.$this->html_markup().'</div>';

		}
		// check custom post types too
		$custom_post_types = coffey_social_share::get_registered_custom_posttypes();
			foreach ($custom_post_types as $key => $value) {
				if ( is_singular( $value ) && in_array( $value, (array)$coffey_options['cs-show-on'] ))

					echo '<div class="floating-share-icons">'.$this->html_markup().'</div>';

			}

		}

	}

	public function get_defaults($preset=true) {
		return array(
				'cs-select-style' => 'small-buttons',
				'cs-select-size'  => 'small',
				'cs-available-services' => $this->get_services(),
				'cs-selected-services' => $preset ? $this->get_services() : array(),
				'cs-select-position' => $preset ? array('after-title') : array(),
				'cs-color' => '',
				'cs-color-select' => '',
				'cs-order' => array('facebook'=>1,'twitter'=>2,'googleplus'=>3,'linkedin'=>4,'pinterest'=>5,'whatsapp'=>6),
				'cs-show-on' => $preset ? array('posts') : array()
				);
	}

	public function get_options() {
		return array_merge( $this->get_defaults(false), get_option('coffey_options') );
	}

	/* Generate HTML for Share Icons */
	public function html_markup() {

		$coffey_options = get_option('coffey_options');

		// add class if necessary
		$class = '';

		$featured_image_url =  (has_post_thumbnail(get_the_ID())) ? wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()),'medium') : '';

		$service_markup_arr = cs_get_buttons_markup_arr(get_the_permalink(),$featured_image_url, get_the_title());

		$sorted_services= $coffey_options['cs-order'];
		uasort($sorted_services,array($this,'sort_services'));
		$html_markup = '';

			foreach ($sorted_services as $key => $value) {
				if( in_array($key, (array)$coffey_options['cs-selected-services']) ){
						// if user on mobile device, show whatsapp link
						if($key=='whatsapp'){
							//if(wp_is_mobile()){
								$html_markup .= 	$service_markup_arr[$key];
						//	}
						} else {
							// if not whatsapp then disply without mobile check
							$html_markup .= 	$service_markup_arr[$key];
						}
				}
			}

		return '<div id="s-share-buttons" class="'.$class.'">'.$html_markup.'</div>';

	}

	/* Register Settings */
	public function register_settings(){

		register_setting( 'coffey_options', 'coffey_options' );

	}

	/*
	 * Add sub menu page in Settings for configuring plugin
	 */
	public function register_submenu(){

		add_submenu_page( 'options-general.php', 'Coffey Social Share Settings', 'Coffey Social Share', 'activate_plugins', 'coffey-social-share-settings', array( $this, 'submenu_page' ) );

	}

	/*
	 * Callback for add_submenu_page for generating markup of page
	 */
	public function submenu_page() {
		?>
		<div class="wrap">
			<h2>Settings</h2>
			<form method="POST" action="options.php">
			<?php settings_fields('coffey_options'); ?>
			<?php
			$coffey_options = get_option('coffey_options');
			$coffey_options['cs-available-services'] = $this->get_services();
			?>
			<?php cs_admin_form($coffey_options); ?>
		</div>
		<?php
	}
	/*
	 * Get registered post types
	 * @return array
	 */
	public static function get_registered_custom_posttypes(){
		return get_post_types(['public'   => true,'_builtin' => false]);
	}

	/* Function used to sort array by value */
	public function sort_services($a, $b) {
		    if ($a == $b) {
		        return 0;
		    }
		    return ($a < $b) ? -1 : 1;
	}

}
new coffey_social_share;

?>
