<?php
/**
 * Plugin Name: Rainy Image for Visual Composer (beta)
 * Plugin URI: http://kowsarhossain.com/
 * Description: Visual Composer Addon. This plugin adds a rainy image element into visual composer
 * Version: 0.9.1
 * Author: Md. Kowsar Hossain
 * Author URI: http://kowsarhossain.com
 * Text Domain: vc-rainy-image
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

if ( ! defined( 'WPINC' ) ) die;

class RIVC_Rainy_Image{
	private static $instance = null;

	private function __construct() {
		if ( !in_array( 'js_composer/js_composer.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )){
			return;
		}
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_and_styles' ));
		add_shortcode( 'rainyimage', array( $this, 'rainyimage' ) );
		add_action( 'init', array( $this, 'vc_integration' ) );
	}

	// single instance
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	// scripts_and_styles
	public function scripts_and_styles(){
		wp_enqueue_script( 'rivc-rainy-script', plugin_dir_url( __FILE__ ).'rainyday.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'rivc-rainy-styles', plugin_dir_url( __FILE__ ).'style.css' );
	}

	// VC integration
	public function vc_integration() {
        // Check if Visual Composer is installed
		if ( ! defined( 'WPB_VC_VERSION' ) ) {	
			return;
		}

		vc_map( array(
			"name" => __("Rainy Image", 'vc-rainy-image'),
			"description" => __("Insert an image with rainy effect", 'vc-rainy-image'),
			"base" => "rainyimage",
			"class" => "",
			"controls" => "full",
			"icon" => plugins_url('icon.png', __FILE__),
			"category" => __('Content', 'js_composer'),
			"params" => array(
				array(
					"type" => "attach_image",
					"holder" => "div",
					"class" => "",
					"heading" => __("Image", 'vc-rainy-image'),
					"param_name" => "src",
					),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __("Alt Tag", 'vc-rainy-image'),
					"param_name" => "alt",
					),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __("Blur", 'vc-rainy-image'),
					"param_name" => "blur",
					"value"       => array('Enable','Disable'),
					"std"         => 'Enable'
					),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __("Opacity", 'vc-rainy-image'),
					"param_name" => "opacity",
					"value" => 1,
					"description" => __("Enter any number from 0 to 1. Default is 1", 'vc-rainy-image')
					),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __("Initial Numbers of Raindrops", 'vc-rainy-image'),
					"param_name" => "initial",
					"value" => 8000,
					"description" => __("Enter any number. Default is 8000", 'vc-rainy-image')
					),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __("Raindrop Intervals (ms)", 'vc-rainy-image'),
					"param_name" => "interval",
					"value" => 100,
					"description" => __("In miliseconds. Default is 100", 'vc-rainy-image')
					),

				)
			) );
	}


	// render shortcode
	public function rainyimage( $atts) {
		extract( shortcode_atts( array(
			'src'       => plugin_dir_url( __FILE__ ).'demo.jpg',
			'alt'       => '',
			'blur'      => 'Enable',
			'opacity'   => '1',
			'initial'   => '8000',
			'interval'  => '100',
			), $atts ) );

		// validation
		if ( is_numeric( $src ) ) {
			$src = wp_get_attachment_image_src( $src, 'full' )[0];
		}

		$blur     = ($blur == 'Enable') ? '10' : '0';
		$opacity  = floatval( $opacity );
		$initial  = intval( $initial );
		$interval = intval( $interval );

		ob_start();
		require 'view.php';
		$output = ob_get_clean();
		return $output;
	}

}

RIVC_Rainy_Image::instance();
