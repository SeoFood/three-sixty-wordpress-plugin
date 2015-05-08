<?php
/**
 * @package Three_Sixty
 * @version 1.0
 */

/*
Plugin Name: Three Sixty Image Slider Wordpress Plugin
Plugin URI: http://www.gravio.de/magazin/
Description: This Plugin uses the Threesixty-slider jQuery Slider plugin in a nice Wordpress Plugin.
Author: Marco Hillger
Version: 1.0
Author URI: http://seofood.de/
*/

$three_sixty = new Three_Sixty();

class Three_Sixty {

	/**
	 * If you should add the script or not
	 *
	 * @var bool
	 */
	private $addScript = false;

	/**
	 * If you should add the style or not
	 *
	 * @var bool
	 */
	private $addStyle = false;

	/**
	 * Default Shortcode Arguments
	 *
	 * @var array
	 */
	private $args = array(
		'width'    => 400,
		'height'   => 240,
		'frames'   => 24,
		'start'    => 1,
		'prefix'   => 'image-',
		'ext'      => 'jpg',
		'dir'      => '',
		'nav'      => 'true',
		'autoplay' => 'true',
		'id'       => 'threesixty',
		'responsive' => 'true'
	);

	public function __construct() {

		add_shortcode( '360', array( $this, 'shortcode_content' ) );

		// Add styles and scripts to the page
		add_action( 'wp_footer', array( $this, 'add_shortcode_scripts' ), 10 );
		add_action( 'wp_footer', array( $this, 'add_shortcode_styles' ), 10 );
	}

	public function shortcode_content( $attr, $content ) {
		$this->addScript = true;
		$this->addStyle = true;

		$this->args = shortcode_atts( $this->args, $attr );
		$this->args['dir'] = content_url($this->args['dir']);

		add_action( 'wp_footer', array( $this, 'add_inline_script'), 101 );

		return <<<EOT
			<div id="{$this->args['id']}" class="threesixty">
			    <div class="spinner">
			        <span>0%</span>
			    </div>
			    <ol class="threesixty_images"></ol>
			</div>
EOT;

	}

	public function add_inline_script() {

		if( wp_script_is( 'jquery', 'done' ) ) {

			echo '<script type="text/javascript">';

			echo <<<EOT
			    var threeSixty = jQuery('#{$this->args['id']}').ThreeSixty({
			        totalFrames: {$this->args['frames']}, // Total no. of image you have for 360 slider
			        endFrame: {$this->args['frames']}, // end frame for the auto spin animation
			        currentFrame: {$this->args['start']}, // This the start frame for auto spin
			        imgList: '.threesixty_images', // selector for image list
			        progress: '.spinner', // selector to show the loading progress
			        imagePath: '{$this->args['dir']}/', // path of the image assets
			        filePrefix: '{$this->args['prefix']}', // file prefix if any
			        ext: '.{$this->args['ext']}', // extention for the assets
			        height: {$this->args['height']},
			        width: {$this->args['width']},
			        navigation: {$this->args['nav']},
			        disableSpin: true,
			        responsive: {$this->args['responsive']}
			    });

EOT;

			if ($this->args['autoplay'] == 'true') {
				echo <<<EOT
					threeSixty.play();
					jQuery('#{$this->args['id']}').bind('click', function(e) {
					      threeSixty.stop();
                    });
EOT;

				echo '';
			}

			echo '</script>';
		}

	}

	public function add_shortcode_scripts() {
		if ( ! $this->addScript ) {
			return false;
		}

		wp_enqueue_script( 'three-sixty-js', plugins_url( 'js/plugin.min.js', __FILE__ ), false );
	}

	public function add_shortcode_styles() {
		if ( ! $this->addStyle ) {
			return false;
		}

		wp_enqueue_style( 'three-sixty-css', plugins_url( 'css/plugin.min.css', __FILE__ ), false );
	}
}